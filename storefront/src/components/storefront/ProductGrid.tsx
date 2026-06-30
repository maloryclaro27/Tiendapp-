import type { PaginatedResponse, Product, ProductFilters } from "@/lib/api";

import { ProductCard } from "./ProductCard";

type ProductGridProps = {
  products: Product[];
  meta: PaginatedResponse<Product>["meta"];
  filters: ProductFilters;
};

function buildPageHref(filters: ProductFilters, page: number) {
  const searchParams = new URLSearchParams();

  Object.entries({
    ...filters,
    page,
  }).forEach(([key, value]) => {
    if (value === undefined || value === null || value === "") {
      return;
    }

    searchParams.set(key, String(value));
  });

  return `/?${searchParams.toString()}#catalogo`;
}

export function ProductGrid({ products, meta, filters }: ProductGridProps) {
  if (products.length === 0) {
    return (
      <section
        id="catalogo"
        className="mx-auto max-w-7xl px-6 py-16"
        aria-live="polite"
      >
        <div className="rounded-[2rem] border border-dashed border-black/10 bg-white p-10 text-center shadow-sm">
          <p className="text-sm font-bold uppercase tracking-[0.28em] text-[#0B6FEF]">
            Sin resultados
          </p>

          <h2 className="mt-4 text-3xl font-black tracking-tight text-[#10213F]">
            No encontramos productos para esta búsqueda.
          </h2>

          <p className="mx-auto mt-4 max-w-xl text-base leading-7 text-[#10213F]/60">
            Ajusta la búsqueda, cambia la marca o limpia los filtros para volver
            a ver el catálogo.
          </p>
        </div>
      </section>
    );
  }

  return (
    <section id="catalogo" className="mx-auto max-w-7xl px-6 py-16">
      <div className="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
          <p className="text-sm font-bold uppercase tracking-[0.28em] text-[#0B6FEF]">
            Catálogo
          </p>

          <h2 className="mt-3 text-4xl font-black tracking-tight text-[#10213F]">
            Productos publicados
          </h2>

          <p className="mt-3 text-sm font-semibold text-[#10213F]/45">
            Mostrando {meta.from}–{meta.to} de {meta.total} productos
          </p>
        </div>

        <p className="max-w-md text-sm leading-6 text-[#10213F]/58">
          Productos sincronizados desde el catálogo administrativo, con marca,
          referencia, unidad de medida y estado de inventario.
        </p>
      </div>

      <div className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        {products.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>

      {meta.last_page > 1 ? (
        <nav
          className="mt-10 flex flex-col items-center justify-between gap-4 rounded-[1.5rem] border border-black/[0.06] bg-white/80 p-4 shadow-sm shadow-black/[0.03] md:flex-row"
          aria-label="Paginación del catálogo"
        >
          <p className="text-sm font-bold text-[#10213F]/55">
            Página {meta.current_page} de {meta.last_page}
          </p>

          <div className="flex flex-wrap items-center justify-center gap-2">
            {meta.current_page > 1 ? (
              <a
                href={buildPageHref(filters, meta.current_page - 1)}
                className="rounded-full border border-black/[0.08] bg-white px-4 py-2 text-sm font-black text-[#10213F] transition hover:-translate-y-0.5 hover:border-[#0B6FEF]/30 hover:text-[#0B6FEF] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
              >
                Anterior
              </a>
            ) : (
              <span className="cursor-not-allowed rounded-full border border-black/[0.04] bg-[#10213F]/5 px-4 py-2 text-sm font-black text-[#10213F]/30">
                Anterior
              </span>
            )}

            {Array.from({ length: meta.last_page }).map((_, index) => {
              const page = index + 1;
              const isActive = page === meta.current_page;

              return isActive ? (
                <span
                  key={page}
                  className="grid h-10 min-w-10 place-items-center rounded-full bg-[#0B6FEF] px-3 text-sm font-black text-white shadow-lg shadow-blue-500/20"
                  aria-current="page"
                >
                  {page}
                </span>
              ) : (
                <a
                  key={page}
                  href={buildPageHref(filters, page)}
                  className="grid h-10 min-w-10 place-items-center rounded-full border border-black/[0.08] bg-white px-3 text-sm font-black text-[#10213F] transition hover:-translate-y-0.5 hover:border-[#0B6FEF]/30 hover:text-[#0B6FEF] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
                >
                  {page}
                </a>
              );
            })}

            {meta.current_page < meta.last_page ? (
              <a
                href={buildPageHref(filters, meta.current_page + 1)}
                className="rounded-full bg-[#0B6FEF] px-4 py-2 text-sm font-black text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-[#0D47C9] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
              >
                Siguiente
              </a>
            ) : (
              <span className="cursor-not-allowed rounded-full border border-black/[0.04] bg-[#10213F]/5 px-4 py-2 text-sm font-black text-[#10213F]/30">
                Siguiente
              </span>
            )}
          </div>
        </nav>
      ) : null}
    </section>
  );
}
