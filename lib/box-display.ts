import type { Box } from "./types";

const TIMES = "×"; // ×

/** Dimensions string, e.g. "18 × 13 × 9". `sep` defaults to " × ". */
export function dimsString(
  d: { length: number; width: number; height: number },
  sep = ` ${TIMES} `
): string {
  return [d.length, d.width, d.height].map(formatInches).join(sep);
}

/** Plain, paste-friendly dimensions, e.g. "18x13x9" for shipping software. */
export function dimsPlain(d: {
  length: number;
  width: number;
  height: number;
}): string {
  return [d.length, d.width, d.height].map(formatInches).join("x");
}

/** Display name: the box name, or its dimensions if the name is empty. */
export function displayName(box: Box): string {
  const name = box.name?.trim();
  return name && name.length > 0 ? name : dimsString(box);
}

/** Trim trailing zeros from inch values (e.g. 6.0 -> "6", 6.5 -> "6.5"). */
export function formatInches(n: number): string {
  if (Number.isInteger(n)) return String(n);
  return String(Number(n.toFixed(2)));
}

/** "fits with ~1″ to spare" style copy from the tightest clearance. */
export function slackLabel(slack: number): string {
  const rounded = Math.round(slack * 10) / 10;
  if (rounded <= 0) return "exact fit (no extra room)";
  return `fits with ~${formatInches(rounded)}″ to spare`;
}
