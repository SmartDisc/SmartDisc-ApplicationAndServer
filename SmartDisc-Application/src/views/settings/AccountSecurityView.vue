<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Camera, User, Mail, KeyRound, Smartphone, Monitor, AlertTriangle, LogOut, ChevronRight, X, Eye, EyeOff } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdAvatar from '@/components/ui/SdAvatar.vue'
import { SdBtn, SdField, SdChip, SdToggle, SdBottomSheet, SdList, SdListRow, SdSectionLabel } from '@/components/ui'
import SdPasswordHint from '@/components/auth/SdPasswordHint.vue'
import { useAuth } from '@/composables/useAuth'
import { sanitizeName, sanitizeEmail, sanitizePassword } from '@/utils/sanitize'
import { required, password as validatePassword } from '@/utils/validate'

const router = useRouter()
const { user } = useAuth()

const name   = ref(user?.value?.name  ?? 'Alex Rivera')
const email  = ref(user?.value?.email ?? 'alex@smartdisc.io')
const faceId = ref(true)

// ── Change password sheet ─────────────────────────────────────────────────
const passwordSheet  = ref(false)
const currentPw      = ref('')
const newPw          = ref('')
const confirmPw      = ref('')
const pwLoading      = ref(false)
const pwError        = ref('')

const pwSaveDisabled = computed(() =>
  !currentPw.value ||
  !!validatePassword(newPw.value) ||
  newPw.value !== confirmPw.value
)

function openPasswordSheet() {
  currentPw.value = ''
  newPw.value     = ''
  confirmPw.value = ''
  pwError.value   = ''
  passwordSheet.value = true
}

async function handlePasswordChange() {
  if (pwSaveDisabled.value) return
  if (newPw.value !== confirmPw.value) {
    pwError.value = 'Passwords do not match'
    return
  }
  pwLoading.value = true
  pwError.value   = ''
  await new Promise(r => setTimeout(r, 1000))
  pwLoading.value = false
  passwordSheet.value = false
}
</script>

<template>
  <AppLayout :tabs="false">
    <SdAppBar back title="Account &amp; security" />

    <div class="account-scroll">
      <!-- Avatar -->
      <div class="avatar-wrap">
        <div class="avatar-pos">
          <SdAvatar name="Alex Rivera" :size="72" :hue="210" />
          <div class="avatar-edit">
            <Camera :size="13" style="color: #fff;" />
          </div>
        </div>
        <span class="avatar-label">Change photo</span>
      </div>

      <!-- Profile fields -->
      <SdSectionLabel>Profile</SdSectionLabel>
      <div class="form-stack">
        <SdField v-model="name" label="Full name" :sanitize="sanitizeName" :maxlength="100">
          <template #icon><User :size="18" :stroke-width="1.75" /></template>
        </SdField>
        <SdField v-model="email" label="Email" type="email" :sanitize="sanitizeEmail" :maxlength="254">
          <template #icon><Mail :size="18" :stroke-width="1.75" /></template>
        </SdField>
      </div>

      <!-- Password -->
      <SdSectionLabel>Password</SdSectionLabel>
      <SdList>
        <SdListRow tappable title="Change password" subtitle="Last changed 3 months ago" @click="openPasswordSheet">
          <template #icon><KeyRound :size="18" style="color: var(--sd-ink);" :stroke-width="1.75" /></template>
          <template #trailing><ChevronRight :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
        <SdListRow title="Sign in with Face ID" subtitle="Unlock SmartDisc with biometrics">
          <template #icon><Smartphone :size="18" style="color: var(--sd-ink);" :stroke-width="1.75" /></template>
          <template #trailing><SdToggle v-model="faceId" /></template>
        </SdListRow>
      </SdList>

      <!-- Sessions -->
      <SdSectionLabel>Active sessions</SdSectionLabel>
      <SdList>
        <SdListRow title="iPhone 15 · this device" subtitle="Berlin · active now">
          <template #icon><Smartphone :size="18" style="color: var(--sd-ink);" :stroke-width="1.75" /></template>
          <template #trailing><SdChip tone="gold">Current</SdChip></template>
        </SdListRow>
        <SdListRow title="MacBook Pro · Safari" subtitle="Berlin · 2 days ago">
          <template #icon><Monitor :size="18" style="color: var(--sd-ink);" :stroke-width="1.75" /></template>
          <template #trailing>
            <button class="icon-x"><X :size="16" style="color: var(--sd-fg3);" /></button>
          </template>
        </SdListRow>
      </SdList>

      <SdBtn variant="ghost" size="md" block style="margin-top: 4px;">
        <template #icon-left><LogOut :size="16" /></template>
        Sign out everywhere else
      </SdBtn>

      <!-- Delete account -->
      <SdBtn variant="ghost" size="md" block class="danger-btn">
        <template #icon-left><AlertTriangle :size="16" /></template>
        Delete account
      </SdBtn>

      <div style="height: 40px;" />
    </div>

    <!-- Change Password sheet -->
    <SdBottomSheet v-model="passwordSheet" title="Change password">
      <div class="pw-stack">
        <SdField
          v-model="currentPw"
          label="Current password"
          type="password"
          :sanitize="sanitizePassword"
          :maxlength="128"
        />
        <SdField
          v-model="newPw"
          label="New password"
          type="password"
          :sanitize="sanitizePassword"
          :maxlength="128"
        />
        <SdPasswordHint :value="newPw" />
        <SdField
          v-model="confirmPw"
          label="Repeat new password"
          type="password"
          :sanitize="sanitizePassword"
          :maxlength="128"
          :error="confirmPw && newPw !== confirmPw ? 'Passwords do not match' : ''"
        />
        <p v-if="pwError" class="pw-error">{{ pwError }}</p>
        <div class="pw-actions">
          <SdBtn variant="ghost" size="md" style="flex:1;" @click="passwordSheet = false">Cancel</SdBtn>
          <SdBtn
            variant="primary"
            size="md"
            style="flex:1;"
            :disabled="pwSaveDisabled || pwLoading"
            @click="handlePasswordChange"
          >
            {{ pwLoading ? 'Saving…' : 'Update password' }}
          </SdBtn>
        </div>
      </div>
    </SdBottomSheet>
  </AppLayout>
</template>

<style scoped>
.account-scroll {
  display: flex;
  flex-direction: column;
  gap: 14px;
  overflow-y: auto;
}

.avatar-wrap {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 4px 0 8px;
  gap: 8px;
}
.avatar-pos { position: relative; }
.avatar-edit {
  position: absolute;
  right: -4px;
  bottom: -4px;
  width: 28px;
  height: 28px;
  border-radius: 999px;
  background: var(--sd-ink);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--sd-shadow-sm);
}
.avatar-label {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-azure);
  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.form-stack { display: flex; flex-direction: column; gap: 12px; }

.icon-x {
  background: none;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.danger-btn {
  color: var(--sd-danger) !important;
  border-color: rgba(192, 88, 78, .30) !important;
}
.danger-btn:hover { background: rgba(192, 88, 78, .04) !important; }

/* Password sheet */
.pw-stack { display: flex; flex-direction: column; gap: 14px; padding-top: 4px; }

.pw-error {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-danger);
  margin: 0;
}

.pw-actions {
  display: flex;
  gap: 10px;
  margin-top: 4px;
}
</style>
