<script setup>
defineProps({
  /** Dark navy variant used on the Welcome screen. */
  dark: { type: Boolean, default: false },
})
</script>

<template>
  <div :class="['auth-wrap', { 'auth-wrap--dark': dark }]">
    <!-- Ambient background blooms — decorative only -->
    <div class="auth-blooms" aria-hidden="true" />

    <!-- Centered content column, max 390 px (mobile-width) -->
    <main class="auth-frame">
      <slot />
    </main>
  </div>
</template>

<style scoped>
.auth-wrap {
  min-height: 100dvh;
  display: flex;
  align-items: stretch;
  justify-content: center;
  background: var(--sd-paper);
  position: relative;
  overflow: hidden;
}
.auth-wrap--dark {
  background: var(--sd-ink-900);
}

/* Background blooms */
.auth-blooms {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 0;
}
.auth-blooms::before {
  content: '';
  position: absolute;
  width: 480px;
  height: 480px;
  left: -160px;
  top: -160px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(111, 147, 181, .55), transparent 62%);
}
.auth-blooms::after {
  content: '';
  position: absolute;
  width: 420px;
  height: 420px;
  right: -160px;
  top: 30%;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(222, 195, 140, .45), transparent 60%);
}
.auth-wrap--dark .auth-blooms::before {
  background: radial-gradient(circle, rgba(111, 147, 181, .30), transparent 65%);
}
.auth-wrap--dark .auth-blooms::after {
  background: radial-gradient(circle, rgba(222, 195, 140, .28), transparent 60%);
}

/* Content frame */
.auth-frame {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 390px;
  min-height: 100dvh;
  display: flex;
  flex-direction: column;
  padding: 0 22px;
  padding-top: env(safe-area-inset-top, 0);
  padding-bottom: env(safe-area-inset-bottom, 0);
}
</style>
