<script setup>
import { LifeBuoy, MessageCircle, QrCode, BatteryLow, Users, Shield, FileText, Lock, Info, ChevronRight, ExternalLink } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import { SdBtn, SdList, SdListRow, SdSectionLabel, SdCard } from '@/components/ui'
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
        <SdBtn variant="primary" size="md" block>
          <template #icon-left><MessageCircle :size="16" /></template>
          Chat with us
        </SdBtn>
      </SdCard>

      <!-- FAQ -->
      <SdSectionLabel>Top questions</SdSectionLabel>
      <SdList>
        <SdListRow title="QR code won't scan" subtitle="Troubleshoot the pairing flow.">
          <template #icon><div class="list-icon"><QrCode :size="16" :stroke-width="1.75" /></div></template>
          <template #trailing><ChevronRight :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
        <SdListRow title="How long does the disc battery last?" subtitle="~60 hours of throws · USB-C charging.">
          <template #icon><div class="list-icon"><BatteryLow :size="16" :stroke-width="1.75" /></div></template>
          <template #trailing><ChevronRight :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
        <SdListRow title="Can I revoke an invite?" subtitle="Yes — anytime, from the People tab.">
          <template #icon><div class="list-icon"><Users :size="16" :stroke-width="1.75" /></div></template>
          <template #trailing><ChevronRight :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
        <SdListRow title="Where is my data stored?" subtitle="Frankfurt · EU. End-to-end encrypted.">
          <template #icon><div class="list-icon"><Shield :size="16" :stroke-width="1.75" /></div></template>
          <template #trailing><ChevronRight :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
      </SdList>

      <!-- About -->
      <SdSectionLabel>About</SdSectionLabel>
      <SdList>
        <SdListRow title="Terms of service">
          <template #icon><FileText :size="18" style="color: var(--sd-ink); flex: none;" /></template>
          <template #trailing><ExternalLink :size="16" style="color: var(--sd-fg3);" /></template>
        </SdListRow>
        <SdListRow title="Privacy policy">
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
