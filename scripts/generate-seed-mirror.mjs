#!/usr/bin/env node
/**
 * Regenerate data/boxes-data.ts from data/boxes.csv.
 *
 * data/boxes.csv is the canonical seed catalog. The browser bundle can't read
 * the filesystem, so we embed the CSV's exact bytes as a string export that the
 * seeding routine (lib/seed.ts) parses. Run this whenever boxes.csv changes:
 *
 *   node scripts/generate-seed-mirror.mjs
 */
import { readFileSync, writeFileSync } from "node:fs";
import { fileURLToPath } from "node:url";
import { dirname, join } from "node:path";

const root = join(dirname(fileURLToPath(import.meta.url)), "..");
const csvPath = join(root, "data", "boxes.csv");
const outPath = join(root, "data", "boxes-data.ts");

const csv = readFileSync(csvPath, "utf8");
const escaped = csv
  .replace(/\\/g, "\\\\")
  .replace(/`/g, "\\`")
  .replace(/\$\{/g, "\\${");

const out = `// AUTO-GENERATED from data/boxes.csv — do not edit by hand.
//
// data/boxes.csv is the canonical, human-editable seed catalog. This module
// re-exports its exact bytes as a string so the seeding routine can parse it
// in the browser bundle without filesystem access or a CSV loader. Regenerate
// with:  node scripts/generate-seed-mirror.mjs
//
// (Parity with boxes.csv is checked by npm run verify:seed.)

export const BOXES_CSV = \`${escaped}\`;
`;

writeFileSync(outPath, out);
console.log(`Wrote ${outPath} from ${csvPath}`);
