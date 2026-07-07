import { defineStore } from 'pinia'
import { Preferences } from '@capacitor/preferences'
import { apiFetch, ApiError } from '@/services/api'
import { isTokenExpired } from '@/utils/jwt'

// Capacitor Preferences persists per-app-sandbox (Keychain-backed UserDefaults on
// iOS, EncryptedSharedPreferences-eligible SharedPreferences on Android; falls
// back to localStorage on web). It is not a secure enclave, but it is not
// readable by other apps either — the standard place to keep a short-lived
// Bearer token for a Capacitor app. We deliberately never store the password.
const TOKEN_KEY = 'sd_auth_token'

function mapAuthError(err) {
  if (!(err instanceof ApiError)) return 'Something went wrong. Please try again.'
  if (err.status === null) return err.message || 'Could not reach the server. Check your connection and try again.'
  if (err.status >= 500) return 'Something went wrong on our end. Please try again shortly.'
  if (err.status === 429) {
    if (err.retryAfter) {
      const mins = Math.ceil(err.retryAfter / 60)
      return `Too many attempts. Please try again in ${mins} minute${mins === 1 ? '' : 's'}.`
    }
    return err.message || 'Too many attempts. Please wait a moment and try again.'
  }
  if (err.fieldErrors) return Object.values(err.fieldErrors)[0]
  return err.message || 'Something went wrong. Please try again.'
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null,
    user: null,
    isLoading: false,
    error: null,
    initialized: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
  },

  actions: {
    /** Restore a session from disk on app start. Call once, before the first route resolves. */
    async init() {
      if (this.initialized) return
      this.initialized = true

      const { value: storedToken } = await Preferences.get({ key: TOKEN_KEY })
      if (!storedToken) return

      if (isTokenExpired(storedToken)) {
        await Preferences.remove({ key: TOKEN_KEY })
        return
      }

      this.token = storedToken
      try {
        await this.fetchMe()
      } catch {
        // Token looked valid locally but the server rejected it (revoked, clock
        // skew, tampering) — drop the session rather than limp along unauthenticated.
        await this._clearSession()
      }
    },

    async fetchMe() {
      const me = await apiFetch('/api/me', { token: this.token })
      this.user = me
    },

    async signIn(email, password) {
      this.isLoading = true
      this.error = null
      try {
        const { token } = await apiFetch('/api/login', {
          method: 'POST',
          body: { email, password },
        })
        await this._setSession(token)
      } catch (err) {
        this.error = mapAuthError(err)
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async signUp(name, email, password) {
      this.isLoading = true
      this.error = null
      try {
        const { token } = await apiFetch('/api/signup', {
          method: 'POST',
          body: { name, email, password },
        })
        await this._setSession(token)
      } catch (err) {
        this.error = mapAuthError(err)
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async signOut() {
      await this._clearSession()
    },

    clearError() {
      this.error = null
    },

    async _setSession(token) {
      this.token = token
      await Preferences.set({ key: TOKEN_KEY, value: token })
      try {
        await this.fetchMe()
      } catch (err) {
        await this._clearSession()
        throw err
      }
    },

    async _clearSession() {
      this.token = null
      this.user = null
      await Preferences.remove({ key: TOKEN_KEY })
    },
  },
})
