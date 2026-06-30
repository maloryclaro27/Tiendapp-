import { AlertTriangle, BadgeCheck, Boxes, PackageCheck, Sparkles } from "lucide-react";

import type { CatalogMetrics } from "@/lib/api";

type StorefrontHeroProps = {
  metrics: CatalogMetrics;
};

export function StorefrontHero({ metrics }: StorefrontHeroProps) {
  return (
    <section className="relative overflow-hidden bg-[#F6F3EC] px-6 py-16">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_12%_10%,rgba(11,111,239,0.13),transparent_30%),radial-gradient(circle_at_88%_8%,rgba(255,176,0,0.14),transparent_28%)]" />

      <div className="relative mx-auto grid max-w-7xl gap-10 lg:grid-cols-[1.08fr_0.92fr] lg:items-center">
        <div>
          <div className="inline-flex items-center gap-2 rounded-full border border-black/[0.06] bg-white/80 px-4 py-2 text-sm font-bold text-[#0B6FEF] shadow-sm shadow-black/[0.03]">
            <Sparkles className="h-4 w-4" />
            Catálogo ecommerce conectado al inventario
          </div>

          <h1 className="mt-7 max-w-4xl text-5xl font-black leading-[0.98] tracking-tight text-[#10213F] md:text-7xl">
            Una experiencia moderna para explorar productos, marcas y disponibilidad.
          </h1>

          <p className="mt-7 max-w-2xl text-lg leading-8 text-[#10213F]/68">
            Consulta el catálogo publicado desde el panel administrativo con datos
            reales de inventario, estado comercial y marcas disponibles.
          </p>

          <div className="mt-9 flex flex-wrap gap-3">
            <a
              href="#catalogo"
              className="rounded-full bg-[#0B6FEF] px-6 py-3 text-sm font-black text-white shadow-xl shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-[#0D47C9] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
            >
              Explorar catálogo
            </a>

            <a
              href="#marcas"
              className="rounded-full border border-black/[0.08] bg-white px-6 py-3 text-sm font-black text-[#10213F] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0B6FEF]/30 hover:text-[#0B6FEF] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
            >
              Ver marcas
            </a>
          </div>
        </div>

        <div className="rounded-[2rem] border border-black/[0.06] bg-white/85 p-5 shadow-2xl shadow-black/[0.06] backdrop-blur">
          <div className="rounded-[1.65rem] bg-[#10213F] p-6 text-white">
            <p className="text-sm font-bold uppercase tracking-[0.28em] text-white/45">
              Estado del catálogo
            </p>

            <div className="mt-6 grid grid-cols-2 gap-4">
              <MetricCard
                icon={PackageCheck}
                label="Productos"
                value={metrics.total_products}
                helper="Publicados"
              />
              <MetricCard
                icon={Boxes}
                label="Unidades"
                value={metrics.total_inventory_units}
                helper="En inventario"
              />
              <MetricCard
                icon={BadgeCheck}
                label="Disponibles"
                value={metrics.available_products}
                helper={`${metrics.available_percent}% del catálogo`}
              />
              <MetricCard
                icon={AlertTriangle}
                label="Alertas"
                value={metrics.stock_alerts}
                helper="Bajo o sin stock"
              />
            </div>

            <div className="mt-5 rounded-[1.25rem] bg-white/10 p-4 ring-1 ring-white/10">
              <div className="flex items-center justify-between gap-4 text-sm font-bold">
                <span>Salud del inventario</span>
                <span>{metrics.stock_health_percent}%</span>
              </div>

              <div className="mt-3 h-2 overflow-hidden rounded-full bg-white/10">
                <div
                  className="h-full rounded-full bg-[#FFB000]"
                  style={{ width: `${metrics.stock_health_percent}%` }}
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

function MetricCard({
  icon: Icon,
  label,
  value,
  helper,
}: {
  icon: React.ComponentType<{ className?: string }>;
  label: string;
  value: number;
  helper: string;
}) {
  return (
    <div className="rounded-[1.25rem] bg-white/10 p-4 ring-1 ring-white/10">
      <Icon className="h-5 w-5 text-[#FFB000]" />
      <p className="mt-4 text-3xl font-black tracking-tight">{value}</p>
      <p className="mt-1 text-sm font-bold text-white/88">{label}</p>
      <p className="mt-1 text-xs font-medium text-white/45">{helper}</p>
    </div>
  );
}
