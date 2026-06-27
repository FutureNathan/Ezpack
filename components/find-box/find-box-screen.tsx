"use client";

import * as React from "react";
import { RotateCcw, PackageX } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { useStore } from "@/components/providers/app-store";
import { DimensionInputs, type DimField } from "./dimension-inputs";
import { LevelSelector } from "./level-selector";
import { CompactResult, PrimaryResult } from "./result-card";
import { findBoxes } from "@/lib/matching";
import { findLevel } from "@/lib/packing-levels";
import { dimsString } from "@/lib/box-display";
import { cn } from "@/lib/utils";
import type { FitResult, PackingLevelKey, SortMode } from "@/lib/types";

const EMPTY_DIMS: Record<DimField, string> = {
  length: "",
  width: "",
  height: "",
};

export function FindBoxScreen() {
  const {
    ready,
    boxes,
    levels,
    prefs,
    setPref,
    updateBox,
    addHistory,
  } = useStore();

  const [dims, setDims] = React.useState<Record<DimField, string>>(EMPTY_DIMS);
  const lastLoggedKey = React.useRef<string | null>(null);

  const level = findLevel(levels, prefs.lastLevel);

  const item = React.useMemo(() => {
    const l = Number(dims.length);
    const w = Number(dims.width);
    const h = Number(dims.height);
    const valid =
      [l, w, h].every((n) => Number.isFinite(n) && n > 0) &&
      dims.length !== "" &&
      dims.width !== "" &&
      dims.height !== "";
    return valid ? { length: l, width: w, height: h } : null;
  }, [dims]);

  const match = React.useMemo(() => {
    if (!item) return null;
    return findBoxes(boxes, item, level, prefs.sortMode);
  }, [item, boxes, level, prefs.sortMode]);

  function handleChange(field: DimField, value: string) {
    setDims((prev) => ({ ...prev, [field]: value }));
  }

  function reset() {
    setDims(EMPTY_DIMS);
    lastLoggedKey.current = null;
  }

  function handleSetCustomPrice(boxId: string, price: number | null) {
    const box = boxes.find((b) => b.id === boxId);
    if (!box) return;
    const overrides = { ...(box.priceOverrides ?? {}) };
    if (price == null) delete overrides.custom;
    else overrides.custom = price;
    void updateBox(boxId, {
      priceOverrides: Object.keys(overrides).length ? overrides : undefined,
    });
  }

  function logEstimate(result: FitResult) {
    if (!item) return;
    const key = `${item.length}x${item.width}x${item.height}|${level.key}|${result.box.id}`;
    if (lastLoggedKey.current === key) return;
    lastLoggedKey.current = key;
    addHistory({
      length: item.length,
      width: item.width,
      height: item.height,
      level: level.key,
      levelLabel: level.label,
      boxId: result.box.id,
      boxName: result.box.name || dimsString(result.box),
      boxDimensions: dimsString(result.box),
      price: result.price,
    });
  }

  return (
    <div className="flex flex-col gap-4">
      {/* Input card */}
      <Card className="gap-4 p-4">
        <div className="flex items-center justify-between">
          <h1 className="text-base font-semibold">Find a box</h1>
          <Button
            type="button"
            variant="ghost"
            size="sm"
            onClick={reset}
            disabled={dims.length === "" && dims.width === "" && dims.height === ""}
          >
            <RotateCcw /> Reset
          </Button>
        </div>

        <DimensionInputs values={dims} onChange={handleChange} autoFocus />

        <div className="flex flex-col gap-2">
          <LevelSelector
            levels={levels}
            value={prefs.lastLevel}
            onChange={(key: PackingLevelKey) => setPref("lastLevel", key)}
          />
        </div>
      </Card>

      {/* Results */}
      {!ready ? (
        <p className="text-muted-foreground py-8 text-center text-sm">
          Loading inventory…
        </p>
      ) : !item ? (
        <p className="text-muted-foreground py-8 text-center text-sm">
          Enter all three dimensions to see the best box.
        </p>
      ) : match && match.anyFit ? (
        <Results
          match={match}
          sortMode={prefs.sortMode}
          onResolve={(mode) => setPref("sortMode", mode)}
        >
          <PrimaryResult
            result={match.fitting[0]}
            level={level}
            fits
            onCopyDimensions={logEstimate}
            onSetCustomPrice={handleSetCustomPrice}
          />
          {match.fitting.slice(1).map((r, i) => (
            <CompactResult key={r.box.id} result={r} rank={i + 2} fits />
          ))}
        </Results>
      ) : (
        <NoFit match={match} sortMode={prefs.sortMode} setPref={setPref}>
          {match && match.closest.length > 0 ? (
            <>
              <PrimaryResult
                result={match.closest[0]}
                level={level}
                fits={false}
                onSetCustomPrice={handleSetCustomPrice}
              />
              {match.closest.slice(1).map((r, i) => (
                <CompactResult
                  key={r.box.id}
                  result={r}
                  rank={i + 2}
                  fits={false}
                />
              ))}
            </>
          ) : null}
        </NoFit>
      )}
    </div>
  );
}

function SortToggle({
  value,
  onChange,
}: {
  value: SortMode;
  onChange: (mode: SortMode) => void;
}) {
  const options: { key: SortMode; label: string }[] = [
    { key: "closest_small", label: "Closest small" },
    { key: "cheapest", label: "Cheapest" },
  ];
  return (
    <div className="bg-muted inline-flex rounded-md p-0.5 text-xs">
      {options.map((opt) => (
        <button
          key={opt.key}
          type="button"
          onClick={() => onChange(opt.key)}
          className={cn(
            "rounded px-2.5 py-1 font-medium transition-colors",
            value === opt.key
              ? "bg-background text-foreground shadow-sm"
              : "text-muted-foreground hover:text-foreground"
          )}
        >
          {opt.label}
        </button>
      ))}
    </div>
  );
}

function Results({
  match,
  sortMode,
  onResolve,
  children,
}: {
  match: NonNullable<ReturnType<typeof findBoxes>>;
  sortMode: SortMode;
  onResolve: (mode: SortMode) => void;
  children: React.ReactNode;
}) {
  return (
    <div className="flex flex-col gap-3">
      <div className="flex items-center justify-between">
        <span className="text-muted-foreground text-sm">
          {match.fitting.length} box
          {match.fitting.length === 1 ? "" : "es"} fit · sort by
        </span>
        <SortToggle value={sortMode} onChange={onResolve} />
      </div>
      {children}
    </div>
  );
}

function NoFit({
  match,
  sortMode,
  setPref,
  children,
}: {
  match: ReturnType<typeof findBoxes> | null;
  sortMode: SortMode;
  setPref: ReturnType<typeof useStore>["setPref"];
  children: React.ReactNode;
}) {
  return (
    <div className="flex flex-col gap-3">
      <Card className="border-destructive/40 bg-destructive/5 flex-row items-center gap-3 p-4">
        <PackageX className="text-destructive size-6 shrink-0" />
        <div>
          <p className="font-semibold">No in-stock box fits this item.</p>
          <p className="text-muted-foreground text-sm">
            Showing the smallest boxes that came closest — try a lower packing
            level, or add a larger box in Inventory.
          </p>
        </div>
      </Card>
      {match && match.closest.length > 0 ? (
        <div className="flex items-center justify-end">
          <SortToggle
            value={sortMode}
            onChange={(mode) => setPref("sortMode", mode)}
          />
        </div>
      ) : null}
      {children}
    </div>
  );
}
