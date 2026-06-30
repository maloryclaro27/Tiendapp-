"use client";

import Image from "next/image";
import { ArrowLeft, ArrowRight, Sparkles } from "lucide-react";
import { useState } from "react";

import type { Product } from "@/lib/api";

import { StatusPill } from "./StatusPill";
import { getProductImageSrc } from "./storefront-assets";

export function FeaturedProducts({ products }: { products: Product[] }) {
  const featuredProducts = products.slice(0, 4);
  const [activeIndex, setActiveIndex] = useState(0);

  if (featuredProducts.length === 0) {
    return null;
  }

  const activeProduct = featuredProducts[activeIndex];

  function previousProduct() {
    setActiveIndex((current) =>
      current === 0 ? featuredProducts.length - 1 : current - 1,
    );
  }

  function nextProduct() {
    setActiveIndex((current) =>
      current === featuredProducts.length - 1 ? 0 : current + 1,
    );
  }

  return (
    <section id="destacados" className="mx-auto max-w-7xl px-6 py-16">
      <div className="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
          <p className="text-sm font-bold uppercase tracking-[0.28em] text-[#0B6FEF]">
            Destacados
          </p>

          <h2 className="mt-3 text-4xl font-black tracking-tight text-[#10213F]">
            Productos destacados del inventario
          </h2>
        </div>

        <p className="max-w-md text-sm leading-6 text-[#10213F]/58">
          Una vitrina dinámica alimentada con productos reales del catálogo
          administrativo.
        </p>
      </div>

      <div className="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <FeaturedProductCard
          product={activeProduct}
          onPrevious={previousProduct}
          onNext={nextProduct}
        />

        <div className="grid gap-4">
          {featuredProducts.map((product, index) => (
            <button
              key={product.id}
              type="button"
              onClick={() => setActiveIndex(index)}
              className="group flex min-h-[118px] items-center gap-4 rounded-[1.35rem] border border-black/[0.06] bg-white/85 p-4 text-left shadow-sm shadow-black/[0.03] transition hover:-translate-y-1 hover:border-[#0B6FEF]/30 hover:shadow-xl hover:shadow-blue-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
            >
              <ProductMiniature product={product} />

              <div className="min-w-0 flex-1">
                <p className="text-xs font-bold uppercase tracking-[0.18em] text-[#0B6FEF]">
                  {product.brand?.name ?? "Marca"}
                </p>

                <h3 className="mt-1 line-clamp-1 text-lg font-black tracking-tight text-[#10213F]">
                  {product.name}
                </h3>

                <p className="mt-1 text-sm font-semibold text-[#10213F]/45">
                  {product.quantity_in_inventory} unidades · {product.inventory_status}
                </p>
              </div>

              <span
                className={
                  index === activeIndex
                    ? "h-3 w-3 rounded-full bg-[#FFB000]"
                    : "h-3 w-3 rounded-full bg-[#10213F]/10"
                }
              />
            </button>
          ))}
        </div>
      </div>
    </section>
  );
}

function FeaturedProductCard({
  product,
  onPrevious,
  onNext,
}: {
  product: Product;
  onPrevious: () => void;
  onNext: () => void;
}) {
  return (
    <article className="relative overflow-hidden rounded-[2rem] bg-[#10213F] p-6 text-white shadow-2xl shadow-black/10">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_25%_20%,rgba(11,111,239,0.20),transparent_34%),radial-gradient(circle_at_90%_10%,rgba(255,176,0,0.08),transparent_26%)]" />

      <div className="relative mb-6 flex items-center justify-between gap-4">
        <span className="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium ring-1 ring-white/10">
          <Sparkles className="h-4 w-4" />
          Producto en vitrina
        </span>

        <div className="flex items-center gap-3">
          <button
            type="button"
            onClick={onPrevious}
            className="grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
            aria-label="Producto anterior"
          >
            <ArrowLeft className="h-5 w-5" />
          </button>

          <button
            type="button"
            onClick={onNext}
            className="grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
            aria-label="Producto siguiente"
          >
            <ArrowRight className="h-5 w-5" />
          </button>
        </div>
      </div>

      <div className="relative rounded-[1.75rem] border border-white/10 bg-white/[0.04] p-4">
        <div className="relative mx-auto flex h-[280px] max-w-[420px] items-center justify-center rounded-[1.5rem] bg-white shadow-xl shadow-black/15">
          <Image
            src={getProductImageSrc(product.name)}
            alt={product.name}
            width={360}
            height={260}
            className="h-auto max-h-[240px] w-auto max-w-[360px] object-contain p-2"
          />
        </div>
      </div>

      <div className="relative mt-6">
        <p className="text-xs font-bold uppercase tracking-[0.32em] text-white/45">
          {product.brand?.name ?? "Marca"} · {product.brand?.reference ?? "Sin referencia"}
        </p>

        <h3 className="mt-3 text-3xl font-semibold leading-tight tracking-tight text-white">
          {product.name}
        </h3>

        <p className="mt-4 text-base leading-7 text-white/70">
          {product.observations}
        </p>

        <div className="mt-5 flex flex-wrap items-center gap-2.5">
          <StatusPill status={product.inventory_status} />
          <span className="rounded-full bg-white/10 px-4 py-2 text-sm font-bold ring-1 ring-white/10">
            {product.quantity_in_inventory} unidades
          </span>
          <span className="rounded-full bg-white/10 px-4 py-2 text-sm font-bold ring-1 ring-white/10">
            {product.unit_of_measure}
          </span>
        </div>
      </div>
    </article>
  );
}

function ProductMiniature({ product }: { product: Product }) {
  return (
    <div className="relative grid h-20 w-20 shrink-0 place-items-center overflow-hidden rounded-2xl bg-white shadow-inner">
      <Image
        src={getProductImageSrc(product.name)}
        alt={product.name}
        width={96}
        height={96}
        className="h-full w-full object-contain p-2 transition group-hover:scale-105"
      />
    </div>
  );
}
