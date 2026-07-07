<script setup>
import { ref } from 'vue'
import { Bell, Trophy, BarChart3, Zap, UserPlus, Star, Smartphone, Mail } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import { SdToggle, SdList, SdListRow, SdSectionLabel } from '@/components/ui'

const personalBests = ref(true)
const weeklyRecap   = ref(true)
const lowBattery    = ref(true)
const newPerson     = ref(true)
const throwFaved    = ref(false)
const pushEnabled   = ref(true)
const emailEnabled  = ref(false)
</script>

<template>
  <AppLayout :tabs="false">
    <SdAppBar back title="Notifications" />

    <div class="notif-scroll">
      <!-- Banner -->
      <div class="info-banner">
        <Bell :size="16" style="flex: none;" />
        <div>
          Notifications are <b>on</b>. Daily quiet hours: 22:00 – 07:00.
        </div>
      </div>

      <!-- Throws -->
      <SdSectionLabel>Throws</SdSectionLabel>
      <SdList>
        <SdListRow title="Personal bests" subtitle="When a throw beats your record.">
          <template #icon><Trophy :size="18" style="color: var(--sd-gold-500); flex: none;" /></template>
          <template #trailing><SdToggle v-model="personalBests" /></template>
        </SdListRow>
        <SdListRow title="Weekly recap" subtitle="Sundays · summary of throws &amp; trends.">
          <template #icon><BarChart3 :size="18" style="color: var(--sd-azure); flex: none;" /></template>
          <template #trailing><SdToggle v-model="weeklyRecap" /></template>
        </SdListRow>
        <SdListRow title="Low battery" subtitle="When a disc drops below 15%.">
          <template #icon><Zap :size="18" style="color: var(--sd-ink); flex: none;" /></template>
          <template #trailing><SdToggle v-model="lowBattery" /></template>
        </SdListRow>
      </SdList>

      <!-- Sharing -->
      <SdSectionLabel>Sharing</SdSectionLabel>
      <SdList>
        <SdListRow title="New person joins" subtitle="When an invitee accepts.">
          <template #icon><UserPlus :size="18" style="color: var(--sd-azure); flex: none;" /></template>
          <template #trailing><SdToggle v-model="newPerson" /></template>
        </SdListRow>
        <SdListRow title="Throws favorited by others" subtitle="When someone stars your throw.">
          <template #icon><Star :size="18" style="color: var(--sd-gold-500); flex: none;" /></template>
          <template #trailing><SdToggle v-model="throwFaved" /></template>
        </SdListRow>
      </SdList>

      <!-- Delivery -->
      <SdSectionLabel>Delivery</SdSectionLabel>
      <SdList>
        <SdListRow title="Push">
          <template #icon><Smartphone :size="18" style="color: var(--sd-ink); flex: none;" /></template>
          <template #trailing><SdToggle v-model="pushEnabled" /></template>
        </SdListRow>
        <SdListRow title="Email">
          <template #icon><Mail :size="18" style="color: var(--sd-ink); flex: none;" /></template>
          <template #trailing><SdToggle v-model="emailEnabled" /></template>
        </SdListRow>
      </SdList>

      <div style="height: 40px;" />
    </div>
  </AppLayout>
</template>

<style scoped>
.notif-scroll { display: flex; flex-direction: column; gap: 14px; }

.info-banner {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  border-radius: var(--sd-r-md);
  background: rgba(111, 147, 181, .16);
  color: var(--sd-ink-700);
  font-family: var(--sd-font-display);
  font-size: 13px;
  font-weight: 500;
}

</style>
