/**
 * Minimal CSV parser (RFC 4180-ish). Supports double-quoted fields (with
 * escaped ""), empty fields, and a trailing newline.
 *
 * Importantly, a double-quote only opens a quoted field when it is the FIRST
 * character of the field. A `"` anywhere else is a literal — the seed catalog
 * uses it as an inch mark in names (e.g. `30" Mirror`, `1" Foam Sheet`), and
 * those must not be treated as quoting.
 */
export function parseCsv(text: string): string[][] {
  const rows: string[][] = [];
  let field = "";
  let row: string[] = [];
  let inQuotes = false;
  let fieldStart = true;

  for (let i = 0; i < text.length; i++) {
    const ch = text[i];

    if (inQuotes) {
      if (ch === '"') {
        if (text[i + 1] === '"') {
          field += '"';
          i++;
        } else {
          inQuotes = false;
        }
      } else {
        field += ch;
      }
      continue;
    }

    if (ch === '"' && fieldStart) {
      inQuotes = true;
      fieldStart = false;
    } else if (ch === ",") {
      row.push(field);
      field = "";
      fieldStart = true;
    } else if (ch === "\n") {
      row.push(field);
      rows.push(row);
      field = "";
      row = [];
      fieldStart = true;
    } else if (ch === "\r") {
      // ignore; handled by \n
    } else {
      field += ch;
      fieldStart = false;
    }
  }

  // Flush the last field/row if the file didn't end with a newline.
  if (field.length > 0 || row.length > 0) {
    row.push(field);
    rows.push(row);
  }

  return rows;
}

/** Parse CSV into an array of objects keyed by the header row. */
export function parseCsvRecords(text: string): Record<string, string>[] {
  const rows = parseCsv(text).filter((r) => r.some((c) => c.trim() !== ""));
  if (rows.length === 0) return [];
  const header = rows[0].map((h) => h.trim());
  return rows.slice(1).map((cells) => {
    const rec: Record<string, string> = {};
    header.forEach((key, idx) => {
      rec[key] = (cells[idx] ?? "").trim();
    });
    return rec;
  });
}
