"use client";

import * as React from "react";
import { Ruler, Weight } from "lucide-react";

import { Badge } from "@/components/ui/badge";
import { Card } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { CopyButton } from "@/components/copy-button";
import {
  dimsPlain,
  dimsString,
  displayName,
  formatInches,
  slackLabel,
} from "@/lib/box-display";
import { formatUsd } from "@/lib/packing-levels";
import { cn } from "@/lib/utils";
import type { FitResult, PackingLevel } from "@/lib/types";

interface PrimaryResultProps {
  result: FitResult;
  level: PackingLevel;
  fits: boolean;
  onCopyDimensions?: (result: FitResult) => void;
  onSetCustomPrice?: (boxId: string, price: number | null) => void;
}

/** The large, emphasized #1 recommendation. */
export function PrimaryResult({
  result,
  level,
  fits,
  onCopyDimensions,
  onSetCustomPrice,
}: PrimaryResultProps) {
  const { box } = result;
  const dims = dimsString(box);
  const isCustom = level.key === "custom";

  return (
    <Card
      className={cn(
        "gap-4 border-2 p-5",
        fits ? "border-primary" : "border-destructive/50"
      )}
    >
      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0">
          <Badge variant={fits ? "default" : "destructive"} className="mb-1.5">
            {fits ? "Best match" : "Closest (doesn't fully fit)"}
          </Badge>
          <h2 className="truncate text-2xl font-bold leading-tight">
            {displayName(box)}
          </h2>
          {box.type ? (
            <p className="text-muted-foreground text-sm">{box.type}</p>
          ) : null}
        </div>
        <div className="text-right">
          <div className="text-muted-foreground text-xs">{level.label}</div>
          <div className="text-3xl font-bold tabular-nums">
            {formatUsd(result.price)}
          </div>
        </div>
      </div>

      {/* Outer dimensions — the value pasted into shipping software. */}
      <div className="bg-muted/60 rounded-lg border p-3">
        <div className="text-muted-foreground mb-1 flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide">
          <Ruler className="size-3.5" /> Outer dimensions (paste into shipping
          software)
        </div>
        <div className="flex flex-wrap items-center justify-between gap-2">
          <div className="text-2xl font-semibold tabular-nums">
            {dims} <span className="text-muted-foreground text-base">in</span>
          </div>
          <CopyButton
            value={dimsPlain(box)}
            label="Copy dimensions"
            size="lg"
            onCopied={() => onCopyDimensions?.(result)}
          />
        </div>
      </div>

      <div className="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
        <span
          className={cn(
            "font-medium",
            fits ? "text-success" : "text-destructive"
          )}
        >
          {fits ? slackLabel(result.slack) : underfitLabel(result)}
        </span>

        {box.weight != null ? (
          <CopyButton
            value={formatInches(box.weight)}
            label={
              <span className="inline-flex items-center gap-1.5">
                <Weight className="size-3.5" />
                {formatInches(box.weight)} lb
              </span>
            }
            variant="outline"
            size="sm"
          />
        ) : null}
      </div>

      {isCustom ? (
        <CustomPriceField
          key={box.id}
          boxId={box.id}
          value={box.priceOverrides?.custom}
          fallback={result.price}
          onSet={onSetCustomPrice}
        />
      ) : null}
    </Card>
  );
}

function underfitLabel(result: FitResult): string {
  const short = Math.round(Math.abs(Math.min(...result.clearance)) * 10) / 10;
  return `too small by ~${formatInches(short)}″ on the tightest side`;
}

function CustomPriceField({
  boxId,
  value,
  fallback,
  onSet,
}: {
  boxId: string;
  value: number | undefined;
  fallback: number;
  onSet?: (boxId: string, price: number | null) => void;
}) {
  // Initialised once on mount; the parent remounts this via key={box.id} when
  // the recommended box changes, so no effect-based prop sync is needed.
  const [text, setText] = React.useState(value != null ? String(value) : "");

  function commit() {
    const trimmed = text.trim();
    if (trimmed === "") {
      onSet?.(boxId, null);
      return;
    }
    const n = Number(trimmed);
    if (!Number.isNaN(n) && n >= 0) onSet?.(boxId, n);
  }

  return (
    <div className="flex items-end gap-2 border-t pt-3">
      <div className="flex flex-col gap-1.5">
        <Label htmlFor={`custom-price-${boxId}`} className="text-xs">
          Custom price for this job
        </Label>
        <div className="relative">
          <span className="text-muted-foreground absolute left-2.5 top-1/2 -translate-y-1/2 text-sm">
            $
          </span>
          <Input
            id={`custom-price-${boxId}`}
            value={text}
            onChange={(e) => setText(e.target.value)}
            onBlur={commit}
            onKeyDown={(e) => {
              if (e.key === "Enter") (e.target as HTMLInputElement).blur();
            }}
            inputMode="decimal"
            placeholder={fallback.toFixed(2)}
            className="w-28 pl-6"
          />
        </div>
      </div>
      <p className="text-muted-foreground pb-2 text-xs">
        Overrides this box&apos;s Custom-level price. Clear to use the default.
      </p>
    </div>
  );
}

/** A compact row for results #2–#5 (and closest-fit fallbacks). */
export function CompactResult({
  result,
  rank,
  fits,
}: {
  result: FitResult;
  rank: number;
  fits: boolean;
}) {
  const { box } = result;
  return (
    <div className="flex items-center gap-3 rounded-md border px-3 py-2">
      <div className="text-muted-foreground w-5 text-center text-sm font-semibold tabular-nums">
        {rank}
      </div>
      <div className="min-w-0 flex-1">
        <div className="truncate text-sm font-medium">{displayName(box)}</div>
        <div className="text-muted-foreground text-xs tabular-nums">
          {dimsString(box)} in
          {fits ? (
            <> · {slackLabel(result.slack)}</>
          ) : (
            <> · {underfitLabel(result)}</>
          )}
        </div>
      </div>
      <div className="text-right">
        <div className="text-sm font-semibold tabular-nums">
          {formatUsd(result.price)}
        </div>
      </div>
      <CopyButton
        value={dimsPlain(box)}
        label="Copy"
        variant="outline"
        size="sm"
      />
    </div>
  );
}
