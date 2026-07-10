<script setup>
import { ref, watch, onMounted } from 'vue'
import { UserPlus, Search, Check, X, UserMinus, Loader2 } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdAvatar from '@/components/ui/SdAvatar.vue'
import { SdCard, SdField, SdBtn, SdList, SdListRow, SdSectionLabel, SdBottomSheet } from '@/components/ui'
import { useFriends } from '@/composables/useFriends'
import { mapAuthError } from '@/stores/auth'
import { sanitizeText } from '@/utils/sanitize'
import { useI18n } from '@/i18n'

const { t } = useI18n()
const {
  friends, friendsError,
  requests,
  searchResults, searchLoading, searchError,
  fetchFriends, fetchRequests, searchUsers, clearSearch,
  sendRequest, acceptRequest, declineRequest, removeFriend,
} = useFriends()

onMounted(() => {
  fetchFriends().catch(() => {})
  fetchRequests().catch(() => {})
})

// ── Add friend: search by name/email ──────────────────────────────────────
const searchQuery = ref('')
const sendingId = ref(null)
const sendSuccess = ref('')
const sendError = ref('')

watch(searchQuery, (value) => {
  sendSuccess.value = ''
  sendError.value = ''
  searchUsers(value)
})

async function handleSendRequest(user) {
  sendingId.value = user.id
  sendError.value = ''
  sendSuccess.value = ''
  try {
    await sendRequest(user.email)
    sendSuccess.value = t('friends.add.sent', { name: user.name })
    searchQuery.value = ''
    clearSearch()
  } catch (err) {
    sendError.value = mapAuthError(err)
  } finally {
    sendingId.value = null
  }
}

// ── Incoming requests ────────────────────────────────────────────────────
const requestActionId = ref(null)
const requestsActionError = ref('')

async function handleAccept(id) {
  requestActionId.value = id
  requestsActionError.value = ''
  try {
    await acceptRequest(id)
  } catch (err) {
    requestsActionError.value = mapAuthError(err)
  } finally {
    requestActionId.value = null
  }
}

async function handleDecline(id) {
  requestActionId.value = id
  requestsActionError.value = ''
  try {
    await declineRequest(id)
  } catch (err) {
    requestsActionError.value = mapAuthError(err)
  } finally {
    requestActionId.value = null
  }
}

// ── Remove friend ────────────────────────────────────────────────────────
const removeSheet = ref(false)
const removeTarget = ref(null)
const removeLoading = ref(false)
const removeError = ref('')

function confirmRemove(friend) {
  removeTarget.value = friend
  removeError.value = ''
  removeSheet.value = true
}

async function handleRemove() {
  if (!removeTarget.value || removeLoading.value) return
  removeLoading.value = true
  removeError.value = ''
  try {
    await removeFriend(removeTarget.value.friendshipId)
    removeSheet.value = false
  } catch (err) {
    removeError.value = mapAuthError(err)
  } finally {
    removeLoading.value = false
  }
}
</script>

<template>
  <AppLayout :tabs="false">
    <SdAppBar back :title="t('friends.page.title')" />

    <!-- Add a friend -->
    <SdCard class="add-card">
      <div class="card-row card-row--mb">
        <UserPlus :size="16" style="color: var(--sd-azure);" />
        <span class="card-label">{{ t('friends.add.title') }}</span>
      </div>
      <SdField
        v-model="searchQuery"
        :placeholder="t('friends.add.placeholder')"
        :sanitize="sanitizeText"
        :maxlength="254"
      >
        <template #icon><Search :size="16" /></template>
      </SdField>

      <p v-if="sendSuccess" class="add-success">{{ sendSuccess }}</p>
      <p v-if="sendError" class="add-error">{{ sendError }}</p>
      <p v-if="searchError" class="add-error">{{ searchError }}</p>

      <p v-if="searchLoading" class="add-status">{{ t('friends.add.searching') }}</p>
      <p v-else-if="searchQuery && !searchResults.length && !searchError" class="add-status">
        {{ t('friends.add.noResults') }}
      </p>

      <div v-if="searchResults.length" class="search-results">
        <div v-for="u in searchResults" :key="u.id" class="search-result-row">
          <SdAvatar :name="u.name" :size="34" />
          <div class="search-result-info">
            <div class="search-result-name">{{ u.name }}</div>
            <div class="search-result-email">{{ u.email }}</div>
          </div>
          <SdBtn
            variant="ghost"
            size="sm"
            :disabled="sendingId === u.id"
            @click="handleSendRequest(u)"
          >
            {{ sendingId === u.id ? t('friends.add.sending') : t('friends.add.send') }}
          </SdBtn>
        </div>
      </div>
    </SdCard>

    <!-- Incoming requests -->
    <div v-if="requests.length" class="friends-section">
      <SdSectionLabel>{{ t('friends.requests.label', { count: requests.length }) }}</SdSectionLabel>
      <p v-if="requestsActionError" class="friends-error">{{ requestsActionError }}</p>
      <SdList>
        <SdListRow
          v-for="r in requests"
          :key="r.id"
          :title="r.fromName"
          :subtitle="r.fromEmail"
        >
          <template #icon><SdAvatar :name="r.fromName" :size="38" /></template>
          <template #trailing>
            <div class="request-actions">
              <button
                class="icon-action icon-action--accept"
                :disabled="requestActionId === r.id"
                @click="handleAccept(r.id)"
              >
                <Loader2 v-if="requestActionId === r.id" :size="16" class="spin-icon" />
                <Check v-else :size="16" />
              </button>
              <button
                class="icon-action icon-action--decline"
                :disabled="requestActionId === r.id"
                @click="handleDecline(r.id)"
              >
                <Loader2 v-if="requestActionId === r.id" :size="16" class="spin-icon" />
                <X v-else :size="16" />
              </button>
            </div>
          </template>
        </SdListRow>
      </SdList>
    </div>

    <!-- Friends list -->
    <div class="friends-section">
      <SdSectionLabel>{{ t('friends.list.label', { count: friends.length }) }}</SdSectionLabel>
      <p v-if="friendsError" class="friends-error">{{ friendsError }}</p>
      <div v-else-if="friends.length === 0" class="friends-empty">
        <SdCard padding="28px 20px">
          <p class="friends-empty__title">{{ t('friends.list.emptyTitle') }}</p>
          <p class="friends-empty__body">{{ t('friends.list.emptyBody') }}</p>
        </SdCard>
      </div>
      <SdList v-else>
        <SdListRow
          v-for="f in friends"
          :key="f.friendshipId"
          :title="f.name"
          :subtitle="f.email"
        >
          <template #icon><SdAvatar :name="f.name" :size="38" /></template>
          <template #trailing>
            <button class="icon-action icon-action--decline" @click="confirmRemove(f)">
              <UserMinus :size="16" />
            </button>
          </template>
        </SdListRow>
      </SdList>
    </div>

    <div style="height: 100px;" />

    <!-- Remove friend confirm sheet -->
    <SdBottomSheet v-model="removeSheet" :title="t('friends.remove.title')">
      <div class="remove-stack">
        <p class="remove-body">
          {{ t('friends.remove.body', { name: removeTarget?.name ?? '' }) }}
        </p>
        <p v-if="removeError" class="add-error">{{ removeError }}</p>
        <div class="remove-actions">
          <SdBtn variant="ghost" size="md" style="flex:1;" @click="removeSheet = false">
            {{ t('common.cancel') }}
          </SdBtn>
          <SdBtn
            variant="primary"
            size="md"
            style="flex:1;"
            class="danger-confirm-btn"
            :disabled="removeLoading"
            @click="handleRemove"
          >
            {{ removeLoading ? t('friends.remove.removing') : t('friends.remove.confirm') }}
          </SdBtn>
        </div>
      </div>
    </SdBottomSheet>
  </AppLayout>
</template>

<style scoped>
.add-card { margin-bottom: 24px; }

.card-row {
  display: flex;
  align-items: center;
  gap: 8px;
}
.card-row--mb { margin-bottom: 10px; }

.card-label {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  flex: 1;
}

.add-status {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-fg3);
  margin: 10px 2px 0;
}
.add-success {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-azure);
  margin: 10px 2px 0;
}
.add-error {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-danger);
  margin: 10px 2px 0;
}

.search-results {
  display: flex;
  flex-direction: column;
  margin-top: 10px;
}
.search-result-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 0;
  border-top: 1px solid rgba(16, 42, 87, .06);
}
.search-result-row:first-child { border-top: none; }
.search-result-info { flex: 1; min-width: 0; }
.search-result-name {
  font-family: var(--sd-font-body);
  font-weight: 600;
  font-size: 14px;
  color: var(--sd-fg1);
  line-height: 1.2;
}
.search-result-email {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  margin-top: 2px;
}

.friends-section { margin-bottom: 24px; }

.friends-error {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-danger);
  margin: 0 0 10px;
}

.friends-empty__title {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 15px;
  color: var(--sd-fg1);
  margin: 0 0 4px;
  text-align: center;
}
.friends-empty__body {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-fg3);
  line-height: 1.5;
  margin: 0;
  text-align: center;
}

.request-actions {
  display: flex;
  gap: 8px;
}

.icon-action {
  width: 32px;
  height: 32px;
  border-radius: 999px;
  border: 1px solid rgba(16, 42, 87, .14);
  background: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  flex: none;
  color: var(--sd-fg3);
  transition: background var(--sd-dur-fast) var(--sd-ease-out);
}
.icon-action:disabled { opacity: 0.5; cursor: not-allowed; }
.icon-action:not(:disabled):hover { background: rgba(16, 42, 87, .06); }

.icon-action--accept {
  color: var(--sd-azure);
  border-color: rgba(111, 147, 181, .4);
}
.icon-action--decline {
  color: var(--sd-danger);
  border-color: rgba(192, 88, 78, .3);
}

.remove-stack {
  display: flex;
  flex-direction: column;
  gap: 14px;
  padding-top: 4px;
}
.remove-body {
  font-family: var(--sd-font-body);
  font-size: 14px;
  color: var(--sd-fg2);
  line-height: 1.4;
  margin: 0;
}
.remove-actions {
  display: flex;
  gap: 10px;
}
.danger-confirm-btn {
  background: var(--sd-danger) !important;
  color: #fff !important;
}
.danger-confirm-btn:not(:disabled):hover { opacity: 0.9; }

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
.spin-icon {
  animation: spin 0.7s linear infinite;
}
</style>
