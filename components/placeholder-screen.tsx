import { Card } from "@/components/ui/card";

/** A visible-but-empty placeholder for tabs that are intentionally out of scope. */
export function PlaceholderScreen({
  title,
  note,
}: {
  title: string;
  note: string;
}) {
  return (
    <div className="flex flex-col gap-4">
      <h1 className="text-lg font-semibold">{title}</h1>
      <Card className="items-center gap-2 py-12 text-center">
        <p className="font-medium">Nothing here yet</p>
        <p className="text-muted-foreground max-w-sm text-sm">{note}</p>
      </Card>
    </div>
  );
}
