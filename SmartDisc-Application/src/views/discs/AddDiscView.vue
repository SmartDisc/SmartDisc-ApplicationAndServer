<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Hash, Lock, Check } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import SdAppBar from '@/components/ui/SdAppBar.vue'
import { SdBtn, SdField } from '@/components/ui'
import { sanitizeUUID, sanitizePassword } from '@/utils/sanitize'

const router = useRouter()
const uuid = ref('')
const password = ref('')
</script>

<template>
  <AppLayout :tabs="false">
    <SdAppBar back title="Pair a disc" />

    <p class="add-hint">
      Hold your camera over the QR code on the rim of the disc.
    </p>

    <!-- QR scan frame -->
    <div class="scan-wrap">
      <div class="scan-frame">
        <div class="scan-corners" />
        <div class="scan-disc">
          <img src="/images/SmartDisc_Mark.png" alt="" />
        </div>
      </div>
      <p class="scan-label">Scanning…</p>
    </div>

    <div class="add-form">
      <SdField
        v-model="uuid"
        label="Disc UUID"
        placeholder="SD-XXXX-XXXX-XXXX"
        :sanitize="sanitizeUUID"
        :maxlength="40"
      >
        <template #icon><Hash :size="18" :stroke-width="1.75" /></template>
      </SdField>
      <SdField
        v-model="password"
        label="Disc password"
        placeholder="From the packaging"
        type="password"
        :sanitize="sanitizePassword"
        :maxlength="128"
      >
        <template #icon><Lock :size="18" :stroke-width="1.75" /></template>
      </SdField>
      <SdBtn variant="primary" size="lg" block @click="router.push('/discs')">
        <template #icon-left><Check :size="18" :stroke-width="2" /></template>
        Pair disc
      </SdBtn>
    </div>
  </AppLayout>
</template>

<style scoped>
.add-hint {
  font-family: var(--sd-font-body);
  font-size: 14px;
  color: var(--sd-fg2);
  text-align: center;
  margin: 0 0 18px;
  line-height: 1.4;
}

.scan-wrap {
  margin-bottom: 18px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.scan-frame {
  position: relative;
  width: 220px;
  height: 220px;
  border-radius: 26px;
  overflow: hidden;
  background: linear-gradient(145deg, #16335d, #08152e);
}
.scan-frame::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at 35% 30%, rgba(222, 195, 140, .30), transparent 55%),
    radial-gradient(circle at 70% 80%, rgba(111, 147, 181, .35), transparent 55%);
}

.scan-corners {
  position: absolute;
  inset: 22px;
  border-radius: 18px;
  background:
    linear-gradient(to right, #dec38c 0, #dec38c 26px, transparent 26px) top left / 26px 3px no-repeat,
    linear-gradient(to bottom, #dec38c 0, #dec38c 26px, transparent 26px) top left / 3px 26px no-repeat,
    linear-gradient(to left,  #dec38c 0, #dec38c 26px, transparent 26px) top right / 26px 3px no-repeat,
    linear-gradient(to bottom, #dec38c 0, #dec38c 26px, transparent 26px) top right / 3px 26px no-repeat,
    linear-gradient(to right, #dec38c 0, #dec38c 26px, transparent 26px) bottom left / 26px 3px no-repeat,
    linear-gradient(to top,   #dec38c 0, #dec38c 26px, transparent 26px) bottom left / 3px 26px no-repeat,
    linear-gradient(to left,  #dec38c 0, #dec38c 26px, transparent 26px) bottom right / 26px 3px no-repeat,
    linear-gradient(to top,   #dec38c 0, #dec38c 26px, transparent 26px) bottom right / 3px 26px no-repeat;
}

.scan-disc {
  position: absolute;
  inset: 50px;
  border-radius: 50%;
  background:
    radial-gradient(circle at 40% 35%, rgba(255, 255, 255, .16), transparent 55%),
    linear-gradient(145deg, #2a4e84, #11244a);
  box-shadow: inset 0 0 22px rgba(0, 0, 0, .4);
  display: flex;
  align-items: center;
  justify-content: center;
}
.scan-disc img { width: 70px; opacity: .9; }

.scan-label {
  margin: 12px 0 0;
  font-family: var(--sd-font-display);
  font-size: 12px;
  color: var(--sd-azure);
  letter-spacing: 0.14em;
  text-transform: uppercase;
}

.add-form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
</style>
