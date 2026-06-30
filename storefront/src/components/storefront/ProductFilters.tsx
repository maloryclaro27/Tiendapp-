import Link from "next/link";
import { Search, SlidersHorizontal } from "lucide-react";

import type { Brand, ProductFilters as ApiProductFilters } from "@/lib/api";

type ProductFiltersProps = {
  brands: Brand[];
  filters: ApiProductFilters;
};

const availabilityOptions = [
  { value: "", label: "Todos los estados" },
  { value: "healthy_stock", label: "Stock saludable" },
  { value: "low_stock", label: "Bajo stock" },
  { value: "out_of_stock", label: "Sin stock" },
  { value: "available", label: "Disponibles" },
];

const unitOptions = [
  { value: "", label: "Todas las unidades" },
  { value: "Unidad", label: "Unidad" },
  { value: "Display", label: "Display" },
  { value: "Caja", label: "Caja" },
];

const sortOptions = [
  { value: "", label: "Más recientes" },
  { value: "name", label: "Nombre A-Z" },
  { value: "stock_desc", label: "Mayor inventario" },
  { value: "stock_asc", label: "Menor inventario" },
  { value: "updated_asc", label: "Actualización antigua" },
];

function toInputValue(value: string | number | undefined) {
  return value === undefined ? "" : String(value);
}

export function ProductFilters({ brands, filters }: ProductFiltersProps) {
  return (
    <section className="mx-auto max-w-7xl px-6 pt-8" aria-label="Filtros del catálogo">
      <div className="rounded-[2rem] border border-black/[0.06] bg-white/90 p-5 shadow-xl shadow-black/[0.04] backdrop-blur">
        <div className="mb-5 flex items-center gap-3">
          <span className="grid h-11 w-11 place-items-center rounded-2xl bg-[#EAF2FF] text-[#0B6FEF]">
            <SlidersHorizontal className="h-5 w-5" />
          </span>

          <div>
            <p className="text-sm font-black uppercase tracking-[0.25em] text-[#0B6FEF]">
              Filtros
            </p>
            <p className="text-sm text-[#10213F]/55">
              Busca productos por marca, unidad, disponibilidad o inventario.
            </p>
          </div>
        </div>

        <form action="/" className="grid gap-3 lg:grid-cols-[1.4fr_1fr_1fr_1fr_1fr_auto_auto]">
          <label className="flex items-center gap-3 rounded-2xl border border-black/[0.06] bg-[#F8F6F1] px-4 py-3">
            <Search className="h-4 w-4 text-[#10213F]/35" />
            <span className="sr-only">Buscar productos</span>
            <input
              name="search"
              defaultValue={toInputValue(filters.search)}
              placeholder="Buscar producto o referencia"
              className="w-full bg-transparent text-sm font-semibold text-[#10213F] outline-none placeholder:text-[#10213F]/35"
            />
          </label>

          <label className="sr-only" htmlFor="brand_id">
            Marca
          </label>
          <select
            id="brand_id"
            name="brand_id"
            defaultValue={toInputValue(filters.brand_id)}
            className="cursor-pointer rounded-2xl border border-black/[0.06] bg-[#F8F6F1] px-4 py-3 text-sm font-semibold text-[#10213F]/75 outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            <option value="">Todas las marcas</option>
            {brands.map((brand) => (
              <option key={brand.id} value={brand.id}>
                {brand.name}
              </option>
            ))}
          </select>

          <label className="sr-only" htmlFor="unit_of_measure">
            Unidad de medida
          </label>
          <select
            id="unit_of_measure"
            name="unit_of_measure"
            defaultValue={toInputValue(filters.unit_of_measure)}
            className="cursor-pointer rounded-2xl border border-black/[0.06] bg-[#F8F6F1] px-4 py-3 text-sm font-semibold text-[#10213F]/75 outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            {unitOptions.map((option) => (
              <option key={option.label} value={option.value}>
                {option.label}
              </option>
            ))}
          </select>

          <label className="sr-only" htmlFor="availability">
            Disponibilidad
          </label>
          <select
            id="availability"
            name="availability"
            defaultValue={toInputValue(filters.availability)}
            className="cursor-pointer rounded-2xl border border-black/[0.06] bg-[#F8F6F1] px-4 py-3 text-sm font-semibold text-[#10213F]/75 outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            {availabilityOptions.map((option) => (
              <option key={option.label} value={option.value}>
                {option.label}
              </option>
            ))}
          </select>

          <label className="sr-only" htmlFor="sort">
            Orden
          </label>
          <select
            id="sort"
            name="sort"
            defaultValue={toInputValue(filters.sort)}
            className="cursor-pointer rounded-2xl border border-black/[0.06] bg-[#F8F6F1] px-4 py-3 text-sm font-semibold text-[#10213F]/75 outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            {sortOptions.map((option) => (
              <option key={option.label} value={option.value}>
                {option.label}
              </option>
            ))}
          </select>

          <button
            type="submit"
            className="rounded-2xl bg-[#0B6FEF] px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-[#0D47C9] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            Filtrar
          </button>

          <Link
            href="/"
            className="rounded-2xl border border-black/[0.06] bg-white px-5 py-3 text-center text-sm font-black text-[#10213F]/65 transition hover:bg-[#F6F3EC] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            Limpiar
          </Link>
        </form>
      </div>
    </section>
  );
}
