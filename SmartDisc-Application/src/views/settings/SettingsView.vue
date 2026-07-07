<script setup>
import { useRouter } from 'vue-router'
import { User, Bell, Palette, HelpCircle, LogOut } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdAvatar from '@/components/ui/SdAvatar.vue'
import { SdList, SdNavRow, SdSectionLabel, SdCard, SdPageHeader } from '@/components/ui'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { user, signOut } = useAuth()

async function handleSignOut() {
  await signOut()
  router.push('/welcome')
}
</script>

<template>
  <AppLayout>
    <SdAppBar title="" />

    <SdPageHeader class="settings-head" eyebrow="Your profile" title="Settings" />

    <!-- Profile card -->
    <SdCard class="profile-card">
      <SdAvatar :name="user?.name ?? 'User'" :size="52" :hue="210" />
      <div class="profile-info">
        <div class="profile-name">{{ user?.name ?? 'Alex Rivera' }}</div>
        <div class="profile-email">{{ user?.email ?? 'alex@smartdisc.io' }}</div>
      </div>
    </SdCard>

    <div class="settings-groups">
      <!-- Account group -->
      <div>
        <SdSectionLabel>Account</SdSectionLabel>
        <SdList>
          <SdNavRow to="/settings/account" label="Account &amp; security">
            <template #icon><User :size="16" :stroke-width="1.75" /></template>
          </SdNavRow>
          <SdNavRow to="/settings/notifications" label="Notifications" hint="On">
            <template #icon><Bell :size="16" :stroke-width="1.75" /></template>
          </SdNavRow>
        </SdList>
      </div>

      <!-- App group -->
      <div>
        <SdSectionLabel>App</SdSectionLabel>
        <SdList>
          <SdNavRow to="/settings/appearance" label="Appearance &amp; units" hint="Light · m, km/h">
            <template #icon><Palette :size="16" :stroke-width="1.75" /></template>
          </SdNavRow>
          <SdNavRow to="/settings/help" label="Help &amp; support">
            <template #icon><HelpCircle :size="16" :stroke-width="1.75" /></template>
          </SdNavRow>
          <SdNavRow label="Sign out" danger @click="handleSignOut">
            <template #icon><LogOut :size="16" :stroke-width="1.75" /></template>
          </SdNavRow>
        </SdList>
      </div>
    </div>

    <div style="height: 100px;" />
  </AppLayout>
</template>

<style scoped>
.settings-head { margin-bottom: 14px; }

.profile-card {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 14px;
}

.profile-info { flex: 1; min-width: 0; }
.profile-name {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 17px;
  color: var(--sd-fg1);
}
.profile-email {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.02em;
  margin-top: 3px;
}

.settings-groups { display: flex; flex-direction: column; gap: 14px; }
</style>
