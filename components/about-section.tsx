import { ArrowUpRight, Star } from "lucide-react";

const GITHUB_URL = "https://github.com/FutureNathan/Ezpack";
const SITE_URL = "https://nathantowianski.com";

/** Free & open-source / "made with love" footer, shown at the bottom of Settings. */
export function AboutSection() {
  return (
    <section className="flex flex-col gap-3">
      <h2 className="text-muted-foreground text-xs font-semibold uppercase tracking-wider">
        About
      </h2>

      <a
        href={GITHUB_URL}
        target="_blank"
        rel="noopener noreferrer"
        className="hover:bg-accent flex items-center justify-center gap-2 rounded-lg border px-4 py-3 font-medium transition-colors"
      >
        <Star className="size-4 fill-current" />
        Free &amp; open source on GitHub
      </a>

      <p className="text-muted-foreground text-center text-sm">
        View the code, file an idea, or make it your own.
      </p>

      {/* The whole line is a button to Nathan's site. */}
      <a
        href={SITE_URL}
        target="_blank"
        rel="noopener noreferrer"
        title="Visit Nathan's website"
        className="group hover:bg-accent mx-auto mt-1 inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm transition-colors"
      >
        <span>Made with</span>
        <span aria-hidden="true">❤️</span>
        <span>by</span>
        <span className="font-semibold underline decoration-dotted underline-offset-2 group-hover:decoration-solid">
          Nathan
        </span>
        {/* Nathan's black & white astronaut (public/astronaut.png). */}
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img src="/astronaut.png" alt="" aria-hidden="true" className="h-7 w-auto" />
        <ArrowUpRight className="text-muted-foreground size-3.5" />
      </a>

      <p className="text-muted-foreground text-center text-xs">
        Questions, feedback, or just want to talk?{" "}
        <a
          href={SITE_URL}
          target="_blank"
          rel="noopener noreferrer"
          className="hover:text-foreground underline underline-offset-2"
        >
          Reach out anytime.
        </a>
      </p>

      <p className="text-muted-foreground/70 text-center text-[11px]">
        EZ Pack · MIT licensed · free &amp; open source
      </p>
    </section>
  );
}
