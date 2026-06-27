import type { Box } from "./types";
import {
  STORAGE_KEYS,
  genId,
  isBrowser,
  readJson,
  removeKey,
  writeJson,
} from "./storage";

/** Input for creating a box. `id` and `source` are filled in if omitted. */
export type NewBox = Omit<Box, "id" | "source"> & {
  id?: string;
  source?: Box["source"];
};

/**
 * The persistence boundary for boxes.
 *
 * The app talks only to this interface, so swapping localStorage for a real
 * backend (Supabase, an API, etc.) is a one-line change in `getBoxRepository()`.
 * Everything is async so async backends drop in without touching callers.
 */
export interface BoxRepository {
  /** All boxes (system + custom). */
  list(): Promise<Box[]>;
  get(id: string): Promise<Box | null>;
  create(input: NewBox): Promise<Box>;
  update(id: string, patch: Partial<Omit<Box, "id">>): Promise<Box>;
  delete(id: string): Promise<void>;

  // --- Bulk helpers used for seeding and "reset to seeded catalog" ---
  /** Replace the entire collection (used to seed or reset). */
  bulkSet(boxes: Box[]): Promise<Box[]>;
  /** Remove everything. */
  clear(): Promise<void>;
}

// ---------------------------------------------------------------------------
// localStorage implementation (the one used now)
// ---------------------------------------------------------------------------

export class LocalStorageBoxRepository implements BoxRepository {
  constructor(private readonly key: string = STORAGE_KEYS.boxes) {}

  private read(): Box[] {
    return readJson<Box[]>(this.key, []);
  }

  private write(boxes: Box[]): Box[] {
    writeJson(this.key, boxes);
    return boxes;
  }

  async list(): Promise<Box[]> {
    return this.read();
  }

  async get(id: string): Promise<Box | null> {
    return this.read().find((b) => b.id === id) ?? null;
  }

  async create(input: NewBox): Promise<Box> {
    const box: Box = {
      ...input,
      id: input.id ?? genId("cust"),
      source: input.source ?? "custom",
    };
    const boxes = this.read();
    boxes.push(box);
    this.write(boxes);
    return box;
  }

  async update(id: string, patch: Partial<Omit<Box, "id">>): Promise<Box> {
    const boxes = this.read();
    const idx = boxes.findIndex((b) => b.id === id);
    if (idx === -1) throw new Error(`Box not found: ${id}`);
    const updated: Box = { ...boxes[idx], ...patch, id };
    boxes[idx] = updated;
    this.write(boxes);
    return updated;
  }

  async delete(id: string): Promise<void> {
    this.write(this.read().filter((b) => b.id !== id));
  }

  async bulkSet(boxes: Box[]): Promise<Box[]> {
    return this.write([...boxes]);
  }

  async clear(): Promise<void> {
    removeKey(this.key);
  }
}

// ---------------------------------------------------------------------------
// Supabase implementation (stub — typed, throws "not implemented")
// ---------------------------------------------------------------------------

/**
 * Swappable backend target. Every method is typed to match BoxRepository so the
 * future migration is purely mechanical: implement the bodies against a
 * Supabase client and point `getBoxRepository()` here. See README → "Swapping
 * localStorage for Supabase".
 */
export class SupabaseBoxRepository implements BoxRepository {
  private notImplemented(method: string): never {
    throw new Error(
      `SupabaseBoxRepository.${method}() is not implemented yet. ` +
        `This is a typed stub so the storage swap is obvious — implement it ` +
        `against a Supabase 'boxes' table when moving off localStorage.`
    );
  }

  async list(): Promise<Box[]> {
    this.notImplemented("list");
  }
  async get(_id: string): Promise<Box | null> {
    this.notImplemented("get");
  }
  async create(_input: NewBox): Promise<Box> {
    this.notImplemented("create");
  }
  async update(_id: string, _patch: Partial<Omit<Box, "id">>): Promise<Box> {
    this.notImplemented("update");
  }
  async delete(_id: string): Promise<void> {
    this.notImplemented("delete");
  }
  async bulkSet(_boxes: Box[]): Promise<Box[]> {
    this.notImplemented("bulkSet");
  }
  async clear(): Promise<void> {
    this.notImplemented("clear");
  }
}

// ---------------------------------------------------------------------------
// Selection point — change this one line to swap the backend.
// ---------------------------------------------------------------------------

let boxRepo: BoxRepository | null = null;

export function getBoxRepository(): BoxRepository {
  if (!boxRepo) {
    boxRepo = new LocalStorageBoxRepository();
    // To migrate later: boxRepo = new SupabaseBoxRepository();
  }
  return boxRepo;
}

/** Test/SSR helper to inject a repository. */
export function setBoxRepository(repo: BoxRepository): void {
  boxRepo = repo;
}

export { isBrowser };
