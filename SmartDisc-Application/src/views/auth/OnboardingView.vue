<script setup>
import { useRouter } from 'vue-router'
import { QrCode, Zap, Users, ArrowRight } from 'lucide-vue-next'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { SdBtn } from '@/components/ui'

const router = useRouter()

const features = [
  {
    icon: QrCode,
    title: '1. Pair your disc',
    body: 'Scan the QR code on the disc and enter its password — once.',
  },
  {
    icon: Zap,
    title: '2. Throw it',
    body: 'Speed, spin and height are captured for every throw — automatically.',
  },
  {
    icon: Users,
    title: '3. Share with your team',
    body: 'Invite people via link — they can view, you stay in control.',
  },
]
</script>

<template>
  <AuthLayout>
    <!-- Skip action in top-right -->
    <nav class="onboarding-nav">
      <button class="onboarding-skip" @click="router.push('/discs')">
        Skip
      </button>
    </nav>

    <div class="onboarding-body">
      <!-- Feature card with logo -->
      <div class="onboarding-card">
        <div class="onboarding-mark">
          <img
            src="/images/SmartDisc_Mark.png"
            alt="SmartDisc"
            class="onboarding-mark__img"
          />
        </div>
        <p class="auth-eyebrow" style="margin-top: 8px;">How SmartDisc works</p>
        <h1 class="onboarding-h1">Three things to know</h1>
      </div>

      <!-- Feature list -->
      <div class="feature-list">
        <div
          v-for="(feature, i) in features"
          :key="i"
          class="feature-row"
          :class="{ 'feature-row--border': i > 0 }"
        >
          <div class="feature-icon">
            <component :is="feature.icon" :size="16" :stroke-width="1.75" />
          </div>
          <div class="feature-copy">
            <p class="feature-title">{{ feature.title }}</p>
            <p class="feature-body">{{ feature.body }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="auth-spacer" />

    <!-- Pager + CTA -->
    <div class="onboarding-footer">
      <div class="onboarding-pager" aria-hidden="true">
        <span />
        <span class="onboarding-pager__dot--active" />
        <span />
      </div>

      <SdBtn
        variant="primary"
        size="lg"
        block
        @click="router.push('/discs')"
      >
        Pair my first disc
        <template #icon-right>
          <ArrowRight :size="18" :stroke-width="2" />
        </template>
      </SdBtn>
    </div>
  </AuthLayout>
</template>

<style scoped>
.onboarding-nav {
  display: flex;
  justify-content: flex-end;
  padding: 12px 0 4px;
  flex: none;
}
.onboarding-skip {
  font-family: var(--sd-font-display);
  font-size: 13px;
  font-weight: 500;
  color: var(--sd-fg2);
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px 8px;
}
.onboarding-skip:hover { color: var(--sd-fg1); }

.onboarding-body {
  display: flex;
  flex-direction: column;
  gap: 16px;
  flex: none;
}

/* Feature highlight card */
.onboarding-card {
  background: var(--sd-glass-light-bg);
  border: 1px solid var(--sd-glass-light-border);
  -webkit-backdrop-filter: var(--sd-glass-blur);
          backdrop-filter: var(--sd-glass-blur);
  border-radius: var(--sd-r-lg);
  box-shadow: var(--sd-shadow-glass);
  padding: 22px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 4px;
}
.onboarding-mark {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: radial-gradient(circle at 35% 30%, rgba(255, 255, 255, .5), transparent 55%),
              var(--sd-gold-grad);
  box-shadow: 0 12px 32px rgba(184, 146, 79, .4),
              inset 0 0 0 1px rgba(255, 255, 255, .4);
  display: flex;
  align-items: center;
  justify-content: center;
}
.onboarding-mark__img { width: 54px; }
.onboarding-h1 {
  font-family: var(--sd-font-display);
  font-weight: 600;
  font-size: 22px;
  letter-spacing: -0.01em;
  color: var(--sd-fg1);
  margin: 4px 0 0;
}

/* Feature list */
.feature-list {
  background: var(--sd-glass-light-bg);
  border: 1px solid var(--sd-glass-light-border);
  -webkit-backdrop-filter: var(--sd-glass-blur-thin);
          backdrop-filter: var(--sd-glass-blur-thin);
  border-radius: var(--sd-r-md);
  box-shadow: var(--sd-shadow-glass);
  overflow: hidden;
}
.feature-row {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 14px 16px;
}
.feature-row--border { border-top: 1px solid rgba(16, 42, 87, .07); }
.feature-icon {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  background: linear-gradient(140deg, #1d3d72, #0a1c3d);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex: none;
  margin-top: 1px;
}
.feature-copy { flex: 1; min-width: 0; }
.feature-title {
  font-family: var(--sd-font-body);
  font-weight: 600;
  font-size: 14px;
  color: var(--sd-fg1);
  margin: 0 0 3px;
  line-height: 1.2;
}
.feature-body {
  font-family: var(--sd-font-body);
  font-size: 12px;
  color: var(--sd-fg3);
  margin: 0;
  line-height: 1.4;
}

/* Pager + footer */
.onboarding-footer {
  display: flex;
  flex-direction: column;
  gap: 12px;
  padding-bottom: 40px;
  flex: none;
}
.onboarding-pager {
  display: flex;
  justify-content: center;
  gap: 7px;
}
.onboarding-pager span {
  width: 7px;
  height: 7px;
  border-radius: 999px;
  background: rgba(16, 42, 87, .2);
}
.onboarding-pager__dot--active {
  background: var(--sd-ink) !important;
  width: 22px !important;
}
</style>
