"use client";

import * as React from "react";
import { ChevronDown, Trash2, TriangleAlert } from "lucide-react";

import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import { useStore } from "@/components/providers/app-store";
import { dimsString, displayName, formatInches } from "@/lib/box-display";
import { round2 } from "@/lib/packing-levels";
import { hasSuspectWeight } from "@/lib/seed";
import { cn } from "@/lib/utils";
import type { Box, PackingLevel, PackingLevelKey } from "@/lib/types";

export function InventoryRow({
  box,
  expanded,
  onToggleExpand,
}: {
  box: Box;
  expanded: boolean;
  onToggleExpand: () => void;
}) {
  const { updateBox } = useStore();
  const suspectWeight = hasSuspectWeight(box);

  return (
    <div className="bg-card overflow-hidden rounded-lg border">
      {/* Collapsed header row */}
      <div className="flex items-center gap-3 p-3">
        <Switch
          checked={box.inStock}
          onCheckedChange={(checked) =>
            void updateBox(box.id, { inStock: checked })
          }
          aria-label={`Show ${displayName(box)} in results`}
        />
        <button
          type="button"
          onClick={onToggleExpand}
          className="flex min-w-0 flex-1 items-center gap-2 text-left"
          aria-expanded={expanded}
        >
          <div className="min-w-0 flex-1">
            <div className="flex items-center gap-2">
              <span
                className={cn(
                  "truncate font-medium",
                  !box.inStock && "text-muted-foreground"
                )}
              >
                {displayName(box)}
              </span>
              {suspectWeight ? (
                <TriangleAlert className="size-4 shrink-0 text-amber-500" />
              ) : null}
            </div>
            <div className="text-muted-foreground truncate text-xs">
              {box.type || "Box"} · {dimsString(box)} in
              {box.category === "material" ? " · material" : ""}
            </div>
          </div>
          <Badge variant="outline" className="hidden sm:inline-flex">
            {box.source}
          </Badge>
          <ChevronDown
            className={cn(
              "text-muted-foreground size-5 shrink-0 transition-transform",
              expanded && "rotate-180"
            )}
          />
        </button>
      </div>

      {/* Editor mounts fresh on expand, so its draft starts from the current box. */}
      {expanded ? <RowEditor box={box} suspectWeight={suspectWeight} /> : null}
    </div>
  );
}

// ---------------------------------------------------------------------------

interface Draft {
  name: string;
  type: string;
  length: string;
  width: string;
  height: string;
  weight: string;
  basePrice: string;
  overrides: Partial<Record<PackingLevelKey, string>>;
}

function toDraft(box: Box): Draft {
  const overrides: Draft["overrides"] = {};
  for (const [k, v] of Object.entries(box.priceOverrides ?? {})) {
    if (v != null) overrides[k as PackingLevelKey] = String(v);
  }
  return {
    name: box.name,
    type: box.type,
    length: formatInches(box.length),
    width: formatInches(box.width),
    height: formatInches(box.height),
    weight: box.weight != null ? formatInches(box.weight) : "",
    basePrice: String(box.basePrice),
    overrides,
  };
}

function RowEditor({
  box,
  suspectWeight,
}: {
  box: Box;
  suspectWeight: boolean;
}) {
  const { levels, updateBox, removeBox } = useStore();
  const [draft, setDraft] = React.useState<Draft>(() => toDraft(box));

  function setField<K extends keyof Draft>(key: K, value: Draft[K]) {
    setDraft((d) => ({ ...d, [key]: value }));
  }

  function numOr(fallback: number, text: string): number {
    const n = Number(text);
    return text.trim() !== "" && Number.isFinite(n) && n >= 0 ? n : fallback;
  }

  function buildOverrides(): Box["priceOverrides"] | undefined {
    const out: Partial<Record<PackingLevelKey, number>> = {};
    for (const level of levels) {
      const raw = draft.overrides[level.key];
      if (raw != null && raw.trim() !== "") {
        const n = Number(raw);
        if (Number.isFinite(n) && n >= 0) out[level.key] = n;
      }
    }
    return Object.keys(out).length ? out : undefined;
  }

  /** Persist the whole draft (called on blur of any editable field). */
  function commit() {
    void updateBox(box.id, {
      name: draft.name,
      type: draft.type,
      length: numOr(box.length, draft.length),
      width: numOr(box.width, draft.width),
      height: numOr(box.height, draft.height),
      weight:
        draft.weight.trim() === ""
          ? undefined
          : numOr(box.weight ?? 0, draft.weight),
      basePrice: numOr(box.basePrice, draft.basePrice),
      priceOverrides: buildOverrides(),
    });
  }

  const liveBase = numOr(box.basePrice, draft.basePrice);

  return (
    <div className="flex flex-col gap-4 border-t p-3">
      {suspectWeight ? (
        <p className="flex items-start gap-2 rounded-md bg-amber-50 p-2 text-xs text-amber-800 dark:bg-amber-950/40 dark:text-amber-300">
          <TriangleAlert className="mt-0.5 size-3.5 shrink-0" />
          Seeded weight looks too low for a cube this size (likely truncated from
          ~10–11 lb). Please verify and correct.
        </p>
      ) : null}

      <Field label="Name" htmlFor={`name-${box.id}`}>
        <Input
          id={`name-${box.id}`}
          value={draft.name}
          onChange={(e) => setField("name", e.target.value)}
          onBlur={commit}
          placeholder={dimsString(box)}
        />
      </Field>

      <Field label="Type / category label" htmlFor={`type-${box.id}`}>
        <Input
          id={`type-${box.id}`}
          value={draft.type}
          onChange={(e) => setField("type", e.target.value)}
          onBlur={commit}
          placeholder="e.g. UPS Box, Custom Box"
        />
      </Field>

      <div>
        <Label className="text-muted-foreground mb-1.5 text-xs uppercase tracking-wide">
          Dimensions (in)
        </Label>
        <div className="grid grid-cols-3 gap-2">
          {(["length", "width", "height"] as const).map((dim) => (
            <Input
              key={dim}
              value={draft[dim]}
              onChange={(e) => setField(dim, e.target.value)}
              onBlur={commit}
              inputMode="decimal"
              type="number"
              min={0}
              step="any"
              aria-label={dim}
              className="text-center tabular-nums"
            />
          ))}
        </div>
      </div>

      <div className="grid grid-cols-2 gap-2">
        <Field label="Base price (Box only) $" htmlFor={`base-${box.id}`}>
          <Input
            id={`base-${box.id}`}
            value={draft.basePrice}
            onChange={(e) => setField("basePrice", e.target.value)}
            onBlur={commit}
            inputMode="decimal"
            type="number"
            min={0}
            step="0.01"
            className="tabular-nums"
          />
        </Field>
        <Field label="Weight (lb, optional)" htmlFor={`weight-${box.id}`}>
          <Input
            id={`weight-${box.id}`}
            value={draft.weight}
            onChange={(e) => setField("weight", e.target.value)}
            onBlur={commit}
            inputMode="decimal"
            type="number"
            min={0}
            step="any"
            placeholder="—"
            className="tabular-nums"
          />
        </Field>
      </div>

      {/* Tier prices: auto-compute from base + upcharge, override allowed. */}
      <div>
        <Label className="text-muted-foreground mb-1.5 text-xs uppercase tracking-wide">
          Tier prices (auto from base + upcharge — edit to override)
        </Label>
        <div className="flex flex-col gap-1.5">
          {levels.map((level) => (
            <TierPriceInput
              key={level.key}
              level={level}
              base={liveBase}
              value={draft.overrides[level.key] ?? ""}
              onChange={(v) =>
                setDraft((d) => ({
                  ...d,
                  overrides: { ...d.overrides, [level.key]: v },
                }))
              }
              onCommit={commit}
            />
          ))}
        </div>
      </div>

      <div className="flex items-center justify-between gap-2 border-t pt-3">
        <Button
          type="button"
          variant="ghost"
          size="sm"
          onClick={() =>
            void updateBox(box.id, {
              category: box.category === "box" ? "material" : "box",
            })
          }
        >
          {box.category === "box" ? "Mark as material" : "Mark as box"}
        </Button>
        <Button
          type="button"
          variant="destructive"
          size="sm"
          onClick={() => void removeBox(box.id)}
        >
          <Trash2 /> Remove
        </Button>
      </div>
    </div>
  );
}

function Field({
  label,
  htmlFor,
  children,
}: {
  label: string;
  htmlFor?: string;
  children: React.ReactNode;
}) {
  return (
    <div className="flex flex-col gap-1.5">
      <Label
        htmlFor={htmlFor}
        className="text-muted-foreground text-xs uppercase tracking-wide"
      >
        {label}
      </Label>
      {children}
    </div>
  );
}

function TierPriceInput({
  level,
  base,
  value,
  onChange,
  onCommit,
}: {
  level: PackingLevel;
  base: number;
  value: string;
  onChange: (value: string) => void;
  onCommit: () => void;
}) {
  const computed = round2(base + level.upcharge);
  const overridden = value.trim() !== "";
  return (
    <div className="flex items-center gap-2">
      <span className="w-24 shrink-0 text-sm">{level.label}</span>
      <div className="relative flex-1">
        <span className="text-muted-foreground absolute left-2.5 top-1/2 -translate-y-1/2 text-sm">
          $
        </span>
        <Input
          value={value}
          onChange={(e) => onChange(e.target.value)}
          onBlur={onCommit}
          inputMode="decimal"
          type="number"
          min={0}
          step="0.01"
          placeholder={computed.toFixed(2)}
          aria-label={`${level.label} price`}
          className={cn("pl-6 tabular-nums", overridden && "border-primary")}
        />
      </div>
      <span className="text-muted-foreground w-28 shrink-0 text-right text-xs">
        {overridden ? "override" : `= base + $${level.upcharge.toFixed(2)}`}
      </span>
    </div>
  );
}
