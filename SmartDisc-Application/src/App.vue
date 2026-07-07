<script setup>
import { RouterView } from 'vue-router'
</script>

<template>
  <RouterView />
</template>

<style>
*,
*::before,
*::after { box-sizing: border-box; }

html,
body {
  margin: 0;
  padding: 0;
  font-family: var(--sd-font-body);
  color: var(--sd-fg1);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /* Prevent elastic bounce scroll on iOS outside scroll containers */
  overscroll-behavior: none;
  /* Full viewport including notch area */
  height: 100%;
}

/* Remove tap highlight on all interactive elements */
a, button, [role="button"], input, select, textarea {
  -webkit-tap-highlight-color: transparent;
}

/* Prevent double-tap zoom, keep touch responsiveness */
button, a {
  touch-action: manipulation;
}
</style>
