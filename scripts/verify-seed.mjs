#!/usr/bin/env node
// Run with: node --experimental-strip-types scripts/verify-seed.mjs
//           (wired as `npm run verify:seed`)
/**
 * Sanity-check the seed catalog using the REAL parser (lib/csv.ts):
 *  1. data/boxes-data.ts embeds data/boxes.csv byte-for-byte (no drift).
 *  2. The quote-aware parser yields one record per physical data line — guards
 *     against the inch-mark-in-name bug (`30" Mirror`, `1" Foam Sheet`) where a
 *     naive parser would treat `"` as CSV quoting and merge/drop rows.
 *  3. Every row has numeric dims + basePrice and a valid category.
 *  4. Report duplicate SKUs (a known upstream quirk we must tolerate).
 */
import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";
import { dirname, join } from "node:path";
import { parseCsvRecords } from "../lib/csv.ts";

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

// 2. One parsed record per physical data line ------------------------------
const physicalLines = csv.split("\n").filter((l) => l.trim() !== "");
const expectedRecords = physicalLines.length - 1; // minus header
const records = parseCsvRecords(csv);
if (records.length === expectedRecords) {
  ok(`parser yields one record per data line (${records.length})`);
} else {
  fail(
    `parser produced ${records.length} records but the file has ` +
      `${expectedRecords} data lines — rows are being merged or dropped`
  );
}

if (physicalLines[0] === "name,length,width,height,weight,basePrice,sku,category") {
  ok("header matches the documented schema");
} else {
  fail(`unexpected header: ${physicalLines[0]}`);
}

// 3. Row validity ----------------------------------------------------------
let boxes = 0;
let materials = 0;
const skuCounts = new Map();

records.forEach((r, i) => {
  const rowNo = i + 2;
  for (const key of ["length", "width", "height", "basePrice"]) {
    if (r[key] === "" || Number.isNaN(Number(r[key]))) {
      fail(`row ${rowNo} (${r.name || "<unnamed>"}): ${key} is not numeric ("${r[key]}")`);
    }
  }
  if (r.weight !== "" && Number.isNaN(Number(r.weight))) {
    fail(`row ${rowNo} (${r.name || "<unnamed>"}): weight is non-numeric ("${r.weight}")`);
  }
  if (r.category !== "box" && r.category !== "material") {
    fail(`row ${rowNo}: category must be box|material, got "${r.category}"`);
  }
  if (r.category === "material") materials++;
  else boxes++;
  if (r.sku) skuCounts.set(r.sku, (skuCounts.get(r.sku) ?? 0) + 1);
});

ok(`${records.length} records: ${boxes} boxes, ${materials} materials`);

const mirrors = records.filter((r) => /Mirror/.test(r.name)).length;
const foams = records.filter((r) => /Foam/.test(r.name)).length;
if (mirrors === 4) ok('inch-mark names preserved (4 "× Mirror" rows)');
else fail(`expected 4 Mirror rows, got ${mirrors}`);
if (foams === 2) ok("both foam-sheet materials present");
else fail(`expected 2 Foam Sheet rows, got ${foams}`);

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
