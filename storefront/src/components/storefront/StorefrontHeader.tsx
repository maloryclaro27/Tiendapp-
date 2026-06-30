import Image from "next/image";
import Link from "next/link";
import { Search, ShieldCheck } from "lucide-react";

type StorefrontHeaderProps = {
  adminUrl: string;
};

export function StorefrontHeader({ adminUrl }: StorefrontHeaderProps) {
  return (
    <header className="sticky top-0 z-50 border-b border-black/[0.06] bg-[#F6F3EC]/90 backdrop-blur-xl">
      <div className="mx-auto flex h-24 max-w-7xl items-center justify-between gap-6 px-6">
        <Link href="/" className="flex shrink-0 items-center gap-3">
          <Image
            src="/tiendapp-header-logo.png"
            alt="TiendAPP"
            width={190}
            height={62}
            priority
            className="h-12 w-auto object-contain"
          />
        </Link>

        <nav
          aria-label="Navegación principal"
          className="hidden items-center gap-2 lg:flex"
        >
          <HeaderLink href="#catalogo">Catálogo</HeaderLink>
          <HeaderLink href="#marcas">Marcas</HeaderLink>
          <HeaderLink href="#destacados">Destacados</HeaderLink>
        </nav>

        <div className="hidden min-w-[280px] items-center gap-3 rounded-full border border-black/[0.06] bg-white px-4 py-3 shadow-sm shadow-black/[0.03] xl:flex">
          <Search className="h-4 w-4 text-[#10213F]/35" />
          <span className="text-sm font-medium text-[#10213F]/45">
            Buscar productos, marcas o referencias
          </span>
        </div>

        <a
          href={adminUrl}
          target="_blank"
          rel="noreferrer"
          className="hidden items-center gap-2 rounded-full bg-[#0B6FEF] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition hover:-translate-y-0.5 hover:bg-[#0D47C9] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000] md:inline-flex"
        >
          <ShieldCheck className="h-4 w-4" />
          Admin
        </a>
      </div>
    </header>
  );
}

function HeaderLink({
  href,
  children,
}: {
  href: string;
  children: React.ReactNode;
}) {
  return (
    <a
      href={href}
      className="rounded-full px-4 py-2 text-[17px] font-semibold text-[#10213F]/70 transition hover:bg-black/[0.04] hover:text-[#0B6FEF] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
    >
      {children}
    </a>
  );
}
