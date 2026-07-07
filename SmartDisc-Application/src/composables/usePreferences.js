import { ref, readonly } from 'vue'

const STORAGE_KEY = 'sd_preferences'

function load() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    return raw ? JSON.parse(raw) : null
  } catch {
    return null
  }
}

function persist(data) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(data))
}

const _stored       = load()
const _language     = ref(_stored?.language     ?? null)
const _theme        = ref(_stored?.theme        ?? null)
const _distanceUnit = ref(_stored?.distanceUnit ?? 'm')
const _speedUnit    = ref(_stored?.speedUnit    ?? 'km/h')

function applyTheme(theme) {
  if (!theme) return
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  const dark = theme === 'dark' || (theme === 'system' && prefersDark)
  document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light')
}

if (_theme.value) applyTheme(_theme.value)

function snapshot() {
  return {
    language:     _language.value,
    theme:        _theme.value,
    distanceUnit: _distanceUnit.value,
    speedUnit:    _speedUnit.value,
  }
}

export function usePreferences() {
  function saveLanguage(lang) {
    _language.value = lang
    persist(snapshot())
  }

  function saveTheme(theme) {
    _theme.value = theme
    persist(snapshot())
    applyTheme(theme)
  }

  function saveDistanceUnit(unit) {
    _distanceUnit.value = unit
    persist(snapshot())
  }

  function saveSpeedUnit(unit) {
    _speedUnit.value = unit
    persist(snapshot())
  }

  return {
    language:     readonly(_language),
    theme:        readonly(_theme),
    distanceUnit: readonly(_distanceUnit),
    speedUnit:    readonly(_speedUnit),
    saveLanguage,
    saveTheme,
    saveDistanceUnit,
    saveSpeedUnit,
  }
}
