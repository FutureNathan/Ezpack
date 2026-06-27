"use client";

import * as React from "react";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";

export type DimField = "length" | "width" | "height";

interface DimensionInputsProps {
  values: Record<DimField, string>;
  onChange: (field: DimField, value: string) => void;
  autoFocus?: boolean;
}

const FIELDS: { key: DimField; label: string }[] = [
  { key: "length", label: "Length" },
  { key: "width", label: "Width" },
  { key: "height", label: "Height" },
];

/**
 * Three big numeric inputs. Tab moves to the next field; Enter does too (for
 * fast single-handed entry). Mobile gets a numeric keypad via inputMode.
 */
export function DimensionInputs({
  values,
  onChange,
  autoFocus,
}: DimensionInputsProps) {
  const refs = React.useRef<(HTMLInputElement | null)[]>([]);

  function handleKeyDown(
    e: React.KeyboardEvent<HTMLInputElement>,
    index: number
  ) {
    if (e.key === "Enter") {
      e.preventDefault();
      refs.current[index + 1]?.focus();
    }
  }

  return (
    <div className="grid grid-cols-3 gap-2 sm:gap-3">
      {FIELDS.map((field, i) => (
        <div key={field.key} className="flex flex-col gap-1.5">
          <Label
            htmlFor={`dim-${field.key}`}
            className="text-muted-foreground text-xs uppercase tracking-wide"
          >
            {field.label}
          </Label>
          <div className="relative">
            <Input
              id={`dim-${field.key}`}
              ref={(el) => {
                refs.current[i] = el;
              }}
              value={values[field.key]}
              onChange={(e) => onChange(field.key, e.target.value)}
              onKeyDown={(e) => handleKeyDown(e, i)}
              onFocus={(e) => e.target.select()}
              inputMode="decimal"
              type="number"
              min={0}
              step="any"
              placeholder="0"
              autoFocus={autoFocus && i === 0}
              aria-label={`${field.label} in inches`}
              className={cn(
                "h-14 pr-7 text-center text-2xl font-semibold tabular-nums",
                "[appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
              )}
            />
            <span className="text-muted-foreground pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-sm">
              in
            </span>
          </div>
        </div>
      ))}
    </div>
  );
}
