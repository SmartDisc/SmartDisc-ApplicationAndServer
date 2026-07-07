<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Pencil, MoreHorizontal, List, BarChart3, Users, Star } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import { SdCard, SdIconBtn } from '@/components/ui'
import { useDiscs } from '@/composables/useDiscs'

const route  = useRoute()
const router = useRouter()
const { getDisc } = useDiscs()

const disc = computed(() => getDisc(route.params.id))

const tabs = [
  { key: 'throws', label: 'Throws', icon: List,     count: () => disc.value?.throws, to: suffix => `/discs/${route.params.id}/throws` },
  { key: 'stats',  label: 'Stats',  icon: BarChart3, to: suffix => `/discs/${route.params.id}/stats` },
  { key: 'people', label: 'People', icon: Users,     count: () => disc.value?.players, to: suffix => `/discs/${route.params.id}/people` },
]

const activeTab = computed(() => {
  if (route.path.endsWith('/stats'))  return 'stats'
  if (route.path.endsWith('/people')) return 'people'
  return 'throws'
})
</script>

<template>
  <AppLayout>
    <SdAppBar back :title="disc?.name ?? ''">
      <template #action>
        <SdIconBtn variant="glass" :to="`/discs/${route.params.id}/menu`">
          <MoreHorizontal :size="18" :stroke-width="1.75" />
        </SdIconBtn>
      </template>
    </SdAppBar>

    <!-- Hero card -->
    <SdCard v-if="disc" class="hero-card" :padding="14">
      <div class="hero-card__top">
        <div class="hero-card__mark">
          <img src="/images/SmartDisc_Mark.png" alt="" />
        </div>
        <div class="hero-card__info">
          <div class="hero-card__name-row">
            <span class="hero-card__name">{{ disc.name }}</span>
            <Pencil :size="14" :stroke-width="1.75" style="color: var(--sd-fg3);" />
          </div>
          <div class="hero-card__uuid">{{ disc.uuid }}</div>
        </div>
        <Star
          :size="22"
          :stroke-width="2"
          :style="{ color: disc.fav ? 'var(--sd-gold-500)' : 'var(--sd-mist)' }"
        />
      </div>
    </SdCard>

    <!-- Top tabs -->
    <nav class="top-tabs">
      <RouterLink
        v-for="tab in tabs"
        :key="tab.key"
        :to="tab.to()"
        :class="['top-tabs__item', { 'top-tabs__item--on': activeTab === tab.key }]"
        replace
      >
        <component :is="tab.icon" :size="14" :stroke-width="activeTab === tab.key ? 2 : 1.75" />
        <span>{{ tab.label }}</span>
        <small v-if="tab.count && tab.count()">{{ tab.count() }}</small>
      </RouterLink>
    </nav>

    <!-- Sub-route content -->
    <RouterView />
  </AppLayout>
</template>

<style scoped>
.hero-card { margin-bottom: 12px; }

.hero-card__top {
  display: flex;
  align-items: center;
  gap: 14px;
}

.hero-card__mark {
  width: 46px;
  height: 46px;
  border-radius: var(--sd-r-md);
  background: linear-gradient(140deg, #1d3d72, #0a1c3d);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--sd-shadow-sm);
  flex: none;
}
.hero-card__mark img { width: 34px; height: 34px; object-fit: contain; }

.hero-card__info { flex: 1; min-width: 0; }

.hero-card__name-row {
  display: flex;
  align-items: center;
  gap: 8px;
}
.hero-card__name {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 19px;
  letter-spacing: -0.01em;
  color: var(--sd-fg1);
  line-height: 1;
}
.hero-card__uuid {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.02em;
  margin-top: 4px;
}

/* Top segmented tabs */
.top-tabs {
  display: flex;
  gap: 4px;
  padding: 4px;
  background: rgba(16, 42, 87, .06);
  border-radius: var(--sd-r-pill);
  margin-bottom: 12px;
}

.top-tabs__item {
  flex: 1;
  padding: 9px 6px;
  border-radius: var(--sd-r-pill);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-family: var(--sd-font-display);
  font-weight: 500;
  font-size: 13px;
  color: var(--sd-fg2);
  text-decoration: none;
  transition: background var(--sd-dur-fast) var(--sd-ease-out),
              color var(--sd-dur-fast) var(--sd-ease-out);
}
.top-tabs__item--on {
  background: #fff;
  color: var(--sd-ink);
  box-shadow: var(--sd-shadow-sm);
}

.top-tabs__item small {
  display: inline-block;
  font-family: var(--sd-font-display);
  background: rgba(16, 42, 87, .10);
  color: var(--sd-ink);
  font-size: 10px;
  padding: 2px 6px;
  border-radius: 999px;
  font-weight: 600;
}
.top-tabs__item--on small {
  background: var(--sd-ink);
  color: #fff;
}
</style>
