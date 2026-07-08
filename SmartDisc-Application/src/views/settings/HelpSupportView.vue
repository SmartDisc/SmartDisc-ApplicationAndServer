<script setup>
import { LifeBuoy, MessageCircle, FileText, Lock, Info, ExternalLink } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import { SdBtn, SdList, SdListRow, SdSectionLabel, SdCard } from '@/components/ui'

function openWebsite() {
  window.open('https://smartdisc.at', '_blank', 'noopener')
}
</script>

<template>
  <AppLayout :tabs="false">
    <SdAppBar back title="Help &amp; support" />

    <div class="help-scroll">
      <!-- Live support card -->
      <SdCard class="support-card" :padding="18">
        <div class="support-header">
          <div class="list-icon list-icon--gold">
            <LifeBuoy :size="18" :stroke-width="1.75" />
          </div>
          <div>
            <div class="support-title">Live support</div>
            <div class="support-meta">Average reply 8 min · 09:00 – 19:00 CET</div>
          </div>
        </div>
        <SdBtn variant="primary" size="md" block @click="openWebsite">
          <template #icon-left><MessageCircle :size="16" /></template>
          Chat with us
        </SdBtn>
      </SdCard>

      <!-- About -->
      <SdSectionLabel>About</SdSectionLabel>
      <SdList>
        <SdListRow tappable title="Terms of service" @click="openWebsite">
          <template #icon><FileText :size="18" style="color: var(--sd-ink); flex: none;" /></template>
          <template #trailing><ExternalLink :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
        <SdListRow tappable title="Privacy policy" @click="openWebsite">
          <template #icon><Lock :size="18" style="color: var(--sd-ink); flex: none;" /></template>
          <template #trailing><ExternalLink :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
        <SdListRow title="App version" subtitle="SmartDisc 2.4.1 (build 481)">
          <template #icon><Info :size="18" style="color: var(--sd-ink); flex: none;" /></template>
        </SdListRow>
      </SdList>

      <div style="height: 40px;" />
    </div>
  </AppLayout>
</template>

<style scoped>
.help-scroll { display: flex; flex-direction: column; gap: 14px; }

.support-card {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.support-header {
  display: flex;
  gap: 12px;
  align-items: center;
}
.support-title {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 15px;
  color: var(--sd-fg1);
}
.support-meta {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.02em;
  margin-top: 2px;
}

.list-icon {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  background: linear-gradient(140deg, #1d3d72, #0a1c3d);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex: none;
}
.list-icon--gold {
  width: 38px;
  height: 38px;
  background: var(--sd-gold-grad);
  color: #5a4416;
}
</style>
