"use client";

import * as React from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { Home, ReceiptText, Settings } from "lucide-react";

import { cn } from "@/lib/utils";

const TABS = [
  { href: "/", label: "Find Box" },
  { href: "/inventory", label: "Inventory" },
  { href: "/billing", label: "Billing" },
  { href: "/support", label: "Support" },
] as const;

function isActive(pathname: string, href: string): boolean {
  if (href === "/") return pathname === "/";
  return pathname === href || pathname.startsWith(`${href}/`);
}

export function AppShell({ children }: { children: React.ReactNode }) {
  const pathname = usePathname() ?? "/";

  return (
    <div className="flex min-h-dvh flex-col">
      <header className="bg-background/90 sticky top-0 z-20 border-b backdrop-blur">
        <div className="mx-auto flex h-14 w-full max-w-3xl items-center gap-2 px-3">
          <Link
            href="/"
            aria-label="Home"
            className="hover:bg-accent flex size-9 items-center justify-center rounded-md transition-colors"
          >
            <Home className="size-5" />
          </Link>

          <Link href="/" className="mr-auto flex items-baseline gap-1.5">
            <span className="text-lg font-bold tracking-tight">EZ Pack</span>
            <span className="text-muted-foreground hidden text-xs sm:inline">
              packing estimator
            </span>
          </Link>

          <Link
            href="/history"
            aria-label="History"
            className={cn(
              "hover:bg-accent flex size-9 items-center justify-center rounded-md transition-colors",
              isActive(pathname, "/history") && "bg-accent"
            )}
          >
            <ReceiptText className="size-5" />
          </Link>
          <Link
            href="/settings"
            aria-label="Settings"
            className={cn(
              "hover:bg-accent flex size-9 items-center justify-center rounded-md transition-colors",
              isActive(pathname, "/settings") && "bg-accent"
            )}
          >
            <Settings className="size-5" />
          </Link>
        </div>

        <nav className="mx-auto flex w-full max-w-3xl items-center gap-1 px-3 pb-0">
          {TABS.map((tab) => {
            const active = isActive(pathname, tab.href);
            return (
              <Link
                key={tab.href}
                href={tab.href}
                className={cn(
                  "relative -mb-px border-b-2 px-3 py-2 text-sm font-medium transition-colors",
                  active
                    ? "border-primary text-foreground"
                    : "text-muted-foreground hover:text-foreground border-transparent"
                )}
              >
                {tab.label}
              </Link>
            );
          })}
        </nav>
      </header>

      <main className="mx-auto w-full max-w-3xl flex-1 px-3 py-5">
        {children}
      </main>
    </div>
  );
}
