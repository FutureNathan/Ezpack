"use client";

import * as React from "react";

import {
  DEFAULT_PACKING_LEVELS,
  normalizeLevels,
} from "@/lib/packing-levels";
import {
  getBoxRepository,
  type NewBox,
} from "@/lib/repository";
import { getSeedBoxes } from "@/lib/seed";
import type { ImportDraft } from "@/lib/import-boxes";
import {
  STORAGE_KEYS,
  genId,
  readJson,
  removeKey,
  writeJson,
} from "@/lib/storage";
import type {
  Box,
  HistoryEntry,
  PackingLevel,
  PackingLevelKey,
  PackingLevelsConfig,
  SortMode,
} from "@/lib/types";

interface Prefs {
  lastLevel: PackingLevelKey;
  sortMode: SortMode;
}

const DEFAULT_PREFS: Prefs = {
  lastLevel: "standard",
  sortMode: "closest_small",
};

interface AppStore {
  ready: boolean;
  boxes: Box[];
  levels: PackingLevelsConfig;
  history: HistoryEntry[];
  prefs: Prefs;

  // Box CRUD
  addBox: (input: NewBox) => Promise<Box>;
  updateBox: (id: string, patch: Partial<Omit<Box, "id">>) => Promise<void>;
  removeBox: (id: string) => Promise<void>;

  // Import / catalog / levels
  importBoxes: (
    drafts: ImportDraft[],
    mode: "append" | "replace"
  ) => Promise<number>;
  resetCatalog: () => Promise<void>;
  updateLevel: (key: PackingLevelKey, patch: Partial<PackingLevel>) => void;
  resetLevels: () => void;

  // History
  addHistory: (entry: Omit<HistoryEntry, "id" | "at">) => void;
  clearHistory: () => void;

  // Prefs
  setPref: <K extends keyof Prefs>(key: K, value: Prefs[K]) => void;
}

const StoreContext = React.createContext<AppStore | null>(null);

export function AppStoreProvider({ children }: { children: React.ReactNode }) {
  const repo = React.useMemo(() => getBoxRepository(), []);

  const [ready, setReady] = React.useState(false);
  const [boxes, setBoxes] = React.useState<Box[]>([]);
  const [levels, setLevels] = React.useState<PackingLevelsConfig>(
    DEFAULT_PACKING_LEVELS
  );
  const [history, setHistory] = React.useState<HistoryEntry[]>([]);
  const [prefs, setPrefs] = React.useState<Prefs>(DEFAULT_PREFS);

  // Hydrate from storage once on the client.
  React.useEffect(() => {
    let active = true;
    (async () => {
      let list = await repo.list();
      if (list.length === 0) {
        list = await repo.bulkSet(getSeedBoxes());
      }
      if (!active) return;
      setBoxes(list);
      setLevels(normalizeLevels(readJson(STORAGE_KEYS.levels, null)));
      setHistory(readJson<HistoryEntry[]>(STORAGE_KEYS.history, []));
      setPrefs({ ...DEFAULT_PREFS, ...readJson(STORAGE_KEYS.prefs, {}) });
      setReady(true);
    })();
    return () => {
      active = false;
    };
  }, [repo]);

  // ---- Box CRUD ----
  const addBox = React.useCallback(
    async (input: NewBox) => {
      const created = await repo.create(input);
      setBoxes((prev) => [...prev, created]);
      return created;
    },
    [repo]
  );

  const updateBox = React.useCallback(
    async (id: string, patch: Partial<Omit<Box, "id">>) => {
      const updated = await repo.update(id, patch);
      setBoxes((prev) => prev.map((b) => (b.id === id ? updated : b)));
    },
    [repo]
  );

  const removeBox = React.useCallback(
    async (id: string) => {
      await repo.delete(id);
      setBoxes((prev) => prev.filter((b) => b.id !== id));
    },
    [repo]
  );

  const importBoxes = React.useCallback(
    async (drafts: ImportDraft[], mode: "append" | "replace") => {
      const created: Box[] = drafts.map((d) => ({
        ...d,
        id: genId("cust"),
        source: "custom",
        inStock: true,
      }));
      const next = mode === "replace" ? created : [...boxes, ...created];
      const saved = await repo.bulkSet(next);
      setBoxes(saved);
      return created.length;
    },
    [repo, boxes]
  );

  const resetCatalog = React.useCallback(async () => {
    const seeded = await repo.bulkSet(getSeedBoxes());
    setBoxes(seeded);
  }, [repo]);

  // ---- Levels ----
  const updateLevel = React.useCallback(
    (key: PackingLevelKey, patch: Partial<PackingLevel>) => {
      setLevels((prev) => {
        const next = prev.map((l) => (l.key === key ? { ...l, ...patch } : l));
        writeJson(STORAGE_KEYS.levels, next);
        return next;
      });
    },
    []
  );

  const resetLevels = React.useCallback(() => {
    setLevels(DEFAULT_PACKING_LEVELS);
    removeKey(STORAGE_KEYS.levels);
  }, []);

  // ---- History ----
  const addHistory = React.useCallback(
    (entry: Omit<HistoryEntry, "id" | "at">) => {
      setHistory((prev) => {
        const full: HistoryEntry = {
          ...entry,
          id: genId("hist"),
          at: new Date().toISOString(),
        };
        const next = [full, ...prev].slice(0, 100);
        writeJson(STORAGE_KEYS.history, next);
        return next;
      });
    },
    []
  );

  const clearHistory = React.useCallback(() => {
    setHistory([]);
    removeKey(STORAGE_KEYS.history);
  }, []);

  // ---- Prefs ----
  const setPref = React.useCallback(
    <K extends keyof Prefs>(key: K, value: Prefs[K]) => {
      setPrefs((prev) => {
        const next = { ...prev, [key]: value };
        writeJson(STORAGE_KEYS.prefs, next);
        return next;
      });
    },
    []
  );

  const value: AppStore = {
    ready,
    boxes,
    levels,
    history,
    prefs,
    addBox,
    updateBox,
    removeBox,
    importBoxes,
    resetCatalog,
    updateLevel,
    resetLevels,
    addHistory,
    clearHistory,
    setPref,
  };

  return (
    <StoreContext.Provider value={value}>{children}</StoreContext.Provider>
  );
}

export function useStore(): AppStore {
  const ctx = React.useContext(StoreContext);
  if (!ctx) {
    throw new Error("useStore must be used within <AppStoreProvider>");
  }
  return ctx;
}
