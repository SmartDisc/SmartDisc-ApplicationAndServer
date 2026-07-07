<script setup>
import { useRouter } from 'vue-router'
import { Bell, Search, QrCode } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdDiscCard from '@/components/discs/SdDiscCard.vue'
import { SdIconBtn, SdPageHeader } from '@/components/ui'
import { useDiscs } from '@/composables/useDiscs'

const router = useRouter()
const { discs } = useDiscs()
</script>

<template>
  <AppLayout>
    <SdAppBar title="">
      <template #action>
        <div class="mydisc-actions">
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
      class="mydisc-head"
      :eyebrow="`Your equipment · ${discs.length} discs`"
      title="My Discs"
    />

    <div class="mydisc-list">
      <SdDiscCard
        v-for="disc in discs"
        :key="disc.id"
        :name="disc.name"
        :uuid="disc.uuid"
        :throws="disc.throws"
        :longest="disc.longest"
        :players="disc.players"
        :fav="disc.fav"
        :last-active="disc.lastActive"
        @click="router.push(`/discs/${disc.id}`)"
      />
    </div>

    <RouterLink to="/discs/add" class="fab">
      <QrCode :size="26" :stroke-width="2" />
    </RouterLink>
  </AppLayout>
</template>

<style scoped>
.mydisc-head { margin-bottom: 14px; }

.mydisc-list {
  display: flex;
  flex-direction: column;
  gap: 14px;
  padding-bottom: 100px;
}

.mydisc-actions {
  margin-top: 2px;
  margin-right: 3px;
  display: flex;
  gap: 10px;
  align-items: center;
}

.fab {
  position: fixed;
  bottom: 110px;
  right: max(22px, calc(50vw - 173px));
  width: 58px;
  height: 58px;
  border-radius: 50%;
  background: var(--sd-gold-grad);
  color: #5a4416;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 14px 32px rgba(184, 146, 79, .5), var(--sd-shadow-md);
  z-index: 10;
  border: 1px solid rgba(255, 255, 255, .4);
  text-decoration: none;
}
.fab:active { transform: scale(0.93); }
</style>
