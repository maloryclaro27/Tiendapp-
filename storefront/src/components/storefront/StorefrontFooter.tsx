"use client";

import Image from "next/image";
import { FileText, Mail } from "lucide-react";
import { ReactNode, useState } from "react";

import { socialLinks } from "./storefront-assets";

export function StorefrontFooter() {
  return (
    <footer className="mx-auto mt-24 max-w-7xl px-6 pb-12">
      <div className="overflow-hidden rounded-[2rem] bg-[#0D47C9] text-white shadow-2xl shadow-blue-900/20">
        <div className="grid gap-6 p-8 lg:grid-cols-[1.15fr_0.85fr]">
          <div className="rounded-[1.75rem] border border-white/15 bg-white/10 p-8 shadow-inner">
            <p className="text-xs font-bold uppercase tracking-[0.35em] text-white/55">
              Escríbenos
            </p>

            <div className="mt-5 flex items-center gap-4">
              <span className="grid h-14 w-14 place-items-center rounded-2xl bg-white text-[#0B6FEF]">
                <Mail className="h-7 w-7" />
              </span>

              <a
                href="mailto:info@tiendapp.net"
                className="text-2xl font-black tracking-tight transition hover:text-[#FFB000]"
              >
                info@tiendapp.net
              </a>
            </div>

            <p className="mt-7 max-w-2xl text-base leading-8 text-white/72">
              Catálogo comercial conectado al inventario para consultar productos,
              marcas, referencias y disponibilidad desde una experiencia moderna.
            </p>
          </div>

          <div className="rounded-[1.75rem] bg-white p-8 text-[#10213F]">
            <p className="text-xs font-bold uppercase tracking-[0.35em] text-[#10213F]/40">
              Síguenos
            </p>

            <div className="mt-7 grid grid-cols-4 gap-4">
              {socialLinks.map((social) => (
                <SocialButton key={social.label} {...social} />
              ))}
            </div>
          </div>
        </div>

        <div className="grid gap-4 border-t border-white/10 bg-[#0B3FB8]/45 px-8 py-6 text-sm text-white/86 md:grid-cols-3">
          <LegalLink>Política de tratamiento de datos personales.</LegalLink>
          <LegalLink>
            Términos y condiciones del sitio web y las plataformas Tiendapp.
          </LegalLink>
          <LegalLink>Derechos de propiedad registrados ®</LegalLink>
        </div>
      </div>
    </footer>
  );
}

function SocialButton({
  label,
  href,
  iconSrc,
  fallback,
}: {
  label: string;
  href: string;
  iconSrc: string;
  fallback: string;
}) {
  const [imageFailed, setImageFailed] = useState(false);

  return (
    <a
      href={href}
      aria-label={label}
      target="_blank"
      rel="noreferrer"
      className="group grid aspect-square place-items-center rounded-2xl border border-black/[0.06] bg-[#F6F3EC] text-[#0B6FEF] transition hover:-translate-y-1 hover:bg-[#0B6FEF] hover:text-white hover:shadow-xl hover:shadow-blue-500/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
    >
      {!imageFailed ? (
        <Image
          src={iconSrc}
          alt=""
          width={48}
          height={48}
          className="h-8 w-8 object-contain transition group-hover:scale-110"
          onError={() => setImageFailed(true)}
        />
      ) : (
        <span className="text-2xl font-black tracking-tight transition group-hover:scale-110">
          {fallback}
        </span>
      )}
    </a>
  );
}

function LegalLink({ children }: { children: ReactNode }) {
  return (
    <a
      href="#"
      className="inline-flex items-center gap-2 transition hover:text-[#FFB000]"
    >
      <FileText className="h-4 w-4 shrink-0" />
      <span>{children}</span>
    </a>
  );
}
