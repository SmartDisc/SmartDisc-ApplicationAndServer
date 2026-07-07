/**
 * Field validators — each returns an error string or empty string.
 * Compose them in a validate() function per form.
 */

export function required(value, label = 'This field') {
  return value?.trim() ? '' : `${label} is required`
}

export function email(value) {
  if (!value?.trim()) return 'Email is required'
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim())
    ? ''
    : 'Enter a valid email address'
}

export function minLength(value, min, label = 'This field') {
  if (!value?.trim()) return `${label} is required`
  return value.length >= min
    ? ''
    : `${label} must be at least ${min} characters`
}

export function password(value) {
  if (!value) return 'Password is required'
  if (value.length < 8)           return 'At least 8 characters required'
  if (!/[A-Z]/.test(value))       return 'Add an uppercase letter (A–Z)'
  if (!/[a-z]/.test(value))       return 'Add a lowercase letter (a–z)'
  if (!/[^A-Za-z0-9]/.test(value)) return 'Add a special character (!@#…)'
  return ''
}

/** Returns the live status of each password rule — used by SdPasswordHint. */
export function passwordRules(value = '') {
  return [
    { label: 'At least 8 characters',      ok: value.length >= 8 },
    { label: 'Uppercase letter (A–Z)',      ok: /[A-Z]/.test(value) },
    { label: 'Lowercase letter (a–z)',      ok: /[a-z]/.test(value) },
    { label: 'Special character (!@#$…)',   ok: /[^A-Za-z0-9]/.test(value) },
  ]
}
