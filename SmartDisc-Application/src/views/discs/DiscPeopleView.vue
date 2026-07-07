<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { Link, Link2, Mail, Crown, Eye, X } from 'lucide-vue-next'
import SdAvatar from '@/components/ui/SdAvatar.vue'
import { SdBtn, SdChip, SdCard } from '@/components/ui'
import { useDiscs } from '@/composables/useDiscs'

const route = useRoute()
const { getDisc } = useDiscs()
const disc = computed(() => getDisc(route.params.id))

const members = [
  { name: 'Alex Rivera', email: 'alex@smartdisc.io · you', role: 'owner', hue: 210 },
  { name: 'Mia Chen',    email: 'mia@team.io',             role: 'read',  hue: 320 },
  { name: 'Jonas Berg',  email: 'jonas@team.io',           role: 'read',  hue: 130 },
]
</script>

<template>
  <div class="people-wrap">
    <!-- Invite link card -->
    <SdCard>
      <div class="card-row card-row--mb">
        <Link :size="16" style="color: var(--sd-azure);" />
        <span class="card-label">Invite link</span>
      </div>
      <div class="invite-box">
        <Link2 :size="14" style="color: var(--sd-azure); flex: none;" />
        <code class="invite-code">smartdisc.io/i/{{ disc?.id ?? '…' }}</code>
        <SdBtn variant="primary" size="sm">Copy</SdBtn>
      </div>
      <div class="invite-actions">
        <SdBtn variant="ghost" size="sm">
          <template #icon-left><Mail :size="14" /></template>
          Invite by email
        </SdBtn>
      </div>
    </SdCard>

    <!-- Members card -->
    <SdCard>
      <div class="card-row card-row--mb">
        <span class="card-label">With access</span>
        <span class="count-label">{{ members.length }} people</span>
      </div>
      <div
        v-for="(m, i) in members"
        :key="m.email"
        class="member-row"
        :class="{ 'member-row--border': i > 0 }"
      >
        <SdAvatar :name="m.name" :hue="m.hue" />
        <div class="member-info">
          <div class="member-name">{{ m.name }}</div>
          <div class="member-email">{{ m.email }}</div>
        </div>
        <SdChip v-if="m.role === 'owner'" tone="owner">
          <template #icon><Crown :size="12" /></template>
          Owner
        </SdChip>
        <template v-else>
          <SdChip tone="read">
            <template #icon><Eye :size="12" /></template>
            Read
          </SdChip>
          <button class="remove-btn">
            <X :size="14" style="color: var(--sd-fg3);" />
          </button>
        </template>
      </div>
    </SdCard>

    <div style="height: 100px;" />
  </div>
</template>

<style scoped>
.people-wrap {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.card-row {
  display: flex;
  align-items: center;
  gap: 8px;
}
.card-row--mb { margin-bottom: 10px; }

.card-label {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  flex: 1;
}
.count-label {
  font-family: var(--sd-font-display);
  font-size: 12px;
  color: var(--sd-fg3);
}

.invite-box {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 8px 10px 14px;
  background: rgba(16, 42, 87, .04);
  border: 1px dashed rgba(16, 42, 87, .18);
  border-radius: var(--sd-r-md);
}
.invite-code {
  font-family: var(--sd-font-display);
  font-size: 12px;
  color: var(--sd-ink-700);
  flex: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.invite-actions {
  display: flex;
  gap: 8px;
  margin-top: 12px;
}

.member-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 0;
}
.member-row--border {
  border-top: 1px solid rgba(16, 42, 87, .06);
  padding-top: 12px;
  margin-top: 4px;
}

.member-info { flex: 1; min-width: 0; }
.member-name {
  font-family: var(--sd-font-body);
  font-weight: 600;
  font-size: 14px;
  color: var(--sd-fg1);
  line-height: 1.1;
}
.member-email {
  font-family: var(--sd-font-display);
  font-size: 11px;
  color: var(--sd-fg3);
  margin-top: 3px;
}

.remove-btn {
  width: 30px;
  height: 30px;
  border: none;
  background: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  flex: none;
}
</style>
