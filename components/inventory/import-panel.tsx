"use client";

import * as React from "react";
import { Download, FileUp, Upload, X, CircleAlert, CircleCheck } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { useStore } from "@/components/providers/app-store";
import {
  boxesToCsv,
  importTemplateCsv,
  parseBoxImport,
} from "@/lib/import-boxes";
import { dimsString } from "@/lib/box-display";
import { formatUsd } from "@/lib/packing-levels";
import { cn } from "@/lib/utils";

function downloadCsv(filename: string, text: string) {
  const blob = new Blob([text], { type: "text/csv;charset=utf-8" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

export function ImportPanel({ onClose }: { onClose: () => void }) {
  const { levels, boxes, importBoxes } = useStore();
  const [raw, setRaw] = React.useState("");
  const [fileName, setFileName] = React.useState<string | null>(null);
  const [mode, setMode] = React.useState<"append" | "replace">("append");
  const [done, setDone] = React.useState<number | null>(null);

  const result = React.useMemo(
    () => (raw.trim() ? parseBoxImport(raw, levels) : null),
    [raw, levels]
  );

  async function handleFile(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (!file) return;
    setFileName(file.name);
    setDone(null);
    setRaw(await file.text());
  }

  async function doImport() {
    if (!result || result.drafts.length === 0) return;
    if (
      mode === "replace" &&
      !window.confirm(
        `Replace ALL ${boxes.length} current boxes with the ${result.drafts.length} imported boxes? This removes the seeded catalog and any edits.`
      )
    ) {
      return;
    }
    const n = await importBoxes(result.drafts, mode);
    setDone(n);
    setRaw("");
    setFileName(null);
  }

  return (
    <Card className="gap-4 border-primary/40 p-4">
      <div className="flex items-center justify-between">
        <h2 className="flex items-center gap-2 font-semibold">
          <Upload className="size-4" /> Import box list
        </h2>
        <Button type="button" variant="ghost" size="icon" onClick={onClose} aria-label="Close import">
          <X />
        </Button>
      </div>

      <p className="text-muted-foreground text-sm">
        Upload a CSV of your own boxes and pricing (or paste it below). Columns
        are matched flexibly — at minimum include <code>length</code>,{" "}
        <code>width</code>, <code>height</code>; optional{" "}
        <code>name, basePrice, weight, sku, category</code> and per-tier prices.
      </p>

      <div className="flex flex-wrap gap-2">
        <Button asChild type="button" variant="outline" size="sm">
          <label className="cursor-pointer">
            <FileUp /> Choose CSV file
            <input
              type="file"
              accept=".csv,text/csv,text/plain"
              className="sr-only"
              onChange={handleFile}
            />
          </label>
        </Button>
        <Button
          type="button"
          variant="outline"
          size="sm"
          onClick={() => downloadCsv("ezpack-template.csv", importTemplateCsv())}
        >
          <Download /> Template
        </Button>
        <Button
          type="button"
          variant="outline"
          size="sm"
          onClick={() => downloadCsv("ezpack-inventory.csv", boxesToCsv(boxes))}
        >
          <Download /> Export current
        </Button>
        {fileName ? (
          <span className="text-muted-foreground self-center text-xs">
            {fileName}
          </span>
        ) : null}
      </div>

      <div className="flex flex-col gap-1.5">
        <Label htmlFor="import-paste" className="text-muted-foreground text-xs uppercase tracking-wide">
          …or paste CSV
        </Label>
        <textarea
          id="import-paste"
          value={raw}
          onChange={(e) => {
            setRaw(e.target.value);
            setDone(null);
            setFileName(null);
          }}
          placeholder={"name,length,width,height,weight,basePrice,sku,category\nMy Box,12,9,4,1,3.50,SB-001,box"}
          rows={4}
          className="border-input focus-visible:border-ring focus-visible:ring-ring/50 w-full resize-y rounded-md border bg-transparent px-3 py-2 font-mono text-xs shadow-xs outline-none focus-visible:ring-[3px]"
        />
      </div>

      {done != null ? (
        <p className="flex items-center gap-2 rounded-md bg-success/10 p-2 text-sm font-medium text-success">
          <CircleCheck className="size-4" /> Imported {done} box
          {done === 1 ? "" : "es"}.{" "}
          <button className="underline" onClick={onClose} type="button">
            Done
          </button>
        </p>
      ) : null}

      {result ? <Preview result={result} /> : null}

      {result && result.drafts.length > 0 ? (
        <>
          <Separator />
          <div className="flex flex-wrap items-center justify-between gap-3">
            <div
              role="radiogroup"
              aria-label="Import mode"
              className="bg-muted inline-flex rounded-md p-0.5 text-xs"
            >
              {(
                [
                  ["append", "Add to inventory"],
                  ["replace", "Replace all"],
                ] as const
              ).map(([key, label]) => (
                <button
                  key={key}
                  type="button"
                  role="radio"
                  aria-checked={mode === key}
                  onClick={() => setMode(key)}
                  className={cn(
                    "rounded px-2.5 py-1 font-medium transition-colors",
                    mode === key
                      ? "bg-background text-foreground shadow-sm"
                      : "text-muted-foreground hover:text-foreground"
                  )}
                >
                  {label}
                </button>
              ))}
            </div>
            <Button type="button" size="sm" onClick={doImport}>
              <Upload /> Import {result.drafts.length} box
              {result.drafts.length === 1 ? "" : "es"}
            </Button>
          </div>
        </>
      ) : null}
    </Card>
  );
}

function Preview({
  result,
}: {
  result: NonNullable<ReturnType<typeof parseBoxImport>>;
}) {
  const sample = result.drafts.slice(0, 5);
  return (
    <div className="flex flex-col gap-2 text-sm">
      <div className="flex flex-wrap gap-x-4 gap-y-1">
        <span className="font-medium">
          {result.drafts.length} box{result.drafts.length === 1 ? "" : "es"} ready
        </span>
        {result.errors.length > 0 ? (
          <span className="text-amber-600 dark:text-amber-400">
            {result.errors.length} row{result.errors.length === 1 ? "" : "s"} skipped
          </span>
        ) : null}
      </div>

      {Object.keys(result.mapped).length > 0 ? (
        <p className="text-muted-foreground text-xs">
          Mapped:{" "}
          {Object.entries(result.mapped)
            .map(([h, f]) => `${h}→${f}`)
            .join(", ")}
          {result.unmapped.length > 0 ? (
            <> · ignored: {result.unmapped.join(", ")}</>
          ) : null}
        </p>
      ) : null}

      {sample.length > 0 ? (
        <div className="overflow-hidden rounded-md border">
          {sample.map((d, i) => (
            <div
              key={i}
              className="flex items-center gap-3 border-b px-3 py-1.5 last:border-b-0"
            >
              <span className="min-w-0 flex-1 truncate">
                {d.name || dimsString(d)}
              </span>
              <span className="text-muted-foreground tabular-nums">
                {dimsString(d)} in
              </span>
              <span className="tabular-nums">{formatUsd(d.basePrice)}</span>
              {d.category === "material" ? (
                <span className="text-muted-foreground text-xs">material</span>
              ) : null}
            </div>
          ))}
          {result.drafts.length > sample.length ? (
            <div className="text-muted-foreground px-3 py-1.5 text-xs">
              +{result.drafts.length - sample.length} more
            </div>
          ) : null}
        </div>
      ) : null}

      {result.errors.length > 0 ? (
        <details className="text-xs">
          <summary className="text-muted-foreground cursor-pointer">
            Skipped rows
          </summary>
          <ul className="mt-1 flex flex-col gap-0.5">
            {result.errors.slice(0, 12).map((e, i) => (
              <li key={i} className="flex items-start gap-1.5">
                <CircleAlert className="mt-0.5 size-3 shrink-0 text-amber-500" />
                line {e.line}: {e.reason}
              </li>
            ))}
            {result.errors.length > 12 ? (
              <li className="text-muted-foreground">
                …and {result.errors.length - 12} more
              </li>
            ) : null}
          </ul>
        </details>
      ) : null}
    </div>
  );
}
