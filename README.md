# EZ Pack

A fast packing-estimator for a pack-and-ship counter. A customer hands the
worker an item; the worker needs three things in seconds:

1. **the best box** to pack it in,
2. **the packing price** for the chosen level of protection, and
3. **the outer dimensions + weight** to type into their carrier's shipping
   software (UPS/FedEx/etc.) so that software can quote the shipping rate.

The north star is **speed and minimal clicks**: type three dimensions, pick a
packing level, instantly get the answer with a one-tap **Copy dimensions**
button.

> **Out of scope (by design):** live carrier shipping-rate APIs, accounts/auth,
> payments, and any multi-user/server backend. EZ Pack does **not** compute the
> carrier's shipping cost — it surfaces the box's outer dimensions and weight
> for the worker to paste into their existing shipping software. **"Estimate the
> price" here means the _packing_ cost (box + packing service), not the carrier
> rate.**

---

## Run it

Requires Node 20+ (developed on Node 22).

```bash
npm install
npm run dev
# open http://localhost:3000
```

Production build:

```bash
npm run build
npm start
```

Other scripts:

| Command | What it does |
| --- | --- |
| `npm run dev` | Dev server (Turbopack) at `localhost:3000` |
| `npm run build` | Production build |
| `npm start` | Serve the production build |
| `npm run lint` | ESLint |
| `npm run typecheck` | `tsc --noEmit` |
| `npm run gen:seed` | Regenerate `data/boxes-data.ts` from `data/boxes.csv` |
| `npm run verify:seed` | Parse the seed catalog with the real parser and sanity-check it |

First load seeds the catalog from `data/boxes.csv` into `localStorage`. To start
fresh, use **Settings → Reset to seeded catalog**, or clear site data.

---

## Stack

- **Next.js 16** (App Router) + **React 19** + **TypeScript**
- **Tailwind CSS v4** + **shadcn/ui** (Radix primitives, `class-variance-authority`)
- **Persistence:** browser `localStorage`, behind a swappable repository interface
- **Deploy target:** Vercel (static — every route prerenders; all logic is client-side)

### Project layout

```
app/                 # routes: / (Find Box), /inventory, /settings, /history, /billing, /support
components/
  app-shell.tsx      # top bar + tab nav
  copy-button.tsx    # one-tap clipboard copy
  providers/         # AppStoreProvider — localStorage-backed state
  find-box/          # estimate screen
  inventory/         # inventory CRUD
  settings/ history/ # settings + history
  ui/                # shadcn/ui primitives
lib/
  types.ts           # domain model
  matching.ts        # the box-fit algorithm
  packing-levels.ts  # level config + pricing
  repository.ts      # BoxRepository + Local/Supabase implementations
  seed.ts  csv.ts    # catalog seeding + CSV parsing
  storage.ts         # SSR-safe localStorage helpers
data/
  boxes.csv          # canonical seed catalog (human-editable)
  boxes-data.ts      # generated mirror of boxes.csv for the browser bundle
scripts/             # seed generation + verification
```

---

## Data model

```ts
type BoxCategory = "box" | "material"; // 'material' (e.g. foam) is excluded from matching

type PackingLevelKey =
  | "box_only" | "standard" | "standard_plus" | "fragile" | "custom";

interface Box {
  id: string;            // generated, stable — NOT the SKU (SKUs are not unique)
  name: string;          // editable; if empty, shown as "L x W x H"
  type: string;          // editable label, e.g. "UPS Box" — informational
  length: number;        // inches
  width: number;
  height: number;
  weight?: number;       // lbs, optional
  basePrice: number;     // the "Box only" price
  priceOverrides?: Partial<Record<PackingLevelKey, number>>; // per-level manual price
  inStock: boolean;      // the "Show" toggle — only in-stock boxes match
  source: "system" | "custom"; // seeded vs user-added
  sku?: string;          // informational only, from the catalog; NOT unique
  category: BoxCategory;
}
```

### Packing levels (global config, editable in Settings)

A level adds an **upcharge** to the box's base price and requires a **padding**
clearance for fit (more protection → more void fill → a bigger box). Defaults:

| Key | Label | Upcharge | Padding |
| --- | --- | --- | --- |
| `box_only` | Box only | $0.00 | 0.5″ |
| `standard` | Standard | $1.99 | 1.0″ |
| `standard_plus` | Standard+ | $4.00 | 1.5″ |
| `fragile` | Fragile | $10.00 | 2.0″ |
| `custom` | Custom | $20.00 | 2.0″ |

**Price for a level** = `priceOverrides[level] ?? (basePrice + upcharge)`. Custom
is meant to be tuned per job, so the estimate screen lets you override its price
inline. Upcharges and paddings are all editable in **Settings**.

### Seed catalog

`data/boxes.csv` is the canonical, human-editable catalog. Because the browser
bundle can't read the filesystem, `data/boxes-data.ts` mirrors its exact bytes
for client-side seeding — regenerate it with `npm run gen:seed` after editing the
CSV (`npm run verify:seed` checks parity and parses with the real parser).

Notes baked into the seeding routine (`lib/seed.ts`):

- **ids are generated and stable** (`sys-NNN` by row order), never the SKU — SKU
  `10158` intentionally appears on two different boxes upstream.
- The **24″ and 26″ cube weights** (seeded as `1`) are almost certainly truncated
  from ~10–11 lb. They're seeded as-is (we don't invent data) but flagged in
  Inventory with a warning for a human to verify. See the `TODO: verify weight`.
- Rows with an **empty name** display as their dimensions; the two **foam sheets**
  are `material`, so they never appear as box recommendations.

---

## The matching algorithm

Implemented in [`lib/matching.ts`](lib/matching.ts). Given an item's three
dimensions and a packing level:

1. Let `pad = level.padding` (clearance added to **each** dimension — not per side).
2. Sort the item's three dimensions largest → smallest: `[I1, I2, I3]`.
3. For each box where `inStock === true` **and** `category === "box"`:
   - Sort the box's three dimensions largest → smallest: `[B1, B2, B3]`.
   - The box **fits** if `B1 ≥ I1+pad` **and** `B2 ≥ I2+pad` **and** `B3 ≥ I3+pad`.
   - Comparing _sorted_ dimensions handles rotation automatically (a 14×10×8 item
     is checked against an 8×10×14 box correctly). The catalog's own L/W/H
     orientation is inconsistent, so we never compare height-to-height literally.
4. **Rank** the boxes that fit:
   - **Default — "Closest small":** smallest box volume first (`B1·B2·B3`
     ascending), tie-break by lower base price. This surfaces the tightest fit.
   - **Toggle — "Cheapest":** by the selected level's price ascending.
5. Return the **top 5**; the #1 is emphasized. If **nothing fits**, say so clearly
   and show the smallest boxes that came **closest** (least total shortfall first).

The UI also reports the **fit slack** — the tightest clearance beyond the
required padding (e.g. _"fits with ~1″ to spare"_, or _"exact fit"_).

Worked example — item **14 × 10 × 8**, **Standard** (pad 1.0″ → need
`[15, 11, 9]`): the smallest fitting in-stock box is **Dyson (15 × 12 × 10)**,
volume 1800 in³, at **$8.98** (`6.99 + 1.99`). Hide it in Inventory and the next
smallest, **J 20 (18 × 13 × 9)**, takes over.

---

## Swapping localStorage for Supabase later

All persistence goes through the `BoxRepository` interface in
[`lib/repository.ts`](lib/repository.ts):

```ts
interface BoxRepository {
  list(): Promise<Box[]>;
  get(id: string): Promise<Box | null>;
  create(input: NewBox): Promise<Box>;
  update(id: string, patch: Partial<Omit<Box, "id">>): Promise<Box>;
  delete(id: string): Promise<void>;
  bulkSet(boxes: Box[]): Promise<Box[]>; // seed / reset
  clear(): Promise<void>;
}
```

Two implementations ship today:

- `LocalStorageBoxRepository` — used now.
- `SupabaseBoxRepository` — a **typed stub**: every method throws
  `"not implemented"`, so the migration is purely mechanical.

The whole app talks only to `getBoxRepository()`, which is the single swap point:

```ts
// lib/repository.ts
export function getBoxRepository(): BoxRepository {
  if (!boxRepo) {
    boxRepo = new LocalStorageBoxRepository();
    // To migrate later: boxRepo = new SupabaseBoxRepository();
  }
  return boxRepo;
}
```

To move to Supabase: create a `boxes` table matching the `Box` shape, implement
the `SupabaseBoxRepository` method bodies against the Supabase client, and flip
that one line. Everything is already `async`, so callers don't change. (Settings
and history use the same `localStorage` boundary in `lib/storage.ts` and can move
the same way.)

---

## What's built vs. stubbed

**Built:** Find Box (live matching, copy, level selector, sort toggle, Custom
price override), Inventory (search, show/hide, inline editing, add/remove, tier
prices, suspect-weight flags), Settings (editable upcharges/paddings, reset
catalog), History (logged on dimension-copy).

**Stubbed:** Billing and Support are intentional visible-but-empty placeholders.

**Never built (out of scope):** carrier shipping-rate lookups, auth, payments, a
server backend.
