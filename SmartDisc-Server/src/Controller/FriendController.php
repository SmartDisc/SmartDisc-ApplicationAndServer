<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Entity\User;
use App\Repository\FriendshipRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use function is_string;

#[Route('/api/friends')]
class FriendController extends AbstractController
{
    #[Route(name: 'app_friends_list', methods: ['GET'])]
    public function list(#[CurrentUser] User $user, FriendshipRepository $friendshipRepository): JsonResponse
    {
        $friendships = $friendshipRepository->findAcceptedFor($user);

        return $this->json(array_map(
            fn (Friendship $friendship) => $this->serializeFriend($friendship, $user),
            $friendships,
        ));
    }

    #[Route('/requests', name: 'app_friends_requests_list', methods: ['GET'])]
    public function requestsList(#[CurrentUser] User $user, FriendshipRepository $friendshipRepository): JsonResponse
    {
        $friendships = $friendshipRepository->findPendingFor($user);

        return $this->json(array_map($this->serializeRequest(...), $friendships));
    }

    #[Route('/requests', name: 'app_friends_requests_create', methods: ['POST'])]
    public function requestsCreate(
        Request $request,
        #[CurrentUser] User $user,
        UserRepository $userRepository,
        FriendshipRepository $friendshipRepository,
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        try {
            $data = $request->toArray();
        } catch (JsonException) {
            return $this->json(['error' => 'Request body must be valid JSON.'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($data['email'] ?? null)) {
            return $this->json(['error' => 'email is a required string.'], Response::HTTP_BAD_REQUEST);
        }

        $email = mb_strtolower(trim($data['email']));
        $found = $userRepository->findOneBy(['email' => $email]);

        if (!$found instanceof User) {
            return $this->json(['error' => 'No user found with that email.'], Response::HTTP_NOT_FOUND);
        }

        if ($found->getId() === $user->getId()) {
            return $this->json(['errors' => ['email' => 'You cannot send a friend request to yourself.']], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (null !== $friendshipRepository->findActiveBetween($user, $found)) {
            return $this->json(['error' => 'A friendship or pending request already exists with that user.'], Response::HTTP_CONFLICT);
        }

        $friendship = new Friendship();
        $friendship->setRequester($user);
        $friendship->setAddressee($found);
        $friendship->setStatus('pending');

        $entityManager->persist($friendship);
        // Flush first so the friendship's auto-generated id is available for the
        // notification payload below.
        $entityManager->flush();

        $notificationRepository->createFor($found, 'friend_request', [
            'friendshipId' => $friendship->getId(),
            'fromUserId' => $user->getId(),
            'fromName' => $user->getName(),
        ]);

        $entityManager->flush();

        return $this->json([
            'id' => $friendship->getId(),
            'status' => $friendship->getStatus(),
            'toEmail' => $found->getEmail(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/requests/{id}/accept', name: 'app_friends_requests_accept', methods: ['POST'])]
    public function requestsAccept(
        int $id,
        #[CurrentUser] User $user,
        FriendshipRepository $friendshipRepository,
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $friendship = $friendshipRepository->find($id);

        if (!$this->isPendingRequestFor($friendship, $user)) {
            return $this->json(['error' => 'Friend request not found.'], Response::HTTP_NOT_FOUND);
        }

        $friendship->setStatus('accepted');
        $friendship->setRespondedAt(new DateTimeImmutable());

        $notificationRepository->createFor($friendship->getRequester(), 'friend_accepted', [
            'friendshipId' => $friendship->getId(),
            'byUserId' => $user->getId(),
            'byName' => $user->getName(),
        ]);

        $entityManager->flush();

        return $this->json(['id' => $friendship->getId(), 'status' => $friendship->getStatus()]);
    }

    #[Route('/requests/{id}/decline', name: 'app_friends_requests_decline', methods: ['POST'])]
    public function requestsDecline(
        int $id,
        #[CurrentUser] User $user,
        FriendshipRepository $friendshipRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $friendship = $friendshipRepository->find($id);

        if (!$this->isPendingRequestFor($friendship, $user)) {
            return $this->json(['error' => 'Friend request not found.'], Response::HTTP_NOT_FOUND);
        }

        $friendship->setStatus('declined');
        $friendship->setRespondedAt(new DateTimeImmutable());
        $entityManager->flush();

        return $this->json(['id' => $friendship->getId(), 'status' => $friendship->getStatus()]);
    }

    #[Route('/search', name: 'app_friends_search', methods: ['GET'])]
    public function search(
        Request $request,
        #[CurrentUser] User $user,
        UserRepository $userRepository,
        FriendshipRepository $friendshipRepository,
    ): JsonResponse {
        $q = trim((string) $request->query->get('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->json([]);
        }

        $excludeIds = $friendshipRepository->findRelatedUserIds($user);
        $results = $userRepository->searchByEmailOrName($q, $excludeIds, 10);

        return $this->json(array_map(static fn (User $found) => [
            'id' => $found->getId(),
            'name' => $found->getName(),
            'email' => $found->getEmail(),
        ], $results));
    }

    #[Route('/{friendshipId}', name: 'app_friends_remove', methods: ['DELETE'], requirements: ['friendshipId' => '\d+'])]
    public function remove(
        int $friendshipId,
        #[CurrentUser] User $user,
        FriendshipRepository $friendshipRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $friendship = $friendshipRepository->find($friendshipId);

        $isParty = $friendship instanceof Friendship
            && ($friendship->getRequester()?->getId() === $user->getId() || $friendship->getAddressee()?->getId() === $user->getId());

        if (!$friendship instanceof Friendship || 'accepted' !== $friendship->getStatus() || !$isParty) {
            return $this->json(['error' => 'Friend not found.'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($friendship);
        $entityManager->flush();

        return $this->json(['message' => 'Friend removed.']);
    }

    private function isPendingRequestFor(?Friendship $friendship, User $user): bool
    {
        return $friendship instanceof Friendship
            && $friendship->getAddressee()?->getId() === $user->getId()
            && 'pending' === $friendship->getStatus();
    }

    private function serializeFriend(Friendship $friendship, User $user): array
    {
        $other = $friendship->getRequester()?->getId() === $user->getId()
            ? $friendship->getAddressee()
            : $friendship->getRequester();

        return [
            'friendshipId' => $friendship->getId(),
            'id' => $other?->getId(),
            'name' => $other?->getName(),
            'email' => $other?->getEmail(),
        ];
    }

    private function serializeRequest(Friendship $friendship): array
    {
        $from = $friendship->getRequester();

        return [
            'id' => $friendship->getId(),
            'fromUserId' => $from?->getId(),
            'fromName' => $from?->getName(),
            'fromEmail' => $from?->getEmail(),
            'createdAt' => $friendship->getCreatedAt()->format(DATE_ATOM),
        ];
    }
}
