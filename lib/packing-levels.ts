import type {
  Box,
  PackingLevel,
  PackingLevelKey,
  PackingLevelsConfig,
} from "./types";

/**
 * Default packing levels, derived from the mock:
 *   $5.99 base -> $5.99 / $7.98 / $9.99 / $15.99 / $25.99
 *
 * A level adds an `upcharge` to the box's base price, and requires `padding`
 * inches of clearance for fit (more protection = more void fill = bigger box).
 *
 * These are editable in Settings (both upcharge and padding).
 */
export const DEFAULT_PACKING_LEVELS: PackingLevelsConfig = [
  { key: "box_only", label: "Box only", upcharge: 0.0, padding: 0.5 },
  { key: "standard", label: "Standard", upcharge: 1.99, padding: 1.0 },
  { key: "standard_plus", label: "Standard+", upcharge: 4.0, padding: 2.0 },
  { key: "fragile", label: "Fragile", upcharge: 10.0, padding: 3.0 },
  { key: "custom", label: "Custom", upcharge: 20.0, padding: 4.0 },
];

export const PACKING_LEVEL_KEYS: PackingLevelKey[] =
  DEFAULT_PACKING_LEVELS.map((l) => l.key);

export function findLevel(
  levels: PackingLevelsConfig,
  key: PackingLevelKey
): PackingLevel {
  return (
    levels.find((l) => l.key === key) ??
    DEFAULT_PACKING_LEVELS.find((l) => l.key === key)!
  );
}

/**
 * Price for a box at a packing level.
 *   priceOverrides[level] ?? (basePrice + upcharge)
 */
export function priceForLevel(
  box: Pick<Box, "basePrice" | "priceOverrides">,
  level: PackingLevel
): number {
  const override = box.priceOverrides?.[level.key];
  if (override !== undefined && override !== null && !Number.isNaN(override)) {
    return override;
  }
  return round2(box.basePrice + level.upcharge);
}

/**
 * Reconcile a stored levels config with the canonical set: keep the five keys
 * in their canonical order and labels, but carry over any saved upcharge/padding
 * edits. Tolerates older/partial saved data.
 */
export function normalizeLevels(
  stored: Partial<PackingLevel>[] | null | undefined
): PackingLevelsConfig {
  return DEFAULT_PACKING_LEVELS.map((def) => {
    const saved = stored?.find((s) => s?.key === def.key);
    return {
      ...def,
      upcharge:
        typeof saved?.upcharge === "number" && !Number.isNaN(saved.upcharge)
          ? saved.upcharge
          : def.upcharge,
      padding:
        typeof saved?.padding === "number" && !Number.isNaN(saved.padding)
          ? saved.padding
          : def.padding,
    };
  });
}

/** Round to cents, avoiding binary-float noise (e.g. 5.99 + 1.99). */
export function round2(n: number): number {
  return Math.round((n + Number.EPSILON) * 100) / 100;
}

/** Format a USD amount, e.g. 7.98 -> "$7.98". */
export function formatUsd(n: number): string {
  return `$${n.toFixed(2)}`;
}
