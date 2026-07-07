<script setup>
import { Star } from 'lucide-vue-next'

defineProps({
  name:     { type: String, required: true },
  time:     { type: String, default: '' },
  rpm:      { type: [String, Number], default: '' },
  fav:      { type: Boolean, default: false },
  auto:     { type: Boolean, default: false },
  readonly: { type: Boolean, default: false },
})
</script>

<template>
  <div class="throw-row">
    <span class="throw-row__star">
      <Star
        v-if="!readonly || fav"
        :size="20"
        :stroke-width="2"
        :style="{ color: fav ? 'var(--sd-gold-500)' : 'var(--sd-mist)' }"
      />
      <span v-else style="width: 20px; display: inline-block;" />
    </span>
    <div class="throw-row__body">
      <div :class="['throw-row__name', { 'throw-row__name--auto': auto }]">{{ name }}</div>
      <div class="throw-row__time">{{ time }}</div>
    </div>
    <div class="throw-row__metric">
      {{ rpm }}<small>RPM</small>
    </div>
  </div>
</template>

<style scoped>
.throw-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  border-radius: var(--sd-r-md);
  background: rgba(255, 255, 255, .5);
  border: 1px solid rgba(255, 255, 255, .55);
  cursor: pointer;
  transition: background var(--sd-dur-fast) var(--sd-ease-out);
}
.throw-row:active { background: rgba(255, 255, 255, .75); }

.throw-row__star { width: 28px; flex: none; display: flex; }

.throw-row__body { flex: 1; min-width: 0; }

.throw-row__name {
  font-family: var(--sd-font-body);
  font-weight: 600;
  font-size: 15px;
  color: var(--sd-fg1);
  line-height: 1.15;
}
.throw-row__name--auto {
  font-style: italic;
  color: var(--sd-fg2);
  font-weight: 500;
}

.throw-row__time {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.02em;
  margin-top: 3px;
}

.throw-row__metric {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 13px;
  color: var(--sd-ink);
  line-height: 1;
  text-align: right;
  flex: none;
}
.throw-row__metric small {
  font-size: 10px;
  color: var(--sd-fg3);
  display: block;
  margin-top: 3px;
  font-weight: 500;
}
</style>
