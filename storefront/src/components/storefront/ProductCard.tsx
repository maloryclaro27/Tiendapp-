"use client";

import Image from "next/image";
import { Tag } from "lucide-react";
import { useState } from "react";

import type { Product } from "@/lib/api";
import { cn } from "@/lib/utils";

import { getBrandImageSrc, getProductImageSrc } from "./storefront-assets";
import { StatusPill } from "./StatusPill";

export function ProductCard({ product }: { product: Product }) {
  const [productImageFailed, setProductImageFailed] = useState(false);
  const [brandImageFailed, setBrandImageFailed] = useState(false);

  const brandName = product.brand?.name ?? "Marca";
  const brandReference = product.brand?.reference ?? "Sin referencia";
  const productImageSrc = getProductImageSrc(product.name);
  const brandImageSrc = getBrandImageSrc(brandName);

  return (
    <article className="group overflow-hidden rounded-[1.75rem] border border-black/[0.06] bg-white shadow-sm shadow-black/[0.04] transition hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10">
      <div className="relative overflow-hidden bg-[#F8F6F1] p-5">
        <div className="relative flex h-56 items-center justify-center rounded-[1.5rem] bg-white shadow-inner">
          {!productImageFailed ? (
            <Image
              src={productImageSrc}
              alt={product.name}
              fill
              sizes="(min-width: 1280px) 25vw, (min-width: 640px) 50vw, 100vw"
              className="object-contain p-5 transition duration-500 group-hover:scale-105"
              onError={() => setProductImageFailed(true)}
            />
          ) : (
            <div className="grid h-24 w-24 place-items-center rounded-2xl bg-[#EAF2FF] text-2xl font-black text-[#0B6FEF]">
              {brandName.slice(0, 2).toUpperCase()}
            </div>
          )}
        </div>

        <span className="absolute left-7 top-7 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold text-[#10213F] shadow-sm ring-1 ring-black/[0.06] backdrop-blur">
          <Tag className="h-3.5 w-3.5" />
          {brandReference}
        </span>
      </div>

      <div className="p-6">
        <div className="mb-4 flex items-center gap-3">
          <div className="relative grid h-11 w-11 shrink-0 place-items-center overflow-hidden rounded-xl bg-[#F6F3EC]">
            {!brandImageFailed ? (
              <Image
                src={brandImageSrc}
                alt={brandName}
                width={64}
                height={64}
                className="h-full w-full object-cover"
                onError={() => setBrandImageFailed(true)}
              />
            ) : (
              <span className="text-sm font-black text-[#0B6FEF]">
                {brandName.slice(0, 2).toUpperCase()}
              </span>
            )}
          </div>

          <div className="min-w-0">
            <p className="truncate text-sm font-bold text-[#10213F]">
              {brandName}
            </p>
            <p className="text-xs font-medium text-[#10213F]/45">
              {product.unit_of_measure}
            </p>
          </div>
        </div>

        <h3 className="line-clamp-2 text-xl font-black tracking-tight text-[#10213F]">
          {product.name}
        </h3>

        <p className="mt-3 line-clamp-2 text-sm leading-6 text-[#10213F]/60">
          {product.observations}
        </p>

        <div className="mt-5 flex flex-wrap items-center gap-2">
          <StatusPill status={product.inventory_status} />

          <span
            className={cn(
              "rounded-full px-4 py-2 text-sm font-bold ring-1",
              product.is_available
                ? "bg-blue-50 text-[#0B6FEF] ring-blue-100"
                : "bg-slate-100 text-slate-500 ring-slate-200",
            )}
          >
            {product.quantity_in_inventory} unidades
          </span>
        </div>
      </div>
    </article>
  );
}
