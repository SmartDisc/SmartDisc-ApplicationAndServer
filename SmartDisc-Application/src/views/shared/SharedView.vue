<script setup>
import { useRouter } from 'vue-router'
import { Bell, Search } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdDiscCard from '@/components/discs/SdDiscCard.vue'
import { SdIconBtn, SdPageHeader } from '@/components/ui'
import { useDiscs } from '@/composables/useDiscs'

const router = useRouter()
const { sharedDiscs } = useDiscs()
</script>

<template>
  <AppLayout>
    <SdAppBar title="">
      <template #action>
        <div class="shared-actions">
          <SdIconBtn variant="glass" to="/notifications" badge>
            <Bell :size="17" :stroke-width="1.75" />
          </SdIconBtn>
          <SdIconBtn variant="glass" to="/search">
            <Search :size="17" :stroke-width="1.75" />
          </SdIconBtn>
        </div>
      </template>
    </SdAppBar>

    <SdPageHeader
      class="shared-head"
      eyebrow="Shared with you"
      title="Team discs"
      subtitle="View throws and stats. Read-only — you can't edit these discs."
    />

    <div class="shared-list">
      <SdDiscCard
        v-for="disc in sharedDiscs"
        :key="disc.id"
        :name="disc.name"
        :uuid="`from ${disc.owner} · ${disc.uuid.slice(0, 11)}–`"
        :throws="disc.throws"
        :longest="disc.longest"
        :players="disc.players"
        :shared="true"
        @click="router.push(`/shared/${disc.id}`)"
      />
    </div>
  </AppLayout>
</template>

<style scoped>
.shared-head { margin-bottom: 16px; }

.shared-list {
  display: flex;
  flex-direction: column;
  gap: 14px;
  padding-bottom: 100px;
}

.shared-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

</style>
