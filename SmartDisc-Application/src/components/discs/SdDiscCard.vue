<script setup>
import { Activity, Eye, Star } from 'lucide-vue-next'
import SdChip from '@/components/ui/SdChip.vue'

defineProps({
  name:       { type: String, required: true },
  uuid:       { type: String, required: true },
  throws:     { type: [Number, String], default: 0 },
  longest:    { type: [Number, String], default: 0 },
  players:    { type: [Number, String], default: 0 },
  fav:        { type: Boolean, default: false },
  shared:     { type: Boolean, default: false },
  lastActive: { type: String, default: null },
})
</script>

<template>
  <div class="disc-card">
    <div class="disc-card__top">
      <div class="disc-card__info">
        <div class="disc-card__name">{{ name }}</div>
        <div class="disc-card__uuid">{{ uuid }}</div>
        <div v-if="lastActive" class="disc-card__activity">
          <Activity :size="11" />
          <span>{{ lastActive }}</span>
        </div>
      </div>
      <SdChip v-if="shared" tone="read">
        <template #icon><Eye :size="12" /></template>
        Read
      </SdChip>
      <Star
        v-else
        :size="22"
        :stroke-width="2"
        :style="{ color: fav ? 'var(--sd-gold-500)' : 'var(--sd-mist)' }"
      />
    </div>
    <div class="disc-card__stats">
      <div class="disc-card__stat">
        <div class="disc-card__stat-v">{{ throws }}</div>
        <div class="disc-card__stat-k">THROWS</div>
      </div>
      <div class="disc-card__stat">
        <div class="disc-card__stat-v">{{ longest }}<span class="disc-card__stat-u">m</span></div>
        <div class="disc-card__stat-k">LONGEST</div>
      </div>
      <div class="disc-card__stat">
        <div class="disc-card__stat-v">{{ players }}</div>
        <div class="disc-card__stat-k">PLAYERS</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.disc-card {
  background: var(--sd-glass-light-bg);
  border: 1px solid var(--sd-glass-light-border);
  -webkit-backdrop-filter: var(--sd-glass-blur);
          backdrop-filter: var(--sd-glass-blur);
  border-radius: var(--sd-r-lg);
  box-shadow: var(--sd-shadow-glass);
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 14px;
  cursor: pointer;
  transition: transform var(--sd-dur-fast) var(--sd-ease-out);
}
.disc-card:active { transform: scale(0.985); }

.disc-card__top {
  display: flex;
  align-items: center;
  gap: 13px;
}

.disc-card__mark {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  flex: none;
  background: linear-gradient(140deg, #1d3d72, #0a1c3d);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--sd-shadow-sm);
}
.disc-card__mark img {
  width: 36px;
  height: 36px;
  object-fit: contain;
}

.disc-card__info { flex: 1; min-width: 0; }

.disc-card__name {
  font-family: var(--sd-font-body);
  font-weight: 600;
  font-size: 17px;
  color: var(--sd-fg1);
  line-height: 1.1;
}

.disc-card__uuid {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.02em;
  margin-top: 4px;
}

.disc-card__activity {
  display: flex;
  align-items: center;
  gap: 5px;
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  margin-top: 5px;
}
.disc-card__activity span { color: var(--sd-success); font-weight: 600; }

.disc-card__stats {
  display: flex;
  gap: 18px;
  border-top: 1px dashed rgba(16, 42, 87, .08);
  padding-top: 12px;
}

.disc-card__stat { display: flex; flex-direction: column; gap: 2px; }

.disc-card__stat-v {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 18px;
  color: var(--sd-ink);
  line-height: 1;
  display: flex;
  align-items: baseline;
  gap: 2px;
}

.disc-card__stat-u {
  font-size: 12px;
  font-weight: 500;
  color: var(--sd-fg3);
}

.disc-card__stat-k {
  font-family: var(--sd-font-display);
  font-size: 10.5px;
  color: var(--sd-fg3);
  letter-spacing: 0.04em;
  text-transform: uppercase;
}
</style>
