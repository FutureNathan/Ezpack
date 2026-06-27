"use client";

import * as React from "react";
import { RotateCcw } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { useStore } from "@/components/providers/app-store";
import type { PackingLevel } from "@/lib/types";

export function SettingsScreen() {
  const { levels, updateLevel, resetLevels, resetCatalog } = useStore();

  function handleResetCatalog() {
    const ok = window.confirm(
      "Reset the catalog to the seeded boxes? This replaces all boxes " +
        "(including your custom ones and edits) with the original catalog."
    );
    if (ok) void resetCatalog();
  }

  return (
    <div className="flex flex-col gap-5">
      <h1 className="text-lg font-semibold">Settings</h1>

      <Card className="gap-4 p-4">
        <div>
          <h2 className="font-semibold">Packing levels</h2>
          <p className="text-muted-foreground text-sm">
            The upcharge is added to a box&apos;s base price. The padding is the
            clearance added to each item dimension when checking fit (more
            protection → more void fill → a bigger box).
          </p>
        </div>

        <div className="grid grid-cols-[1fr_auto_auto] items-center gap-x-3 gap-y-1">
          <span className="text-muted-foreground text-xs uppercase tracking-wide">
            Level
          </span>
          <span className="text-muted-foreground w-28 text-right text-xs uppercase tracking-wide">
            Upcharge ($)
          </span>
          <span className="text-muted-foreground w-28 text-right text-xs uppercase tracking-wide">
            Padding (in)
          </span>
          <div className="col-span-3">
            <Separator />
          </div>
          {levels.map((level) => (
            <LevelSettingRow
              key={level.key}
              level={level}
              onUpcharge={(v) => updateLevel(level.key, { upcharge: v })}
              onPadding={(v) => updateLevel(level.key, { padding: v })}
            />
          ))}
        </div>

        <div>
          <Button type="button" variant="outline" size="sm" onClick={resetLevels}>
            <RotateCcw /> Reset levels to defaults
          </Button>
        </div>
      </Card>

      <Card className="border-destructive/40 gap-3 p-4">
        <div>
          <h2 className="font-semibold">Reset catalog</h2>
          <p className="text-muted-foreground text-sm">
            Replace all boxes with the original seeded catalog. Removes custom
            boxes and discards edits.
          </p>
        </div>
        <div>
          <Button
            type="button"
            variant="destructive"
            size="sm"
            onClick={handleResetCatalog}
          >
            Reset to seeded catalog
          </Button>
        </div>
      </Card>
    </div>
  );
}

function LevelSettingRow({
  level,
  onUpcharge,
  onPadding,
}: {
  level: PackingLevel;
  onUpcharge: (value: number) => void;
  onPadding: (value: number) => void;
}) {
  const [upcharge, setUpcharge] = React.useState(String(level.upcharge));
  const [padding, setPadding] = React.useState(String(level.padding));

  // Sync local inputs when the level changes externally (e.g. "reset to
  // defaults") using React's adjust-state-during-render pattern — no effect.
  const [prev, setPrev] = React.useState({
    u: level.upcharge,
    p: level.padding,
  });
  if (prev.u !== level.upcharge || prev.p !== level.padding) {
    setPrev({ u: level.upcharge, p: level.padding });
    setUpcharge(String(level.upcharge));
    setPadding(String(level.padding));
  }

  function commitUpcharge() {
    const n = Number(upcharge);
    if (Number.isFinite(n) && n >= 0) onUpcharge(n);
    else setUpcharge(String(level.upcharge));
  }
  function commitPadding() {
    const n = Number(padding);
    if (Number.isFinite(n) && n >= 0) onPadding(n);
    else setPadding(String(level.padding));
  }

  return (
    <>
      <Label className="text-sm font-medium">{level.label}</Label>
      <Input
        value={upcharge}
        onChange={(e) => setUpcharge(e.target.value)}
        onBlur={commitUpcharge}
        inputMode="decimal"
        type="number"
        min={0}
        step="0.01"
        aria-label={`${level.label} upcharge`}
        className="w-28 text-right tabular-nums"
      />
      <Input
        value={padding}
        onChange={(e) => setPadding(e.target.value)}
        onBlur={commitPadding}
        inputMode="decimal"
        type="number"
        min={0}
        step="0.25"
        aria-label={`${level.label} padding`}
        className="w-28 text-right tabular-nums"
      />
    </>
  );
}
