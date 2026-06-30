import type { Product } from "@/lib/api";

import { ProductCard } from "./ProductCard";

export function ProductGrid({ products }: { products: Product[] }) {
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
    </section>
  );
}
