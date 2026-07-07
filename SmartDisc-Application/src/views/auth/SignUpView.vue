<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { User, Mail, Lock, Info } from 'lucide-vue-next'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AuthBackBtn from '@/components/auth/AuthBackBtn.vue'
import { SdBtn, SdField } from '@/components/ui'
import SdPasswordHint from '@/components/auth/SdPasswordHint.vue'
import { useAuth } from '@/composables/useAuth'
import { email as validateEmail, password as validatePassword, required } from '@/utils/validate'
import { sanitizeName, sanitizeEmail, sanitizePassword } from '@/utils/sanitize'

const router = useRouter()
const { signUp, isLoading, error: authError, clearError } = useAuth()

const form   = reactive({ name: '', email: '', password: '', confirm: '' })
const errors = reactive({ name: '', email: '', password: '', confirm: '' })
const showHint = ref(false)

function validate() {
  errors.name     = form.name.trim().length >= 2 ? '' : 'Full name must be at least 2 characters'
  errors.email    = validateEmail(form.email)
  errors.password = validatePassword(form.password)
  errors.confirm  = form.confirm !== form.password
    ? 'Passwords do not match'
    : required(form.confirm, 'Confirm password')
  return !errors.name && !errors.email && !errors.password && !errors.confirm
}

async function handleSubmit() {
  clearError()
  if (!validate()) return
  try {
    await signUp(form.name, form.email, form.password)
    router.push('/onboarding')
  } catch {
    // authError is populated by useAuth
  }
}
</script>

<template>
  <AuthLayout>
    <nav class="auth-nav">
      <AuthBackBtn to="/welcome" />
    </nav>

    <header class="auth-header">
      <p class="auth-eyebrow">Create account</p>
      <h1 class="auth-h1">Hi there.</h1>
      <p class="auth-sub">
        A SmartDisc account lets you pair discs and review every throw.
      </p>
    </header>

    <form class="auth-form" novalidate @submit.prevent="handleSubmit">
      <SdField
        v-model="form.name"
        label="Full name"
        placeholder="Alex Rivera"
        autocomplete="name"
        :error="errors.name"
        :disabled="isLoading"
        :sanitize="sanitizeName"
        :maxlength="50"
        @input="errors.name = ''"
      >
        <template #icon><User :size="18" :stroke-width="1.75" /></template>
      </SdField>

      <SdField
        v-model="form.email"
        label="Email"
        placeholder="you@email.com"
        type="email"
        autocomplete="username"
        :error="errors.email"
        :disabled="isLoading"
        :sanitize="sanitizeEmail"
        :maxlength="254"
        @input="errors.email = ''"
      >
        <template #icon><Mail :size="18" :stroke-width="1.75" /></template>
      </SdField>

      <!-- Password with info toggle -->
      <div class="password-wrap">
        <SdField
          v-model="form.password"
          label="Password"
          placeholder="password"
          type="password"
          autocomplete="new-password"
          :error="errors.password"
          :disabled="isLoading"
          :sanitize="sanitizePassword"
          :maxlength="128"
          @input="errors.password = ''; errors.confirm = ''"
        >
          <template #icon><Lock :size="18" :stroke-width="1.75" /></template>
          <template #action>
            <button
              type="button"
              class="info-btn"
              :class="{ 'info-btn--active': showHint }"
              @click="showHint = !showHint"
              :aria-label="showHint ? 'Hide password rules' : 'Show password rules'"
            >
              <Info :size="16" :stroke-width="1.75" />
            </button>
          </template>
        </SdField>
        <SdPasswordHint v-if="showHint" :value="form.password" />
      </div>

      <!-- Confirm password -->
      <SdField
        v-model="form.confirm"
        label="repeat password"
        placeholder="repeat your password"
        type="password"
        autocomplete="new-password"
        :error="errors.confirm"
        :disabled="isLoading"
        :sanitize="sanitizePassword"
        :maxlength="128"
        @input="errors.confirm = ''"
      >
        <template #icon><Lock :size="18" :stroke-width="1.75" /></template>
      </SdField>

      <p v-if="authError" class="auth-form-error" role="alert">
        {{ authError }}
      </p>

      <SdBtn
        type="submit"
        variant="primary"
        size="lg"
        block
        :disabled="isLoading"
      >
        {{ isLoading ? 'Creating account…' : 'Create account' }}
      </SdBtn>

      <p class="sign-up__legal">
        By creating an account you agree to the SmartDisc
        <a href="#" class="sign-up__legal-link">Terms</a> and
        <a href="#" class="sign-up__legal-link">Privacy Policy</a>.
      </p>
    </form>

    <div class="auth-spacer" />

    <footer class="auth-footer">
      Already have one?
      <RouterLink to="/sign-in" class="auth-footer__link">Sign in</RouterLink>
    </footer>
  </AuthLayout>
</template>

<style scoped>
.password-wrap {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.info-btn {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: none;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: var(--sd-fg3);
  transition: color var(--sd-dur-fast) var(--sd-ease-out),
              background var(--sd-dur-fast) var(--sd-ease-out);
}
.info-btn:hover       { color: var(--sd-azure); background: rgba(111,147,181,.10); }
.info-btn--active     { color: var(--sd-azure); background: rgba(111,147,181,.14); }

.sign-up__legal {
  font-family: var(--sd-font-body);
  font-size: 12px;
  color: var(--sd-fg3);
  text-align: center;
  line-height: 1.45;
  margin: 0 14px;
}
.sign-up__legal-link {
  color: var(--sd-azure);
  text-decoration: none;
}
.sign-up__legal-link:hover { text-decoration: underline; }
</style>
