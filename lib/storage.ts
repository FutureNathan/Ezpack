/** SSR-safe localStorage helpers. */

export const isBrowser = typeof window !== "undefined";

export function readJson<T>(key: string, fallback: T): T {
  if (!isBrowser) return fallback;
  try {
    const raw = window.localStorage.getItem(key);
    if (raw == null) return fallback;
    return JSON.parse(raw) as T;
  } catch {
    return fallback;
  }
}

export function writeJson(key: string, value: unknown): void {
  if (!isBrowser) return;
  try {
    window.localStorage.setItem(key, JSON.stringify(value));
  } catch {
    // Quota or serialization failure — ignore for a prototype.
  }
}

export function removeKey(key: string): void {
  if (!isBrowser) return;
  try {
    window.localStorage.removeItem(key);
  } catch {
    // ignore
  }
}

/** Generate a stable, unique id with a prefix (used for user-added records). */
export function genId(prefix: string): string {
  if (isBrowser && typeof window.crypto?.randomUUID === "function") {
    return `${prefix}-${window.crypto.randomUUID()}`;
  }
  return `${prefix}-${Math.random().toString(36).slice(2, 10)}${Date.now().toString(
    36
  )}`;
}

/** localStorage keys, namespaced so the swap to a backend is a clean boundary. */
export const STORAGE_KEYS = {
  boxes: "ezpack.boxes.v1",
  // Bumped to v2 when the default paddings changed (standard+ 2", fragile 3",
  // custom 4") so existing sessions adopt the new defaults instead of a stale
  // cached config.
  levels: "ezpack.levels.v2",
  history: "ezpack.history.v1",
  prefs: "ezpack.prefs.v1",
} as const;
