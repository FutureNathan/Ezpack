"use client";

import { cn } from "@/lib/utils";
import type { PackingLevel, PackingLevelKey } from "@/lib/types";

interface LevelSelectorProps {
  levels: PackingLevel[];
  value: PackingLevelKey;
  onChange: (key: PackingLevelKey) => void;
}

/** Segmented control for the five packing levels. */
export function LevelSelector({ levels, value, onChange }: LevelSelectorProps) {
  return (
    <div
      role="radiogroup"
      aria-label="Packing level"
      className="grid grid-cols-2 gap-1.5 sm:grid-cols-5"
    >
      {levels.map((level) => {
        const active = level.key === value;
        return (
          <button
            key={level.key}
            type="button"
            role="radio"
            aria-checked={active}
            onClick={() => onChange(level.key)}
            className={cn(
              "flex flex-col items-center justify-center gap-0.5 rounded-md border px-2 py-2 text-center transition-colors",
              "focus-visible:ring-ring/50 outline-none focus-visible:ring-[3px]",
              active
                ? "border-primary bg-primary text-primary-foreground"
                : "bg-background hover:bg-accent hover:text-accent-foreground"
            )}
          >
            <span className="text-sm font-semibold leading-tight">
              {level.label}
            </span>
            <span
              className={cn(
                "text-[11px] leading-tight",
                active ? "text-primary-foreground/75" : "text-muted-foreground"
              )}
            >
              +${level.upcharge.toFixed(2)} · {formatPad(level.padding)}″ pad
            </span>
          </button>
        );
      })}
    </div>
  );
}

function formatPad(n: number): string {
  return Number.isInteger(n) ? String(n) : String(Number(n.toFixed(2)));
}
