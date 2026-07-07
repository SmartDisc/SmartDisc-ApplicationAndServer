<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdStatTile from '@/components/ui/SdStatTile.vue'
import { SdBtn } from '@/components/ui'
import { MoreHorizontal, Star, Share2 } from 'lucide-vue-next'
import { useDiscs } from '@/composables/useDiscs'

const route = useRoute()
const { getDisc } = useDiscs()
const disc  = computed(() => getDisc(route.params.id))
const throw_ = computed(() =>
  disc.value?.throws_list.find(t => String(t.id) === String(route.params.throwId)) ?? null
)
</script>

<template>
  <div class="throw-screen">
    <div class="throw-bg">
      <div class="throw-bg__b1" />
      <div class="throw-bg__b2" />
    </div>

    <div class="throw-content">
      <SdAppBar back>
        <template #action>
          <button class="dark-icon-btn">
            <MoreHorizontal :size="17" :stroke-width="1.75" />
          </button>
        </template>
      </SdAppBar>

      <div class="throw-header">
        <div class="throw-eyebrow">Throw · {{ disc?.name }}</div>
        <h1 class="throw-title">{{ throw_?.name ?? 'Throw' }}</h1>
        <p class="throw-time">{{ throw_?.time?.split('·')[0] ?? 'Today' }}</p>
      </div>

      <!-- Primary stats -->
      <div class="stat-row">
        <SdStatTile dark :v="throw_?.rpm ?? 1320" k="RPM" />
        <SdStatTile dark v="27" u="km/h" k="Speed" />
        <SdStatTile dark v="3.8" u="m" k="Height" />
      </div>

      <!-- Flight path visualization -->
      <div class="flight">
        <svg width="100%" height="100%" viewBox="0 0 340 220" preserveAspectRatio="none" class="flight__svg">
          <defs>
            <linearGradient id="trail" x1="0" x2="1">
              <stop offset="0%" stop-color="#dec38c" />
              <stop offset="100%" stop-color="#6f93b5" />
            </linearGradient>
          </defs>
          <path d="M20 190 C90 30, 220 30, 320 130" stroke="url(#trail)" stroke-width="3" fill="none" stroke-linecap="round" />
          <path d="M20 190 C90 30, 220 30, 320 130" stroke="rgba(255,255,255,.15)" stroke-width="14" fill="none" stroke-linecap="round" opacity=".4" />
          <circle cx="20" cy="190" r="6" fill="#dec38c" />
          <circle cx="320" cy="130" r="6" fill="#fff" />
        </svg>
        <span class="flight__lbl flight__lbl--start">Release</span>
        <span class="flight__lbl flight__lbl--end">Catch · 41 m</span>
      </div>

      <!-- Secondary stats -->
      <div class="stat-row">
        <SdStatTile dark v="3.1" u="s" k="Hang time" />
        <SdStatTile dark v="18°" k="Launch" />
        <SdStatTile dark v="41" u="m" k="Distance" />
      </div>

      <!-- Actions -->
      <div class="throw-actions">
        <SdBtn variant="dark-glass" size="md">
          <template #icon-left><Star :size="16" :stroke-width="1.75" /></template>
          Favorite
        </SdBtn>
        <SdBtn variant="gold" size="md" block>
          <template #icon-left><Share2 :size="16" :stroke-width="1.75" /></template>
          Share
        </SdBtn>
      </div>
    </div>
  </div>
</template>

<style scoped>
.throw-screen {
  min-height: 100dvh;
  background: var(--sd-ink-900);
  position: relative;
  overflow: hidden;
  display: flex;
  justify-content: center;
}

.throw-bg {
  position: fixed;
  inset: 0;
  pointer-events: none;
}
.throw-bg__b1 {
  position: absolute;
  width: 380px;
  height: 380px;
  left: -120px;
  top: -120px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(111, 147, 181, .30), transparent 65%);
}
.throw-bg__b2 {
  position: absolute;
  width: 360px;
  height: 360px;
  right: -140px;
  top: 28%;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(222, 195, 140, .28), transparent 60%);
}

.throw-content {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 390px;
  display: flex;
  flex-direction: column;
  padding: 0 22px 32px;
}

/* Override AppBar styles for dark background */
.throw-content :deep(.appbar__back) {
  background: rgba(255, 255, 255, .12);
  border-color: rgba(255, 255, 255, .18);
  color: #fff;
}
.throw-content :deep(.appbar__title) {
  color: var(--sd-fg-on-dark);
}

.dark-icon-btn {
  width: 38px;
  height: 38px;
  border-radius: 999px;
  background: rgba(255, 255, 255, .12);
  border: 1px solid rgba(255, 255, 255, .18);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  cursor: pointer;
}

.throw-eyebrow {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 11px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--sd-gold-300);
  margin: 0 0 8px;
}
.throw-title {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 26px;
  letter-spacing: -0.01em;
  color: #fff;
  margin: 0 0 4px;
  line-height: 1.05;
}
.throw-time {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-fg2-on-dark);
  margin: 0 0 14px;
}

.stat-row {
  display: flex;
  gap: 10px;
  margin-bottom: 14px;
}

.flight {
  flex: 1;
  position: relative;
  min-height: 160px;
  border-radius: var(--sd-r-md);
  border: 1px solid rgba(255, 255, 255, .08);
  background:
    repeating-linear-gradient(90deg, rgba(255, 255, 255, .05) 0 1px, transparent 1px 32px),
    repeating-linear-gradient(0deg, rgba(255, 255, 255, .05) 0 1px, transparent 1px 26px),
    rgba(255, 255, 255, .04);
  overflow: hidden;
  margin-bottom: 14px;
}
.flight__svg { position: absolute; inset: 0; }

.flight__lbl {
  position: absolute;
  font-family: var(--sd-font-display);
  font-size: 10px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  background: rgba(255, 255, 255, .85);
  color: var(--sd-ink);
  padding: 2px 6px;
  border-radius: 6px;
}
.flight__lbl--start { left: 12px; bottom: 12px; background: rgba(222, 195, 140, .95); }
.flight__lbl--end   { right: 12px; top: 12px; }

.throw-actions {
  display: flex;
  gap: 8px;
  margin-top: 4px;
}
</style>
