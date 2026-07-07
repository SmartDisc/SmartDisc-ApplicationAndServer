<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import SdThrowRow from '@/components/discs/SdThrowRow.vue'
import { SdChip } from '@/components/ui'
import { Star, Calendar } from 'lucide-vue-next'
import { useDiscs } from '@/composables/useDiscs'

const route  = useRoute()
const router = useRouter()
const { getDisc } = useDiscs()

const disc = computed(() => getDisc(route.params.id))
</script>

<template>
  <div class="throws-wrap">
    <!-- Filter chips -->
    <div class="throws-filters">
      <SdChip tone="solid-light">
        <template #icon><Star :size="12" /></template>
        Favorites
      </SdChip>
      <SdChip tone="solid-light">
        <template #icon><Calendar :size="12" /></template>
        Today
      </SdChip>
      <span class="throws-filters__sort">Newest first</span>
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
        @click="router.push(`/discs/${route.params.id}/throw/${t.id}`)"
      />
    </div>

    <div v-if="!disc?.throws_list?.length" class="throws-empty">
      No throws yet — go throw this disc!
    </div>
  </div>
</template>

<style scoped>
.throws-wrap {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding-bottom: 100px;
}

.throws-filters {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 4px;
}

.throws-filters__sort {
  margin-left: auto;
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.throws-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.throws-empty {
  font-family: var(--sd-font-body);
  font-size: 14px;
  color: var(--sd-fg3);
  text-align: center;
  padding: 32px 0;
}
</style>
