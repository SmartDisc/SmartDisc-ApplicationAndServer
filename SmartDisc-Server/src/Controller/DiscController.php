<?php

namespace App\Controller;

use App\Entity\Disc;
use App\Entity\DiscInvitation;
use App\Entity\User;
use App\Repository\DiscInvitationRepository;
use App\Repository\DiscRepository;
use App\Repository\FriendshipRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use function is_int;
use function is_string;

#[Route('/api/discs')]
class DiscController extends AbstractController
{
    // A hash of a password nobody can ever type, used to keep password_verify()'s
    // timing the same whether the disc exists or not — otherwise a missing disc
    // would return faster than a wrong password and leak which UUIDs are real.
    private const string DUMMY_HASH = '$2y$12$FRK4Ndm4rlS9zBSNRifxNuP/Y1FlD7C2za3ivzPV2X4/lGDK/wWvG';

    #[Route(name: 'app_discs_list', methods: ['GET'])]
    public function list(#[CurrentUser] User $user, DiscRepository $discRepository): JsonResponse
    {
        $discs = $discRepository->findBy(['owner' => $user]);

        return $this->json(array_map($this->serializeDisc(...), $discs));
    }

    #[Route('/shared', name: 'app_discs_shared', methods: ['GET'])]
    public function shared(#[CurrentUser] User $user): JsonResponse
    {
        // The inverse side of Disc::$sharedPeople — every disc someone else
        // owns but has shared access with this user.
        $discs = $user->getDiscs()->toArray();

        return $this->json(array_map($this->serializeSharedDisc(...), $discs));
    }

    #[Route('/{id}', name: 'app_discs_rename', methods: ['PATCH'])]
    public function rename(
        string $id,
        Request $request,
        #[CurrentUser] User $user,
        DiscRepository $discRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        try {
            $data = $request->toArray();
        } catch (JsonException) {
            return $this->json(['error' => 'Request body must be valid JSON.', 'code' => 'invalid_json_body'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($data['name'] ?? null)) {
            return $this->json(['error' => 'name is a required string.', 'code' => 'missing_required_fields'], Response::HTTP_BAD_REQUEST);
        }

        $name = trim($data['name']);
        if ('' === $name || mb_strlen($name) > 60) {
            return $this->json(['errors' => ['name' => 'Name must be between 1 and 60 characters.'], 'code' => 'invalid_disc_name'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $disc = $discRepository->find($id);

        // 404 (rather than 403) whether the disc doesn't exist or belongs to
        // someone else, so this endpoint can't be used to probe disc ownership.
        if (!$disc instanceof Disc || $disc->getOwner()?->getId() !== $user->getId()) {
            return $this->json(['error' => 'Disc not found.', 'code' => 'disc_not_found'], Response::HTTP_NOT_FOUND);
        }

        $disc->setName($name);
        $entityManager->flush();

        return $this->json($this->serializeDisc($disc));
    }

    #[Route('/claim', name: 'app_discs_claim', methods: ['POST'])]
    public function claim(
        Request $request,
        #[CurrentUser] User $user,
        DiscRepository $discRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        try {
            $data = $request->toArray();
        } catch (JsonException) {
            return $this->json(['error' => 'Request body must be valid JSON.', 'code' => 'invalid_json_body'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($data['id'] ?? null) || !is_string($data['password'] ?? null)) {
            return $this->json(['error' => 'id and password are required strings.', 'code' => 'missing_required_fields'], Response::HTTP_BAD_REQUEST);
        }

        $disc = $discRepository->find(trim($data['id']));
        $passwordValid = password_verify($data['password'], $disc?->getPassword() ?? self::DUMMY_HASH);

        if (!$disc instanceof Disc || !$passwordValid) {
            return $this->json(['errors' => ['password' => 'Invalid disc UUID or password.'], 'code' => 'invalid_disc_credentials'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (null !== $disc->getOwner()) {
            return $this->json(['errors' => ['id' => 'This disc has already been claimed.'], 'code' => 'disc_already_claimed'], Response::HTTP_CONFLICT);
        }

        $disc->setOwner($user);
        $entityManager->flush();

        return $this->json($this->serializeDisc($disc), Response::HTTP_CREATED);
    }

    #[Route('/{id}/invitations', name: 'app_discs_invitations_list', methods: ['GET'])]
    public function invitationsList(
        string $id,
        #[CurrentUser] User $user,
        DiscRepository $discRepository,
        DiscInvitationRepository $discInvitationRepository,
    ): JsonResponse {
        $disc = $discRepository->find($id);

        // 404 (rather than 403) whether the disc doesn't exist or belongs to
        // someone else, so this endpoint can't be used to probe disc ownership.
        if (!$disc instanceof Disc || $disc->getOwner()?->getId() !== $user->getId()) {
            return $this->json(['error' => 'Disc not found.', 'code' => 'disc_not_found'], Response::HTTP_NOT_FOUND);
        }

        $invitations = $discInvitationRepository->findPendingForDisc($disc);

        return $this->json(array_map($this->serializeInvitation(...), $invitations));
    }

    #[Route('/{id}/invitations', name: 'app_discs_invitations_create', methods: ['POST'])]
    public function invitationsCreate(
        string $id,
        Request $request,
        #[CurrentUser] User $user,
        DiscRepository $discRepository,
        UserRepository $userRepository,
        FriendshipRepository $friendshipRepository,
        DiscInvitationRepository $discInvitationRepository,
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $disc = $discRepository->find($id);

        if (!$disc instanceof Disc || $disc->getOwner()?->getId() !== $user->getId()) {
            return $this->json(['error' => 'Disc not found.', 'code' => 'disc_not_found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $data = $request->toArray();
        } catch (JsonException) {
            return $this->json(['error' => 'Request body must be valid JSON.', 'code' => 'invalid_json_body'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_int($data['friendId'] ?? null)) {
            return $this->json(['error' => 'friendId is a required integer.', 'code' => 'missing_required_fields'], Response::HTTP_BAD_REQUEST);
        }

        $friend = $userRepository->find($data['friendId']);

        if (!$friend instanceof User || !$friendshipRepository->isAcceptedFriend($user, $friend)) {
            return $this->json(['error' => 'That user is not one of your friends.', 'code' => 'not_your_friend'], Response::HTTP_NOT_FOUND);
        }

        if ($disc->getSharedPeople()->contains($friend) || null !== $discInvitationRepository->findPendingForDiscAndUser($disc, $friend)) {
            return $this->json(['error' => 'That user already has access to or a pending invitation for this disc.', 'code' => 'already_shared_or_invited'], Response::HTTP_CONFLICT);
        }

        $invitation = new DiscInvitation();
        $invitation->setDisc($disc);
        $invitation->setFromUser($user);
        $invitation->setToUser($friend);
        $invitation->setStatus('pending');

        $entityManager->persist($invitation);
        // Flush first so the invitation's auto-generated id is available for the
        // notification payload below.
        $entityManager->flush();

        $notificationRepository->createFor($friend, 'disc_invitation', [
            'invitationId' => $invitation->getId(),
            'discId' => $disc->getId(),
            'discName' => $disc->getName(),
            'fromUserId' => $user->getId(),
            'fromName' => $user->getName(),
        ]);

        $entityManager->flush();

        // Returns the same shape as the GET list endpoint (not just id/status):
        // the frontend appends this response directly into its pending-invites
        // list without refetching, so it needs toName/toEmail etc. up front —
        // otherwise the new row briefly renders with a blank name and a "?" avatar.
        return $this->json($this->serializeInvitation($invitation), Response::HTTP_CREATED);
    }

    #[Route('/{id}/invitations/{invitationId}', name: 'app_discs_invitations_cancel', methods: ['DELETE'])]
    public function invitationsCancel(
        string $id,
        int $invitationId,
        #[CurrentUser] User $user,
        DiscRepository $discRepository,
        DiscInvitationRepository $discInvitationRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $disc = $discRepository->find($id);

        if (!$disc instanceof Disc || $disc->getOwner()?->getId() !== $user->getId()) {
            return $this->json(['error' => 'Disc not found.', 'code' => 'disc_not_found'], Response::HTTP_NOT_FOUND);
        }

        $invitation = $discInvitationRepository->find($invitationId);

        if (!$invitation instanceof DiscInvitation || $invitation->getDisc()?->getId() !== $disc->getId()) {
            return $this->json(['error' => 'Invitation not found.', 'code' => 'invitation_not_found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($invitation);
        $entityManager->flush();

        return $this->json(['message' => 'Invitation cancelled.']);
    }

    #[Route('/{id}/people', name: 'app_discs_people_list', methods: ['GET'])]
    public function peopleList(
        string $id,
        #[CurrentUser] User $user,
        DiscRepository $discRepository,
    ): JsonResponse {
        $disc = $discRepository->find($id);

        if (!$disc instanceof Disc || $disc->getOwner()?->getId() !== $user->getId()) {
            return $this->json(['error' => 'Disc not found.', 'code' => 'disc_not_found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(array_map($this->serializePerson(...), $disc->getSharedPeople()->toArray()));
    }

    #[Route('/{id}/people/{userId}', name: 'app_discs_people_remove', methods: ['DELETE'])]
    public function peopleRemove(
        string $id,
        int $userId,
        #[CurrentUser] User $user,
        DiscRepository $discRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $disc = $discRepository->find($id);

        if (!$disc instanceof Disc || $disc->getOwner()?->getId() !== $user->getId()) {
            return $this->json(['error' => 'Disc not found.', 'code' => 'disc_not_found'], Response::HTTP_NOT_FOUND);
        }

        $person = $userRepository->find($userId);

        if (!$person instanceof User || !$disc->getSharedPeople()->contains($person)) {
            return $this->json(['error' => 'That person does not have access to this disc.', 'code' => 'person_no_disc_access'], Response::HTTP_NOT_FOUND);
        }

        $disc->removeSharedPerson($person);
        $entityManager->flush();

        return $this->json(['message' => 'Access removed.']);
    }

    private function serializePerson(User $person): array
    {
        return [
            'id' => $person->getId(),
            'name' => $person->getName(),
            'email' => $person->getEmail(),
        ];
    }

    private function serializeDisc(Disc $disc): array
    {
        return [
            'id' => $disc->getId(),
            'name' => $disc->getName(),
            'sharedCount' => $disc->getSharedPeople()->count(),
        ];
    }

    private function serializeSharedDisc(Disc $disc): array
    {
        $owner = $disc->getOwner();

        return [
            'id' => $disc->getId(),
            'name' => $disc->getName(),
            'ownerName' => $owner?->getName(),
            'ownerEmail' => $owner?->getEmail(),
            'sharedCount' => $disc->getSharedPeople()->count(),
        ];
    }

    private function serializeInvitation(DiscInvitation $invitation): array
    {
        $toUser = $invitation->getToUser();

        return [
            'id' => $invitation->getId(),
            'toUserId' => $toUser?->getId(),
            'toName' => $toUser?->getName(),
            'toEmail' => $toUser?->getEmail(),
            'createdAt' => $invitation->getCreatedAt()->format(DATE_ATOM),
        ];
    }
}
