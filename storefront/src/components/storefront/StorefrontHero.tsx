"use client";

import {
  AlertTriangle,
  ArrowRight,
  BadgeCheck,
  Box,
  Boxes,
  PackageCheck,
  ShieldCheck,
  Store,
} from "lucide-react";
import { useEffect, useState } from "react";

import type { CatalogMetrics } from "@/lib/api";
import { cn } from "@/lib/utils";

type StorefrontHeroProps = {
  metrics: CatalogMetrics;
};

const heroSlides = [
  {
    eyebrow: "Catálogo conectado",
    title: "Productos y marcas en una vitrina moderna.",
    description:
      "Una experiencia pública conectada al panel administrativo para consultar productos, marcas y disponibilidad en tiempo real.",
    primaryLabel: "Explorar catálogo",
    primaryHref: "#catalogo",
    secondaryLabel: "Ver marcas",
    secondaryHref: "#marcas",
    icon: Store,
  },
  {
    eyebrow: "Alertas operativas",
    title: "Bajo stock y sin stock convertidos en señales visuales.",
    description:
      "Las alertas se vuelven útiles para priorizar reposición, publicación y gestión comercial.",
    primaryLabel: "Revisar alertas",
    primaryHref: "#catalogo",
    secondaryLabel: "Ver destacados",
    secondaryHref: "#destacados",
    icon: AlertTriangle,
  },
  {
    eyebrow: "Vista comercial",
    title: "Inventario claro para vender con más criterio.",
    description:
      "El storefront permite navegar por marca, unidad de medida, búsqueda y estado de inventario.",
    primaryLabel: "Ver productos",
    primaryHref: "#catalogo",
    secondaryLabel: "Ver destacados",
    secondaryHref: "#destacados",
    icon: BadgeCheck,
  },
];

export function StorefrontHero({ metrics }: StorefrontHeroProps) {
  const [activeSlide, setActiveSlide] = useState(0);

  const slide = heroSlides[activeSlide];
  const SlideIcon = slide.icon;

  useEffect(() => {
    const timer = window.setTimeout(() => {
      setActiveSlide((current) =>
        current === heroSlides.length - 1 ? 0 : current + 1,
      );
    }, 6500);

    return () => window.clearTimeout(timer);
  }, [activeSlide]);

  return (
    <section className="relative overflow-hidden bg-[#F6F3EC] px-6 py-12">
      <div className="absolute inset-0 bg-[linear-gradient(rgba(16,33,63,0.035)_1px,transparent_1px),linear-gradient(90deg,rgba(16,33,63,0.035)_1px,transparent_1px)] bg-[size:32px_32px]" />

      <div className="relative mx-auto max-w-7xl">
        <div className="grid overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#10213F] via-[#0D47C9] to-[#0B6FEF] p-8 shadow-2xl shadow-blue-950/16 md:p-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:p-12">
          <div className="relative z-10 max-w-3xl">
            <div className="inline-flex items-center gap-2 rounded-full border border-white/18 bg-white/12 px-4 py-2 text-sm font-bold text-white shadow-sm backdrop-blur">
              <SlideIcon className="h-4 w-4 text-[#FFB000]" />
              {slide.eyebrow}
            </div>

            <h1 className="mt-7 max-w-3xl text-5xl font-black leading-[1.02] tracking-tight text-white md:text-6xl">
              {slide.title}
            </h1>

            <p className="mt-7 max-w-2xl text-lg leading-8 text-white/72">
              {slide.description}
            </p>

            <div className="mt-9 flex flex-wrap items-center gap-3">
              <a
                href={slide.primaryHref}
                className="inline-flex items-center gap-2 rounded-full bg-[#FFB000] px-6 py-3 text-sm font-black text-[#10213F] shadow-xl shadow-amber-500/20 transition hover:-translate-y-0.5 hover:bg-[#FFC247] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
              >
                {slide.primaryLabel}
                <ArrowRight className="h-4 w-4" />
              </a>

              <a
                href={slide.secondaryHref}
                className="rounded-full border border-white/16 bg-white/8 px-6 py-3 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-white/14 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
              >
                {slide.secondaryLabel}
              </a>
            </div>

            <div className="mt-9 flex items-center gap-2">
              {heroSlides.map((item, index) => (
                <button
                  key={item.eyebrow}
                  type="button"
                  onClick={() => setActiveSlide(index)}
                  className={cn(
                    "h-2.5 rounded-full transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]",
                    index === activeSlide
                      ? "w-10 bg-[#FFB000]"
                      : "w-2.5 bg-white/35 hover:bg-white/60",
                  )}
                  aria-label={`Ir al slide ${index + 1}`}
                />
              ))}
            </div>
          </div>

          <div className="relative mt-10 lg:mt-0">
            <div className="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-[#FFC247]/18 blur-3xl" />
            <div className="absolute -bottom-20 left-0 h-56 w-56 rounded-full bg-white/10 blur-3xl" />

            <div className="relative ml-auto max-w-[430px] rounded-[2rem] border border-white/15 bg-white/10 p-5 shadow-2xl shadow-blue-950/20 backdrop-blur">
              <div className="rounded-[1.65rem] bg-white p-6 text-[#10213F] shadow-xl shadow-black/10">
                <div className="mb-6 flex items-start justify-between gap-5">
                  <div>
                    <p className="text-xs font-black uppercase tracking-[0.32em] text-[#10213F]/35">
                      Vista comercial
                    </p>
                    <h2 className="mt-3 text-2xl font-black tracking-tight">
                      Inventario publicado
                    </h2>
                  </div>

                  <span className="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-[#0B6FEF] text-white shadow-lg shadow-blue-500/25">
                    <Store className="h-7 w-7" />
                  </span>
                </div>

                <div className="grid gap-3">
                  <HeroMetric
                    icon={PackageCheck}
                    value={metrics.available_products}
                    label="Disponibles"
                    tone="blue"
                  />
                  <HeroMetric
                    icon={Boxes}
                    value={metrics.total_inventory_units}
                    label="Unidades"
                    tone="blue"
                  />
                  <HeroMetric
                    icon={AlertTriangle}
                    value={metrics.stock_alerts}
                    label="Alertas"
                    tone="amber"
                  />
                </div>

                <div className="mt-5 rounded-2xl bg-[#F6F3EC] p-5">
                  <div className="flex items-center justify-between gap-4">
                    <div>
                      <p className="text-xs font-black uppercase tracking-[0.32em] text-[#10213F]/35">
                        Salud
                      </p>
                      <p className="mt-1 text-4xl font-black">
                        {metrics.stock_health_percent}%
                      </p>
                    </div>

                    <span className="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-emerald-500">
                      <ShieldCheck className="h-7 w-7" />
                    </span>
                  </div>

                  <div className="mt-4 h-2 overflow-hidden rounded-full bg-[#10213F]/10">
                    <div
                      className="h-full rounded-full bg-[#FFB000]"
                      style={{
                        width: `${Math.min(Math.max(metrics.stock_health_percent, 0), 100)}%`,
                      }}
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <Box className="pointer-events-none absolute bottom-8 right-[48%] hidden h-28 w-28 rotate-12 text-white/[0.04] lg:block" />
        </div>
      </div>
    </section>
  );
}

function HeroMetric({
  icon: Icon,
  value,
  label,
  tone,
}: {
  icon: typeof PackageCheck;
  value: number;
  label: string;
  tone: "blue" | "amber";
}) {
  return (
    <div className="flex items-center gap-4 rounded-2xl bg-[#F6F3EC] p-4">
      <span
        className={cn(
          "grid h-12 w-12 shrink-0 place-items-center rounded-2xl",
          tone === "blue"
            ? "bg-blue-50 text-[#0B6FEF]"
            : "bg-amber-50 text-[#F59E0B]",
        )}
      >
        <Icon className="h-6 w-6" />
      </span>

      <div>
        <p className="text-2xl font-black leading-none">{value}</p>
        <p className="mt-1 text-xs font-black uppercase tracking-[0.28em] text-[#10213F]/40">
          {label}
        </p>
      </div>
    </div>
  );
}
