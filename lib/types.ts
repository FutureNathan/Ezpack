// ---------------------------------------------------------------------------
// EZ Pack — core domain model
// ---------------------------------------------------------------------------

/**
 * 'box' items are candidates for box-fit matching.
 * 'material' items (e.g. foam sheets) are stock you sell but never recommend
 * as a box — they are excluded from matching results.
 */
export type BoxCategory = "box" | "material";

/** The stable keys for the five packing levels. */
export type PackingLevelKey =
  | "box_only"
  | "standard"
  | "standard_plus"
  | "fragile"
  | "custom";

/** A box (or material) record. Dimensions are in inches, prices in USD. */
export interface Box {
  /** Generated, stable id. NOT the SKU — SKUs are not unique in the catalog. */
  id: string;
  /** Editable display name. If empty, the UI shows "L x W x H". */
  name: string;
  /** Editable category label, e.g. "UPS Box", "Custom Box" — informational. */
  type: string;
  length: number;
  width: number;
  height: number;
  /** Optional — some catalog rows have no weight. */
  weight?: number;
  /** The "Box only" price; tier prices derive from this + the level upcharge. */
  basePrice: number;
  /** Optional per-level manual price overrides (e.g. a per-job Custom price). */
  priceOverrides?: Partial<Record<PackingLevelKey, number>>;
  /** The "Show" toggle. Only in-stock boxes are matching candidates. */
  inStock: boolean;
  /** 'system' = seeded from the catalog, 'custom' = user-added. */
  source: "system" | "custom";
  /** Informational only, straight from the catalog. NOT unique. */
  sku?: string;
  category: BoxCategory;
}

/** A single packing level's editable configuration. */
export interface PackingLevel {
  key: PackingLevelKey;
  label: string;
  /** Added to a box's base price to get this level's price (USD). */
  upcharge: number;
  /** Clearance (inches) added to each item dimension when checking fit. */
  padding: number;
}

/** The ordered set of packing levels (global config, editable in Settings). */
export type PackingLevelsConfig = PackingLevel[];

/** Secondary sort for the result list. */
export type SortMode = "closest_small" | "cheapest";

/** A box scored against an item query. */
export interface FitResult {
  box: Box;
  /** Outer volume in cubic inches (sorted-dim product). */
  volume: number;
  /** True if the box fits the item plus the level's padding. */
  fits: boolean;
  /**
   * Tightest clearance beyond the required padding, in inches.
   * >= 0 when the box fits ("~1\" to spare"); negative when it doesn't.
   */
  slack: number;
  /** Total shortfall across dimensions, in inches. 0 when the box fits. */
  deficit: number;
  /** Price for the queried packing level (USD). */
  price: number;
  /** Per-(sorted)-dimension clearance beyond padding, for detailed display. */
  clearance: [number, number, number];
}

/** The full result of a match query. */
export interface MatchResult {
  /** Item dimensions sorted largest -> smallest. */
  itemDims: [number, number, number];
  level: PackingLevel;
  /** Up to 5 boxes that fit, ranked by the active sort mode. */
  fitting: FitResult[];
  /** When nothing fits: the smallest boxes that came closest (up to 5). */
  closest: FitResult[];
  anyFit: boolean;
}

/** A logged estimate (History view). */
export interface HistoryEntry {
  id: string;
  /** ISO timestamp. */
  at: string;
  length: number;
  width: number;
  height: number;
  level: PackingLevelKey;
  levelLabel: string;
  /** The chosen/recommended box, if any. */
  boxId?: string;
  boxName?: string;
  /** Outer dimensions string of the chosen box, e.g. "18 × 13 × 9". */
  boxDimensions?: string;
  /** Packing price for the level (USD). */
  price?: number;
}
