import { BOXES_CSV } from "@/data/boxes-data";
import { parseCsvRecords } from "./csv";
import type { Box, BoxCategory } from "./types";

/**
 * SKUs that need their weight double-checked before being trusted downstream.
 *
 * TODO: verify weight — a 24" and a 26" cube almost certainly weigh more than
 * 1 lb (the catalog value looks truncated from ~10–11 lb). We seed the value
 * as-is so we don't invent data, but flag it so a human can confirm. The flag
 * surfaces a small warning in Inventory; it does not affect matching.
 */
export const SUSPECT_WEIGHT_SKUS = new Set(["10011", "10025"]); // 24 Cube, 26 Cube

function num(value: string): number {
  return Number(value);
}

function optionalNum(value: string): number | undefined {
  if (value === "" || value == null) return undefined;
  const n = Number(value);
  return Number.isNaN(n) ? undefined : n;
}

/** Default informational type label for a seeded box. Fully editable later. */
function defaultType(name: string, category: BoxCategory): string {
  if (category === "material") return "Material";
  if (/^ups\b/i.test(name)) return "UPS Box";
  return "Box";
}

/**
 * Parse the seed CSV into Box records.
 *
 * - ids are generated and stable (`sys-NNN` by row order) — never the SKU,
 *   which is not unique (e.g. 10158 appears on two different boxes).
 * - source: 'system', inStock: true by default.
 * - category comes from the last column.
 * - weight and sku are optional.
 */
export function parseSeedCsv(csv: string): Box[] {
  const records = parseCsvRecords(csv);

  return records.map((r, i) => {
    const category: BoxCategory = r.category === "material" ? "material" : "box";
    const name = r.name ?? "";
    const sku = r.sku && r.sku !== "" ? r.sku : undefined;

    return {
      id: `sys-${String(i + 1).padStart(3, "0")}`,
      name,
      type: defaultType(name, category),
      length: num(r.length),
      width: num(r.width),
      height: num(r.height),
      weight: optionalNum(r.weight),
      basePrice: num(r.basePrice),
      inStock: true,
      source: "system",
      sku,
      category,
    } satisfies Box;
  });
}

/** The seeded catalog, parsed from the canonical CSV. */
export function getSeedBoxes(): Box[] {
  return parseSeedCsv(BOXES_CSV);
}

/** True if a box's seeded weight is flagged for verification. */
export function hasSuspectWeight(box: Box): boolean {
  return box.source === "system" && !!box.sku && SUSPECT_WEIGHT_SKUS.has(box.sku);
}
