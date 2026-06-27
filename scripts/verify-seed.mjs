#!/usr/bin/env node
/**
 * Sanity-check the seed catalog:
 *  1. data/boxes-data.ts embeds data/boxes.csv byte-for-byte (no drift).
 *  2. Every row parses (numeric dims + basePrice, category in {box,material}).
 *  3. Report duplicate SKUs (a known upstream quirk we must tolerate).
 *
 * Run: npm run verify:seed
 */
import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";
import { dirname, join } from "node:path";

const root = join(dirname(fileURLToPath(import.meta.url)), "..");
const csv = readFileSync(join(root, "data", "boxes.csv"), "utf8");
const ts = readFileSync(join(root, "data", "boxes-data.ts"), "utf8");

let failures = 0;
const fail = (m) => {
  console.error("  ✗", m);
  failures++;
};
const ok = (m) => console.log("  ✓", m);

// 1. Parity ----------------------------------------------------------------
const m = ts.match(/export const BOXES_CSV = `([\s\S]*)`;\n$/);
if (!m) {
  fail("boxes-data.ts does not match the expected export shape");
} else {
  const embedded = m[1]
    .replace(/\\`/g, "`")
    .replace(/\\\$\{/g, "${")
    .replace(/\\\\/g, "\\");
  if (embedded === csv) ok("boxes-data.ts is byte-identical to boxes.csv");
  else fail("boxes-data.ts is OUT OF SYNC — run scripts/generate-seed-mirror.mjs");
}

// 2. Row validity ----------------------------------------------------------
const lines = csv.split("\n").filter((l) => l.trim() !== "");
const expectedHeader = "name,length,width,height,weight,basePrice,sku,category";
if (lines[0] === expectedHeader) ok("header matches the documented schema");
else fail(`unexpected header: ${lines[0]}`);

const rows = lines.slice(1).map((l) => l.split(","));
let boxes = 0;
let materials = 0;
const skuCounts = new Map();

for (const [i, cells] of rows.entries()) {
  const [name, length, width, height, weight, basePrice, sku, category] = cells;
  const rowNo = i + 2;
  for (const [label, v] of [
    ["length", length],
    ["width", width],
    ["height", height],
    ["basePrice", basePrice],
  ]) {
    if (v === "" || Number.isNaN(Number(v))) {
      fail(`row ${rowNo} (${name || "<unnamed>"}): ${label} is not numeric ("${v}")`);
    }
  }
  if (weight !== "" && Number.isNaN(Number(weight))) {
    fail(`row ${rowNo} (${name || "<unnamed>"}): weight is non-numeric ("${weight}")`);
  }
  if (category !== "box" && category !== "material") {
    fail(`row ${rowNo}: category must be box|material, got "${category}"`);
  }
  if (category === "material") materials++;
  else boxes++;
  if (sku) skuCounts.set(sku, (skuCounts.get(sku) ?? 0) + 1);
}

ok(`${rows.length} rows parsed: ${boxes} boxes, ${materials} materials`);

const dupes = [...skuCounts.entries()].filter(([, n]) => n > 1);
if (dupes.length) {
  console.log(
    `  ℹ duplicate SKUs (expected upstream quirk — never used as identity): ` +
      dupes.map(([s, n]) => `${s}×${n}`).join(", ")
  );
}

if (failures) {
  console.error(`\nverify:seed FAILED with ${failures} problem(s).`);
  process.exit(1);
}
console.log("\nverify:seed passed.");
