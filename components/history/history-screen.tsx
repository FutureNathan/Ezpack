"use client";

import { Trash2 } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { useStore } from "@/components/providers/app-store";
import { CopyButton } from "@/components/copy-button";
import { dimsPlain } from "@/lib/box-display";
import { formatUsd } from "@/lib/packing-levels";

export function HistoryScreen() {
  const { ready, history, clearHistory } = useStore();

  return (
    <div className="flex flex-col gap-4">
      <div className="flex items-center justify-between">
        <h1 className="text-lg font-semibold">History</h1>
        {history.length > 0 ? (
          <Button
            type="button"
            variant="ghost"
            size="sm"
            onClick={clearHistory}
          >
            <Trash2 /> Clear
          </Button>
        ) : null}
      </div>

      <p className="text-muted-foreground text-xs">
        Recent estimates are logged when you copy a recommended box&apos;s
        dimensions.
      </p>

      {!ready ? (
        <p className="text-muted-foreground py-8 text-center text-sm">Loading…</p>
      ) : history.length === 0 ? (
        <p className="text-muted-foreground py-8 text-center text-sm">
          No estimates yet.
        </p>
      ) : (
        <div className="flex flex-col gap-2">
          {history.map((h) => (
            <Card key={h.id} className="flex-row items-center gap-3 p-3">
              <div className="min-w-0 flex-1">
                <div className="text-sm font-medium">
                  {h.length} × {h.width} × {h.height} in · {h.levelLabel}
                </div>
                <div className="text-muted-foreground truncate text-xs">
                  {h.boxName ? (
                    <>
                      → {h.boxName}
                      {h.boxDimensions ? ` (${h.boxDimensions} in)` : ""}
                    </>
                  ) : (
                    "no box chosen"
                  )}
                  {" · "}
                  {formatDate(h.at)}
                </div>
              </div>
              {h.price != null ? (
                <div className="text-sm font-semibold tabular-nums">
                  {formatUsd(h.price)}
                </div>
              ) : null}
              {h.boxDimensions ? (
                <CopyButton
                  value={dimsPlain({
                    length: parseDim(h.boxDimensions, 0),
                    width: parseDim(h.boxDimensions, 1),
                    height: parseDim(h.boxDimensions, 2),
                  })}
                  label="Copy"
                  variant="outline"
                  size="sm"
                />
              ) : null}
            </Card>
          ))}
        </div>
      )}
    </div>
  );
}

function parseDim(dimsStr: string, index: number): number {
  const parts = dimsStr.split("×").map((p) => Number(p.trim()));
  return parts[index] ?? 0;
}

function formatDate(iso: string): string {
  try {
    const d = new Date(iso);
    return d.toLocaleString(undefined, {
      month: "short",
      day: "numeric",
      hour: "numeric",
      minute: "2-digit",
    });
  } catch {
    return "";
  }
}
