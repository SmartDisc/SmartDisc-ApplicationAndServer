<!-- Temporary placeholder for routes not yet implemented. Remove when the real view is built. -->
<script setup>
import { useRoute } from 'vue-router'
const route = useRoute()
</script>

<template>
  <div class="placeholder">
    <p class="placeholder__label">{{ route.name }}</p>
    <h1 class="placeholder__title">Coming soon</h1>
  </div>
</template>

<style scoped>
.placeholder {
  min-height: 100dvh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: var(--sd-paper);
  font-family: var(--sd-font-display);
}
.placeholder__label {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--sd-azure);
  margin: 0;
}
.placeholder__title {
  font-size: 28px;
  font-weight: 600;
  color: var(--sd-fg1);
  letter-spacing: -0.02em;
  margin: 0;
}
</style>
