"use client";

import * as React from "react";
import { Plus, Search, Upload } from "lucide-react";
import { toast } from "sonner";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { useStore } from "@/components/providers/app-store";
import { InventoryRow } from "./inventory-row";
import { ImportPanel } from "./import-panel";
import { dimsString } from "@/lib/box-display";
import type { Box } from "@/lib/types";

function matchesQuery(box: Box, q: string): boolean {
  if (!q) return true;
  const haystack = [
    box.name,
    box.type,
    box.sku ?? "",
    dimsString(box),
    box.category,
  ]
    .join(" ")
    .toLowerCase();
  return haystack.includes(q.toLowerCase());
}

export function InventoryScreen() {
  const { ready, boxes, addBox } = useStore();
  const [query, setQuery] = React.useState("");
  const [expanded, setExpanded] = React.useState<Set<string>>(new Set());
  const [importing, setImporting] = React.useState(false);

  const filtered = React.useMemo(
    () => boxes.filter((b) => matchesQuery(b, query)),
    [boxes, query]
  );

  function toggle(id: string) {
    setExpanded((prev) => {
      const next = new Set(prev);
      if (next.has(id)) next.delete(id);
      else next.add(id);
      return next;
    });
  }

  async function handleAdd() {
    const created = await addBox({
      name: "",
      type: "Custom Box",
      length: 12,
      width: 12,
      height: 12,
      basePrice: 0,
      inStock: true,
      category: "box",
    });
    setExpanded((prev) => new Set(prev).add(created.id));
    setQuery("");
    toast.success("Box added");
    // Bring the new (appended) row into view.
    requestAnimationFrame(() => {
      document
        .getElementById(`box-${created.id}`)
        ?.scrollIntoView({ behavior: "smooth", block: "center" });
    });
  }

  const inStockCount = boxes.filter((b) => b.inStock && b.category === "box")
    .length;

  return (
    <div className="flex flex-col gap-4">
      <div className="flex items-center justify-between gap-2">
        <h1 className="text-lg font-semibold">Inventory</h1>
        <div className="flex gap-2">
          <Button
            type="button"
            size="sm"
            variant="outline"
            onClick={() => setImporting((v) => !v)}
            aria-expanded={importing}
          >
            <Upload /> Import
          </Button>
          <Button type="button" size="sm" onClick={handleAdd}>
            <Plus /> Add box
          </Button>
        </div>
      </div>

      {importing ? <ImportPanel onClose={() => setImporting(false)} /> : null}

      <div className="relative">
        <Search className="text-muted-foreground absolute left-2.5 top-1/2 size-4 -translate-y-1/2" />
        <Input
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          placeholder="Search name, type, SKU, or dimensions…"
          className="pl-8"
        />
      </div>

      <p className="text-muted-foreground text-xs">
        {ready
          ? `${boxes.length} items · ${inStockCount} in-stock boxes available for matching`
          : "Loading…"}
        {query ? ` · ${filtered.length} match${filtered.length === 1 ? "" : "es"}` : ""}
      </p>

      <div className="flex flex-col gap-2">
        {filtered.map((box) => (
          <div key={box.id} id={`box-${box.id}`}>
            <InventoryRow
              box={box}
              expanded={expanded.has(box.id)}
              onToggleExpand={() => toggle(box.id)}
            />
          </div>
        ))}
        {ready && filtered.length === 0 ? (
          <p className="text-muted-foreground py-8 text-center text-sm">
            {query
              ? `No boxes match “${query}”.`
              : "No boxes yet — add one to get started."}
          </p>
        ) : null}
      </div>
    </div>
  );
}
