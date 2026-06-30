"use client";

import { AlertTriangle, RotateCcw } from "lucide-react";
import { useEffect } from "react";

export default function Error({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  useEffect(() => {
    console.error("Storefront error:", error);
  }, [error]);

  return (
    <main className="min-h-screen bg-[#F6F3EC] px-6 py-16">
      <section className="mx-auto flex min-h-[70vh] max-w-4xl items-center justify-center">
        <div className="relative overflow-hidden rounded-[2rem] border border-red-200/70 bg-white p-8 text-center shadow-2xl shadow-black/[0.06] md:p-12">
          <div className="absolute inset-x-0 top-0 h-2 bg-gradient-to-r from-[#FFB000] via-red-400 to-[#0B6FEF]" />

          <div className="mx-auto grid h-20 w-20 place-items-center rounded-[1.5rem] bg-red-50 text-red-500">
            <AlertTriangle className="h-10 w-10" />
          </div>

          <p className="mt-8 text-sm font-black uppercase tracking-[0.32em] text-[#0B6FEF]">
            Storefront temporalmente no disponible
          </p>

          <h1 className="mt-4 text-4xl font-black tracking-tight text-[#10213F] md:text-5xl">
            No pudimos cargar el catálogo.
          </h1>

          <p className="mx-auto mt-5 max-w-2xl text-base leading-7 text-[#10213F]/60">
            Puede tratarse de una interrupción temporal en la conexión con la
            API del administrador. Intenta cargar nuevamente la información.
          </p>

          <button
            type="button"
            onClick={reset}
            className="mx-auto mt-8 inline-flex items-center gap-2 rounded-full bg-[#0B6FEF] px-6 py-3 text-sm font-black text-white shadow-xl shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-[#0D47C9] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            <RotateCcw className="h-4 w-4" />
            Reintentar
          </button>

          {error.digest ? (
            <p className="mt-6 text-xs font-semibold text-[#10213F]/35">
              Código de referencia: {error.digest}
            </p>
          ) : null}
        </div>
      </section>
    </main>
  );
}
