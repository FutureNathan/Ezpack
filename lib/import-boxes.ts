import { parseCsv } from "./csv";
import { formatInches } from "./box-display";
import type {
  Box,
  BoxCategory,
  PackingLevelKey,
  PackingLevelsConfig,
} from "./types";

/** A validated box ready to be persisted (id/source added by the store). */
export interface ImportDraft {
  name: string;
  type: string;
  length: number;
  width: number;
  height: number;
  weight?: number;
  basePrice: number;
  sku?: string;
  category: BoxCategory;
  priceOverrides?: Partial<Record<PackingLevelKey, number>>;
}

export interface ImportResult {
  drafts: ImportDraft[];
  errors: { line: number; reason: string }[];
  /** header label -> field it mapped to (for the preview) */
  mapped: Record<string, string>;
  unmapped: string[];
}

/** Normalize a header cell to a comparison key: lowercase, alphanumerics only. */
function norm(h: string): string {
  return h.toLowerCase().replace(/[^a-z0-9]/g, "");
}

// Header synonyms -> canonical field.
const FIELD_SYNONYMS: Record<string, string[]> = {
  name: ["name", "boxname", "box", "description", "desc", "item", "itemname"],
  length: ["length", "l", "len", "long", "lengthin"],
  width: ["width", "w", "wide", "widthin"],
  height: ["height", "h", "ht", "depth", "d", "deep", "tall", "heightin"],
  weight: ["weight", "wt", "lbs", "lb", "pounds", "weightlbs"],
  basePrice: [
    "baseprice",
    "price",
    "base",
    "boxonly",
    "boxonlyprice",
    "cost",
    "boxprice",
    "boxcost",
  ],
  sku: ["sku", "code", "itemnumber", "itemno", "partnumber", "part"],
  type: ["type", "typelabel", "kind", "boxtype"],
  category: ["category", "class", "categoryboxmaterial"],
};

// Per-level override columns (box_only maps to basePrice above).
const LEVEL_SYNONYMS: Record<PackingLevelKey, string[]> = {
  box_only: [],
  standard: ["standard", "standardprice", "std"],
  standard_plus: ["standardplus", "standardpluspr", "stdplus", "splus"],
  fragile: ["fragile", "fragileprice"],
  custom: ["custom", "customprice"],
};

function buildHeaderMap(headers: string[]) {
  const fieldOf: Record<number, string> = {};
  const levelOf: Record<number, PackingLevelKey> = {};
  const mapped: Record<string, string> = {};
  const unmapped: string[] = [];

  headers.forEach((raw, i) => {
    const key = norm(raw);
    let found: string | null = null;

    for (const [field, syns] of Object.entries(FIELD_SYNONYMS)) {
      if (syns.includes(key)) {
        fieldOf[i] = field;
        found = field;
        break;
      }
    }
    if (!found) {
      for (const [level, syns] of Object.entries(LEVEL_SYNONYMS) as [
        PackingLevelKey,
        string[],
      ][]) {
        if (syns.includes(key)) {
          levelOf[i] = level;
          found = `${level} price`;
          break;
        }
      }
    }

    if (found) mapped[raw || `(column ${i + 1})`] = found;
    else if (raw.trim() !== "") unmapped.push(raw);
  });

  return { fieldOf, levelOf, mapped, unmapped };
}

function toNumber(v: string | undefined): number | null {
  if (v == null) return null;
  const t = v.trim().replace(/[$,]/g, "");
  if (t === "") return null;
  const n = Number(t);
  return Number.isFinite(n) ? n : null;
}

/**
 * Parse an uploaded CSV (a store's own box list) into validated drafts.
 *
 * Flexible about headers (length/L, basePrice/price/box only, etc.). Rows need
 * three positive numeric dimensions; base price defaults to 0 (a store may own
 * boxes it doesn't charge for). Category defaults to "box"; rows that look like
 * material/foam become "material". Optional per-tier price columns become
 * priceOverrides.
 */
export function parseBoxImport(
  csvText: string,
  _levels?: PackingLevelsConfig
): ImportResult {
  const rows = parseCsv(csvText).filter((r) => r.some((c) => c.trim() !== ""));
  const errors: ImportResult["errors"] = [];

  if (rows.length === 0) {
    return { drafts: [], errors: [{ line: 0, reason: "File is empty." }], mapped: {}, unmapped: [] };
  }

  const headers = rows[0];
  const { fieldOf, levelOf, mapped, unmapped } = buildHeaderMap(headers);

  const hasDims =
    Object.values(fieldOf).includes("length") &&
    Object.values(fieldOf).includes("width") &&
    Object.values(fieldOf).includes("height");

  if (!hasDims) {
    return {
      drafts: [],
      errors: [
        {
          line: 1,
          reason:
            "Could not find Length, Width and Height columns. Expected a header row like: name,length,width,height,weight,basePrice,sku,category",
        },
      ],
      mapped,
      unmapped,
    };
  }

  const drafts: ImportDraft[] = [];

  for (let r = 1; r < rows.length; r++) {
    const cells = rows[r];
    const line = r + 1;
    const get = (field: string): string | undefined => {
      const idx = Object.keys(fieldOf).find((i) => fieldOf[Number(i)] === field);
      return idx != null ? cells[Number(idx)] : undefined;
    };

    const length = toNumber(get("length"));
    const width = toNumber(get("width"));
    const height = toNumber(get("height"));

    if (length == null || width == null || height == null) {
      errors.push({ line, reason: "missing or non-numeric length/width/height" });
      continue;
    }
    if (length <= 0 || width <= 0 || height <= 0) {
      errors.push({ line, reason: "dimensions must be greater than 0" });
      continue;
    }

    const name = (get("name") ?? "").trim();
    const rawCategory = (get("category") ?? "").trim().toLowerCase();
    const category: BoxCategory =
      rawCategory === "material" || /foam|sheet|bubble|wrap/.test(rawCategory)
        ? "material"
        : "box";

    const basePrice = toNumber(get("basePrice")) ?? 0;
    const weight = toNumber(get("weight")) ?? undefined;
    const sku = (get("sku") ?? "").trim() || undefined;
    const type =
      (get("type") ?? "").trim() ||
      (category === "material" ? "Material" : "Custom Box");

    // Per-tier overrides.
    const overrides: Partial<Record<PackingLevelKey, number>> = {};
    for (const [idxStr, level] of Object.entries(levelOf)) {
      const n = toNumber(cells[Number(idxStr)]);
      if (n != null) overrides[level] = n;
    }

    drafts.push({
      name,
      type,
      length,
      width,
      height,
      weight,
      basePrice,
      sku,
      category,
      priceOverrides: Object.keys(overrides).length ? overrides : undefined,
    });
  }

  return { drafts, errors, mapped, unmapped };
}

// ---------------------------------------------------------------------------
// Export (so a store can download, edit in a spreadsheet, and re-import)
// ---------------------------------------------------------------------------

const EXPORT_HEADER = [
  "name",
  "length",
  "width",
  "height",
  "weight",
  "basePrice",
  "sku",
  "category",
  "standard",
  "standard_plus",
  "fragile",
  "custom",
];

function csvCell(value: string): string {
  // Quote only when needed (comma, quote, or newline).
  if (/[",\n]/.test(value)) return `"${value.replace(/"/g, '""')}"`;
  return value;
}

/** Serialize boxes to a re-importable CSV string. */
export function boxesToCsv(boxes: Box[]): string {
  const lines = [EXPORT_HEADER.join(",")];
  for (const b of boxes) {
    const o = b.priceOverrides ?? {};
    const row = [
      b.name ?? "",
      formatInches(b.length),
      formatInches(b.width),
      formatInches(b.height),
      b.weight != null ? formatInches(b.weight) : "",
      String(b.basePrice),
      b.sku ?? "",
      b.category,
      o.standard != null ? String(o.standard) : "",
      o.standard_plus != null ? String(o.standard_plus) : "",
      o.fragile != null ? String(o.fragile) : "",
      o.custom != null ? String(o.custom) : "",
    ];
    lines.push(row.map((c) => csvCell(c)).join(","));
  }
  return lines.join("\n") + "\n";
}

/** A small example CSV a store can fill in. */
export function importTemplateCsv(): string {
  return [
    EXPORT_HEADER.join(","),
    "My Small Box,12,9,4,1,3.50,SB-001,box",
    "My Large Box,24,18,12,3,7.00,LB-001,box",
    '36" Mirror Box,36,36,6,6,16.99,MIR-36,box',
    "1in Foam Sheet,24,1,48,,12.99,FOAM-1,material",
  ].join("\n") + "\n";
}
