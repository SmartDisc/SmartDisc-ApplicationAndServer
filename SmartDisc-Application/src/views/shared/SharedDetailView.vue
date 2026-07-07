<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { MoreHorizontal, Eye } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdStatTile from '@/components/ui/SdStatTile.vue'
import SdThrowRow from '@/components/discs/SdThrowRow.vue'
import { SdChip, SdCard, SdIconBtn, SdSectionLabel } from '@/components/ui'
import { useDiscs } from '@/composables/useDiscs'

const route  = useRoute()
const router = useRouter()
const { getSharedDisc } = useDiscs()
const disc = computed(() => getSharedDisc(route.params.id))
</script>

<template>
  <AppLayout>
    <SdAppBar back :title="disc?.name ?? ''" >
      <template #action>
        <SdIconBtn variant="glass">
          <MoreHorizontal :size="18" :stroke-width="1.75" />
        </SdIconBtn>
      </template>
    </SdAppBar>

    <!-- Hero card -->
    <SdCard v-if="disc" class="hero-card" :padding="18">
      <div class="hero-top">
        <div class="hero-info">
          <div class="hero-name">{{ disc.name }}</div>
          <div class="hero-uuid">{{ disc.uuid }} · owned by {{ disc.owner }}</div>
        </div>
        <SdChip tone="read">
          <template #icon><Eye :size="12" /></template>
          Read
        </SdChip>
      </div>
      <div class="stat-row">
        <SdStatTile :v="disc.throws" k="Throws" />
        <SdStatTile :v="disc.longest" u="m" k="Longest" />
        <SdStatTile :v="disc.topRpm" k="Top RPM" />
      </div>
    </SdCard>

    <!-- Throws -->
    <div class="section-header">
      <SdSectionLabel style="flex: 1; margin: 0;">Recent throws</SdSectionLabel>
      <span class="count">{{ disc?.throws }} total</span>
    </div>

    <div class="throws-list">
      <SdThrowRow
        v-for="t in disc?.throws_list ?? []"
        :key="t.id"
        :name="t.name"
        :time="t.time"
        :rpm="t.rpm"
        :fav="t.fav"
        :auto="t.auto"
        :readonly="true"
        @click="router.push(`/shared/${disc.id}/throw/${t.id}`)"
      />
    </div>

    <div style="height: 100px;" />
  </AppLayout>
</template>

<style scoped>
.hero-card {
  margin-bottom: 16px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.hero-top { display: flex; align-items: center; gap: 14px; }

.hero-info { flex: 1; min-width: 0; }
.hero-name {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 22px;
  letter-spacing: -0.01em;
  color: var(--sd-fg1);
  line-height: 1;
}
.hero-uuid {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.02em;
  margin-top: 4px;
}

.stat-row { display: flex; gap: 10px; }

.section-header {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
}
.count {
  font-family: var(--sd-font-display);
  font-size: 12px;
  color: var(--sd-fg3);
}

.throws-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
</style>
