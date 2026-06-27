import { priceForLevel } from "./packing-levels";
import type {
  Box,
  FitResult,
  MatchResult,
  PackingLevel,
  SortMode,
} from "./types";

/** Sort three numbers largest -> smallest. */
export function sortDimsDesc(
  a: number,
  b: number,
  c: number
): [number, number, number] {
  return [a, b, c].sort((x, y) => y - x) as [number, number, number];
}

/**
 * Score a single box against an item.
 *
 * Rotation is handled by comparing *sorted* dimensions: an item's largest side
 * is checked against the box's largest side, etc. The catalog's own L/W/H
 * orientation is inconsistent, so we never compare height-to-height literally.
 */
export function scoreBox(
  box: Box,
  itemSorted: [number, number, number],
  level: PackingLevel
): FitResult {
  const [b1, b2, b3] = sortDimsDesc(box.length, box.width, box.height);
  const pad = level.padding;

  const need: [number, number, number] = [
    itemSorted[0] + pad,
    itemSorted[1] + pad,
    itemSorted[2] + pad,
  ];

  // Per-dimension clearance beyond the required padding (can be negative).
  const clearance: [number, number, number] = [
    b1 - need[0],
    b2 - need[1],
    b3 - need[2],
  ];

  const fits = clearance[0] >= 0 && clearance[1] >= 0 && clearance[2] >= 0;
  const slack = Math.min(clearance[0], clearance[1], clearance[2]);
  const deficit =
    Math.max(0, -clearance[0]) +
    Math.max(0, -clearance[1]) +
    Math.max(0, -clearance[2]);

  return {
    box,
    volume: b1 * b2 * b3,
    fits,
    slack,
    deficit,
    price: priceForLevel(box, level),
    clearance,
  };
}

/** Comparator for boxes that fit, by the active sort mode. */
function compareFitting(a: FitResult, b: FitResult, sort: SortMode): number {
  if (sort === "cheapest") {
    // Cheapest first by the selected level's price; tie-break smaller volume.
    if (a.price !== b.price) return a.price - b.price;
    return a.volume - b.volume;
  }
  // "closest_small" (default): smallest box volume first; tie-break lower base price.
  if (a.volume !== b.volume) return a.volume - b.volume;
  if (a.box.basePrice !== b.box.basePrice) {
    return a.box.basePrice - b.box.basePrice;
  }
  return a.price - b.price;
}

/**
 * The matching algorithm.
 *
 * Given an item's three dimensions and a packing level, returns up to the top 5
 * in-stock boxes that fit (ranked by `sort`), and — if nothing fits — the up-to-5
 * smallest boxes that came closest.
 *
 * Materials (e.g. foam sheets) and out-of-stock boxes are never candidates.
 */
export function findBoxes(
  boxes: Box[],
  item: { length: number; width: number; height: number },
  level: PackingLevel,
  sort: SortMode = "closest_small",
  limit = 5
): MatchResult {
  const itemSorted = sortDimsDesc(item.length, item.width, item.height);

  const candidates = boxes.filter(
    (b) => b.inStock && b.category === "box"
  );

  const scored = candidates.map((b) => scoreBox(b, itemSorted, level));

  const fitting = scored
    .filter((r) => r.fits)
    .sort((a, b) => compareFitting(a, b, sort))
    .slice(0, limit);

  // Closest non-fitting boxes: least total shortfall first, then smallest box.
  const closest = scored
    .filter((r) => !r.fits)
    .sort((a, b) => {
      if (a.deficit !== b.deficit) return a.deficit - b.deficit;
      return a.volume - b.volume;
    })
    .slice(0, limit);

  return {
    itemDims: itemSorted,
    level,
    fitting,
    closest,
    anyFit: fitting.length > 0,
  };
}
