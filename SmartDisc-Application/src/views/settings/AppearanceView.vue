<script setup>
import { ref, computed } from 'vue'
import { Ruler, Gauge, Languages, Sparkles, Wand2, CheckCircle2, ChevronRight } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import { SdToggle, SdList, SdListRow, SdSectionLabel, SdOptionSheet } from '@/components/ui'
import { usePreferences } from '@/composables/usePreferences'

const {
  language, theme, distanceUnit, speedUnit,
  saveLanguage, saveTheme, saveDistanceUnit, saveSpeedUnit,
} = usePreferences()

const glassEffects = ref(true)
const reduceMotion = ref(false)

// Sheet open states
const distanceSheet = ref(false)
const speedSheet    = ref(false)
const languageSheet = ref(false)

// ── Distance options ──────────────────────────────────────────────────────
const distanceOptions = [
  { value: 'm',  label: 'Kilometers / Meters',  badge: 'km · m' },
  { value: 'ft', label: 'Miles / Feet',          badge: 'mi · ft' },
]
const distanceLabel = computed(() =>
  distanceOptions.find(o => o.value === distanceUnit.value)?.badge ?? 'm'
)

// ── Speed options ─────────────────────────────────────────────────────────
const speedOptions = [
  { value: 'km/h', label: 'Kilometers per hour', badge: 'km/h' },
  { value: 'mph',  label: 'Miles per hour',       badge: 'mph'  },
]
const speedLabel = computed(() =>
  speedOptions.find(o => o.value === speedUnit.value)?.badge ?? 'km/h'
)

// ── Language options ──────────────────────────────────────────────────────
const languageOptions = [
  { value: 'en', label: 'English',    badge: 'EN' },
  { value: 'de', label: 'Deutsch',    badge: 'DE' },
  { value: 'fr', label: 'Français',   badge: 'FR' },
  { value: 'es', label: 'Español',    badge: 'ES' },
]
const languageLabel = computed(() =>
  languageOptions.find(o => o.value === language.value)?.badge ?? 'EN'
)

// ── Theme options ─────────────────────────────────────────────────────────
const themeOptions = [
  { value: 'light',  label: 'Light'  },
  { value: 'dark',   label: 'Dark'   },
  { value: 'system', label: 'System' },
]
const activeTheme = computed(() => theme.value ?? 'light')
</script>

<template>
  <AppLayout :tabs="false">
    <SdAppBar back title="Appearance" />

    <div class="appearance-scroll">
      <!-- Theme -->
      <SdSectionLabel>Theme</SdSectionLabel>
      <div class="theme-row">
        <button
          v-for="opt in themeOptions"
          :key="opt.value"
          class="theme-card"
          :class="{ 'theme-card--selected': activeTheme === opt.value }"
          @click="saveTheme(opt.value)"
        >
          <div class="theme-preview" :class="`theme-preview--${opt.value}`" />
          <div class="theme-name">{{ opt.label }}</div>
          <CheckCircle2
            v-if="activeTheme === opt.value"
            :size="14"
            style="color: var(--sd-ink); margin-top: 4px;"
          />
        </button>
      </div>

      <!-- Units -->
      <SdSectionLabel>Units</SdSectionLabel>
      <SdList>
        <SdListRow
          tappable
          title="Distance &amp; height"
          :subtitle="`Currently ${distanceUnit === 'm' ? 'metric' : 'imperial'}`"
          @click="distanceSheet = true"
        >
          <template #icon><Ruler :size="18" style="color: var(--sd-ink);" :stroke-width="1.75" /></template>
          <template #trailing>
            <span class="pref-value">{{ distanceLabel }}</span>
            <ChevronRight :size="16" style="color: var(--sd-fg3);" />
          </template>
        </SdListRow>
        <SdListRow tappable title="Speed" subtitle="km/h · mph" @click="speedSheet = true">
          <template #icon><Gauge :size="18" style="color: var(--sd-ink);" :stroke-width="1.75" /></template>
          <template #trailing>
            <span class="pref-value">{{ speedLabel }}</span>
            <ChevronRight :size="16" style="color: var(--sd-fg3);" />
          </template>
        </SdListRow>
        <SdListRow
          tappable
          title="Language"
          :subtitle="languageOptions.find(o => o.value === language)?.label ?? 'English'"
          @click="languageSheet = true"
        >
          <template #icon><Languages :size="18" style="color: var(--sd-ink);" :stroke-width="1.75" /></template>
          <template #trailing>
            <span class="pref-value">{{ languageLabel }}</span>
            <ChevronRight :size="16" style="color: var(--sd-fg3);" />
          </template>
        </SdListRow>
      </SdList>

      <!-- Motion -->
      <SdSectionLabel>Motion</SdSectionLabel>
      <SdList>
        <SdListRow title="Liquid Glass effects" subtitle="Translucent panels &amp; subtle blur.">
          <template #icon><Sparkles :size="18" style="color: var(--sd-gold-500);" /></template>
          <template #trailing><SdToggle v-model="glassEffects" /></template>
        </SdListRow>
        <SdListRow title="Reduce motion" subtitle="Calmer transitions and parallax.">
          <template #icon><Wand2 :size="18" style="color: var(--sd-ink);" /></template>
          <template #trailing><SdToggle v-model="reduceMotion" /></template>
        </SdListRow>
      </SdList>

      <div style="height: 40px;" />
    </div>

    <SdOptionSheet
      v-model="distanceSheet"
      title="Distance &amp; Height"
      :options="distanceOptions"
      :selected="distanceUnit"
      @select="saveDistanceUnit"
    />
    <SdOptionSheet
      v-model="speedSheet"
      title="Speed"
      :options="speedOptions"
      :selected="speedUnit"
      @select="saveSpeedUnit"
    />
    <SdOptionSheet
      v-model="languageSheet"
      title="Language"
      :options="languageOptions"
      :selected="language"
      @select="saveLanguage"
    />
  </AppLayout>
</template>

<style scoped>
.appearance-scroll { display: flex; flex-direction: column; gap: 14px; }

/* Theme cards */
.theme-row { display: flex; gap: 10px; }

.theme-card {
  flex: 1;
  background: var(--sd-glass-light-bg);
  border: 1px solid var(--sd-glass-light-border);
  -webkit-backdrop-filter: var(--sd-glass-blur);
          backdrop-filter: var(--sd-glass-blur);
  border-radius: var(--sd-r-lg);
  box-shadow: var(--sd-shadow-glass);
  padding: 12px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;
  transition: transform var(--sd-dur-fast) var(--sd-ease-out),
              box-shadow var(--sd-dur-fast) var(--sd-ease-out);
}
.theme-card:active { transform: scale(0.97); }
.theme-card--selected { border: 2px solid var(--sd-ink); }

.theme-preview {
  height: 56px;
  border-radius: 12px;
  margin-bottom: 8px;
  width: 100%;
}
.theme-preview--light  { background: linear-gradient(140deg, #f2eae2, #dec38c); border: 1px solid rgba(0,0,0,.06); }
.theme-preview--dark   { background: linear-gradient(140deg, #0a1c3d, #1d3d72); }
.theme-preview--system { background: linear-gradient(140deg, #f2eae2 50%, #0a1c3d 50%); }

.theme-name {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 13px;
  color: var(--sd-ink);
}

.pref-value {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 13px;
  color: var(--sd-ink);
}
</style>
