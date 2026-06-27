"use client";

import * as React from "react";
import { Check, Copy } from "lucide-react";

import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

interface CopyButtonProps
  extends Omit<React.ComponentProps<typeof Button>, "value" | "onClick"> {
  /** The exact text placed on the clipboard. */
  value: string;
  /** Visible label; falls back to the value. */
  label?: React.ReactNode;
  /** Fired after a successful copy (e.g. to log a history entry). */
  onCopied?: (value: string) => void;
}

/**
 * One-tap copy. Writes `value` to the clipboard and shows a "Copied!" state.
 * This is the value a counter worker pastes into their shipping software.
 */
export function CopyButton({
  value,
  label,
  onCopied,
  variant = "default",
  size = "default",
  className,
  ...props
}: CopyButtonProps) {
  const [copied, setCopied] = React.useState(false);
  const timer = React.useRef<ReturnType<typeof setTimeout> | null>(null);

  React.useEffect(
    () => () => {
      if (timer.current) clearTimeout(timer.current);
    },
    []
  );

  async function copy() {
    try {
      if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(value);
      } else {
        // Fallback for non-secure contexts.
        const ta = document.createElement("textarea");
        ta.value = value;
        ta.style.position = "fixed";
        ta.style.opacity = "0";
        document.body.appendChild(ta);
        ta.select();
        document.execCommand("copy");
        document.body.removeChild(ta);
      }
      setCopied(true);
      onCopied?.(value);
      if (timer.current) clearTimeout(timer.current);
      timer.current = setTimeout(() => setCopied(false), 1400);
    } catch {
      // ignore — clipboard may be unavailable
    }
  }

  return (
    <Button
      type="button"
      variant={copied ? "secondary" : variant}
      size={size}
      onClick={copy}
      aria-label={copied ? "Copied" : `Copy ${value}`}
      className={cn(className)}
      {...props}
    >
      {copied ? <Check className="text-success" /> : <Copy />}
      {copied ? "Copied!" : (label ?? value)}
    </Button>
  );
}
