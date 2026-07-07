import { ref, readonly } from 'vue'

const _discs = ref([
  {
    id: '7f3a9c2e',
    name: 'Sky Hammer',
    uuid: 'SD-7F3A-9C2E-04B1',
    throws: 42,
    longest: 41,
    players: 3,
    fav: true,
    lastActive: 'Active 2 min ago',
    throws_list: [
      { id: 1, name: 'Backhand to Mia', time: 'Today · 14:32 · 24 km/h', rpm: 1180, fav: true },
      { id: 2, name: '14:29',           time: 'Today · 14:29 · 19 km/h', rpm:  920, auto: true },
      { id: 3, name: 'Long huck',       time: 'Today · 14:21 · 27 km/h', rpm: 1320, fav: true },
      { id: 4, name: '14:08',           time: 'Today · 14:08 · 16 km/h', rpm:  870, auto: true },
      { id: 5, name: 'Flick cross-field', time: 'Today · 13:55 · 23 km/h', rpm: 1090 },
      { id: 6, name: '13:40',           time: 'Today · 13:40 · 21 km/h', rpm: 1010, auto: true },
    ],
  },
  {
    id: '22b871d0',
    name: 'Night Owl',
    uuid: 'SD-22B8-71D0-9AAC',
    throws: 18,
    longest: 36,
    players: 1,
    fav: false,
    lastActive: 'Yesterday · 18:51',
    throws_list: [],
  },
  {
    id: '0a4dee19',
    name: 'Tournament',
    uuid: 'SD-0A4D-EE19-6B82',
    throws: 91,
    longest: 48,
    players: 5,
    fav: false,
    lastActive: '3 days ago',
    throws_list: [],
  },
])

const _sharedDiscs = ref([
  {
    id: '5c19da4f',
    name: 'Team Disc — Reds',
    uuid: 'SD-5C19-DA4F-2B77',
    owner: 'Mia Chen',
    throws: 58,
    longest: 44,
    topRpm: 1350,
    players: 6,
    throws_list: [
      { id: 10, name: 'Endzone score', time: 'Sat · 11:14 · 28 km/h', rpm: 1350, fav: true,  readonly: true },
      { id: 11, name: '11:02',        time: 'Sat · 11:02 · 23 km/h', rpm: 1080, auto: true,  readonly: true },
      { id: 12, name: 'Break throw',  time: 'Sat · 10:55 · 21 km/h', rpm:  990,              readonly: true },
      { id: 13, name: '10:40',        time: 'Sat · 10:40 · 24 km/h', rpm: 1160, auto: true,  readonly: true },
      { id: 14, name: '10:31',        time: 'Sat · 10:31 · 18 km/h', rpm:  880, auto: true,  readonly: true },
    ],
  },
  {
    id: '1f00b4c2',
    name: 'Scrimmage night',
    uuid: 'SD-1F00-B4C2-AA31',
    owner: 'A. Becker',
    throws: 23,
    longest: 35,
    topRpm: 1200,
    players: 4,
    throws_list: [],
  },
  {
    id: '9d20c87f',
    name: 'Weekend league',
    uuid: 'SD-9D20-C87F-5511',
    owner: 'J. Lopez',
    throws: 40,
    longest: 39,
    topRpm: 1280,
    players: 8,
    throws_list: [],
  },
])

export function useDiscs() {
  function getDisc(id) {
    return _discs.value.find(d => d.id === id) ?? null
  }
  function getSharedDisc(id) {
    return _sharedDiscs.value.find(d => d.id === id) ?? null
  }
  return {
    discs: readonly(_discs),
    sharedDiscs: readonly(_sharedDiscs),
    getDisc,
    getSharedDisc,
  }
}
