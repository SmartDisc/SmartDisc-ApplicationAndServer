<script setup>
import { useRouter } from 'vue-router'
import { Trophy, Users, Zap } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import SdAvatar from '@/components/ui/SdAvatar.vue'
import { SdChip } from '@/components/ui'

const router = useRouter()
</script>

<template>
  <AppLayout :tabs="false">
    <SdAppBar back title="Activity">
      <template #action>
        <button class="mark-all">Mark all read</button>
      </template>
    </SdAppBar>

    <!-- Filter chips -->
    <div class="filter-row">
      <SdChip tone="owner">All</SdChip>
      <SdChip tone="solid-light">Unread · 4</SdChip>
      <SdChip tone="solid-light">Throws</SdChip>
      <SdChip tone="solid-light">Sharing</SdChip>
    </div>

    <!-- Today -->
    <p class="day-label">Today</p>

    <div class="notif notif--unread">
      <div class="notif__dot" />
      <div class="notif__avatar notif__avatar--gold">
        <Trophy :size="18" style="color: #5a4416;" />
      </div>
      <div class="notif__body">
        <div class="notif__title">New personal best · 41 m</div>
        <div class="notif__desc">Sky Hammer · "Long huck" at 1 320 rpm beat your previous best by 4 m.</div>
        <div class="notif__meta">14:21 · 12 min ago</div>
      </div>
    </div>

    <div class="notif notif--unread">
      <div class="notif__dot" />
      <SdAvatar name="Mia Chen" :size="36" :hue="320" class="notif__avatar" />
      <div class="notif__body">
        <div class="notif__title">Mia Chen joined Sky Hammer</div>
        <div class="notif__desc">She accepted your invite and can now view all throws.</div>
        <div class="notif__meta">11:08</div>
      </div>
    </div>

    <div class="notif notif--unread">
      <div class="notif__dot" />
      <div class="notif__avatar notif__avatar--glass">
        <Zap :size="18" style="color: var(--sd-ink);" />
      </div>
      <div class="notif__body">
        <div class="notif__title">Sky Hammer is connected</div>
        <div class="notif__desc">Battery 88%. Ready for today's session.</div>
        <div class="notif__meta">09:42</div>
      </div>
    </div>

    <!-- Yesterday -->
    <p class="day-label" style="margin-top: 14px;">Yesterday</p>

    <div class="notif">
      <div class="notif__dot notif__dot--read" />
      <SdAvatar name="Jonas Berg" :size="36" :hue="130" class="notif__avatar" />
      <div class="notif__body">
        <div class="notif__title">Jonas favorited "Sunset huck"</div>
        <div class="notif__desc">Night Owl · 36 m at 1 240 rpm.</div>
        <div class="notif__meta">18:14</div>
      </div>
    </div>

    <div class="notif">
      <div class="notif__dot notif__dot--read" />
      <div class="notif__avatar notif__avatar--gold">
        <Users :size="18" style="color: #5a4416;" />
      </div>
      <div class="notif__body">
        <div class="notif__title">Coach M shared Team Disc — Reds</div>
        <div class="notif__desc">You have read access to 58 throws.</div>
        <div class="notif__meta">Yesterday · 10:30</div>
      </div>
    </div>

    <div style="height: 40px;" />
  </AppLayout>
</template>

<style scoped>
.mark-all {
  font-family: var(--sd-font-display);
  font-weight: 500;
  font-size: 13px;
  color: var(--sd-ink);
  background: none;
  border: none;
  cursor: pointer;
  padding: 0 8px;
}

.filter-row {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 8px;
}

.day-label {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 11px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--sd-azure);
  margin: 8px 0 4px;
}

.notif {
  display: flex;
  gap: 12px;
  padding: 14px 0;
  border-top: 1px solid rgba(16, 42, 87, .07);
}

.notif__dot {
  width: 8px;
  height: 8px;
  border-radius: 999px;
  background: var(--sd-gold-400);
  margin-top: 6px;
  flex: none;
}
.notif__dot--read { background: transparent; }

.notif__avatar {
  width: 36px;
  height: 36px;
  border-radius: 12px;
  background: linear-gradient(140deg, #1d3d72, #0a1c3d);
  display: flex;
  align-items: center;
  justify-content: center;
  flex: none;
}
.notif__avatar--gold { background: var(--sd-gold-grad); }
.notif__avatar--glass {
  background: var(--sd-glass-light-bg);
  border: 1px solid var(--sd-glass-light-border);
  color: var(--sd-ink);
}

.notif__body { flex: 1; min-width: 0; }
.notif__title {
  font-family: var(--sd-font-body);
  font-weight: 600;
  font-size: 14px;
  color: var(--sd-fg1);
  line-height: 1.25;
}
.notif__desc {
  font-family: var(--sd-font-body);
  font-size: 13px;
  color: var(--sd-fg2);
  margin-top: 3px;
  line-height: 1.35;
}
.notif__meta {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  margin-top: 4px;
  letter-spacing: 0.02em;
}
</style>
