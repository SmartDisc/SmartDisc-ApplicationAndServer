<script setup>
import { useRoute } from 'vue-router'
import { Disc3, Users, Settings } from 'lucide-vue-next'

const route = useRoute()

const tabs = [
  { key: 'discs',    label: 'My Discs',  icon: Disc3,    to: '/discs' },
  { key: 'shared',   label: 'Shared',    icon: Users,    to: '/shared' },
  { key: 'settings', label: 'Settings',  icon: Settings, to: '/settings' },
]

function isActive(tab) {
  return route.path.startsWith(tab.to)
}
</script>

<template>
  <nav class="tabbar">
    <RouterLink
      v-for="tab in tabs"
      :key="tab.key"
      :to="tab.to"
      :class="['tabbar__item', { 'tabbar__item--on': isActive(tab) }]"
    >
      <component :is="tab.icon" :size="17" :stroke-width="isActive(tab) ? 2 : 1.75" />
      <span>{{ tab.label }}</span>
    </RouterLink>
  </nav>
</template>

<style scoped>
.tabbar {
  margin: 0 18px 26px;
  border-radius: var(--sd-r-pill);
  background: var(--sd-glass-light-bg);
  -webkit-backdrop-filter: var(--sd-glass-blur);
          backdrop-filter: var(--sd-glass-blur);
  border: 1px solid var(--sd-glass-light-border);
  box-shadow: var(--sd-shadow-glass);
  padding: 6px;
  display: flex;
  gap: 4px;
  flex: none;
}

.tabbar__item {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  border-radius: var(--sd-r-pill);
  font-family: var(--sd-font-display);
  font-weight: 500;
  font-size: 13px;
  color: var(--sd-fg2);
  padding: 11px 8px;
  text-decoration: none;
}

.tabbar__item--on {
  background: var(--sd-ink);
  color: #fff;
  box-shadow: var(--sd-shadow-sm);
}
</style>
