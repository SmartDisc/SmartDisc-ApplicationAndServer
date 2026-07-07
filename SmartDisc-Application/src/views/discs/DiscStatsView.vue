<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { SdChip, SdCard } from '@/components/ui'
import SdStatTile from '@/components/ui/SdStatTile.vue'
import { TrendingUp, Star } from 'lucide-vue-next'
import { useDiscs } from '@/composables/useDiscs'

const route = useRoute()
const { getDisc } = useDiscs()
const disc = computed(() => getDisc(route.params.id))

const bars = [24, 32, 28, 40, 52, 36, 68]
const days = ['MON','TUE','WED','THU','FRI','SAT','SUN']
</script>

<template>
  <div class="stats-wrap">
    <!-- Speed chart -->
    <SdCard :padding="18">
      <div class="glass-card__header">
        <div class="stat-group">
          <div class="stat-label">Top speed · 7 days</div>
          <div class="stat-big">28<span class="stat-unit">km/h</span></div>
        </div>
        <SdChip tone="gold">
          <template #icon><TrendingUp :size="12" /></template>
          +12%
        </SdChip>
      </div>
      <div class="chart">
        <div
          v-for="(h, i) in bars"
          :key="i"
          class="chart__bar"
          :class="{ 'chart__bar--gold': i === bars.length - 1 }"
          :style="{ height: h + '%' }"
        />
      </div>
      <div class="chart__labels">
        <span v-for="d in days" :key="d">{{ d }}</span>
      </div>
    </SdCard>

    <!-- RPM + Height tiles -->
    <div class="tile-row">
      <SdCard flex :padding="16">
        <div class="stat-label">Avg spin</div>
        <div class="stat-big">1 182<span class="stat-unit">rpm</span></div>
        <div class="gauge"><div class="gauge__fill" style="width: 62%;" /></div>
        <div class="trend trend--up">↑ 8% vs last week</div>
      </SdCard>
      <SdCard flex :padding="16">
        <div class="stat-label">Max height</div>
        <div class="stat-big">5.4<span class="stat-unit">m</span></div>
        <div class="gauge"><div class="gauge__fill" style="width: 48%;" /></div>
        <div class="trend">= same as last</div>
      </SdCard>
    </div>

    <!-- Best throw -->
    <SdCard :padding="18">
      <div class="glass-card__header">
        <div class="stat-label">Best throw this week</div>
        <SdChip tone="gold">
          <template #icon><Star :size="12" /></template>
          41 m
        </SdChip>
      </div>
      <div class="best-throw">
        <div>
          <div class="throw-name">Long huck</div>
          <div class="throw-time">Today · 14:21 · 1 320 rpm</div>
        </div>
      </div>
    </SdCard>

    <div style="height: 100px;" />
  </div>
</template>

<style scoped>
.stats-wrap {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.glass-card__header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 10px;
}

.tile-row {
  display: flex;
  gap: 10px;
}

.stat-label {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.12em;
  text-transform: uppercase;
}
.stat-big {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 26px;
  color: var(--sd-ink);
  line-height: 1;
  display: flex;
  align-items: baseline;
  gap: 4px;
}
.stat-unit { font-size: 13px; color: var(--sd-fg3); font-weight: 500; }

.stat-group { display: flex; flex-direction: column; gap: 4px; }

.chart {
  display: flex;
  align-items: flex-end;
  gap: 5px;
  height: 60px;
  margin-top: 10px;
}
.chart__bar {
  flex: 1;
  border-radius: 4px 4px 1px 1px;
  background: linear-gradient(180deg, var(--sd-azure), var(--sd-ink-700));
  min-height: 4px;
}
.chart__bar--gold { background: var(--sd-gold-grad); }

.chart__labels {
  display: flex;
  justify-content: space-between;
  margin-top: 8px;
  font-family: var(--sd-font-display);
  font-size: 10px;
  color: var(--sd-fg3);
  letter-spacing: 0.08em;
}

.gauge {
  width: 100%;
  height: 6px;
  border-radius: 99px;
  background: rgba(16, 42, 87, .10);
  overflow: hidden;
  margin-top: 8px;
}
.gauge__fill {
  height: 100%;
  border-radius: 99px;
  background: linear-gradient(90deg, var(--sd-azure), var(--sd-gold-300));
}

.trend {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  margin-top: 6px;
}
.trend--up { color: var(--sd-success); }

.best-throw {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.throw-name {
  font-family: var(--sd-font-body);
  font-weight: 600;
  font-size: 14px;
  color: var(--sd-fg1);
  line-height: 1.15;
}
.throw-time {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  margin-top: 3px;
  letter-spacing: 0.02em;
}
</style>
