"use client";

import Image from "next/image";
import { Building2 } from "lucide-react";
import { useState } from "react";

import type { Brand } from "@/lib/api";

import { getBrandImageSrc } from "./storefront-assets";

export function BrandRail({ brands }: { brands: Brand[] }) {
  if (brands.length === 0) {
    return null;
  }

  return (
    <section id="marcas" className="mx-auto max-w-7xl px-6 py-16">
      <div className="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
          <p className="text-sm font-bold uppercase tracking-[0.28em] text-[#0B6FEF]">
            Marcas
          </p>

          <h2 className="mt-3 text-4xl font-black tracking-tight text-[#10213F]">
            Marcas publicadas
          </h2>
        </div>

        <p className="max-w-md text-sm leading-6 text-[#10213F]/58">
          Navega el catálogo por marcas creadas desde el panel administrativo.
        </p>
      </div>

      <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        {brands.map((brand) => (
          <BrandCard key={brand.id} brand={brand} />
        ))}
      </div>
    </section>
  );
}

function BrandCard({ brand }: { brand: Brand }) {
  const [imageFailed, setImageFailed] = useState(false);
  const imageSrc = getBrandImageSrc(brand.name);

  return (
    <a
      href={`/?brand_id=${brand.id}#catalogo`}
      className="group flex min-h-[128px] items-center gap-5 rounded-[1.75rem] border border-black/[0.06] bg-white/85 p-5 text-left text-[#10213F] shadow-sm shadow-black/[0.03] transition hover:-translate-y-1 hover:border-[#0B6FEF]/30 hover:shadow-2xl hover:shadow-blue-500/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
    >
      <div className="relative grid h-24 w-24 shrink-0 place-items-center overflow-hidden rounded-[1.5rem] bg-[#F6F3EC] sm:h-28 sm:w-28">
        {!imageFailed ? (
          <Image
            src={imageSrc}
            alt={brand.name}
            width={160}
            height={160}
            className="h-full w-full object-cover transition group-hover:scale-105"
            onError={() => setImageFailed(true)}
          />
        ) : (
          <span className="grid h-full w-full place-items-center bg-[#EAF2FF] text-xl font-black text-[#0B6FEF]">
            {brand.name.slice(0, 2).toUpperCase()}
          </span>
        )}
      </div>

      <div className="min-w-0">
        <div className="mb-2 inline-flex items-center gap-1.5 rounded-full bg-[#EAF2FF] px-3 py-1 text-xs font-bold text-[#0B6FEF]">
          <Building2 className="h-3.5 w-3.5" />
          Marca
        </div>

        <h3 className="truncate text-lg font-black tracking-tight">
          {brand.name}
        </h3>

        <p className="mt-1 truncate text-sm font-semibold text-[#10213F]/45">
          {brand.reference}
        </p>
      </div>
    </a>
  );
}
