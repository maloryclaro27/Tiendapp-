"use client";

import Image from "next/image";
import type { LucideIcon } from "lucide-react";
import type { ReactNode } from "react";
import { useEffect, useMemo, useState } from "react";
import {
  AlertTriangle,
  ArrowLeft,
  ArrowRight,
  BadgeCheck,
  Boxes,
  Building2,
  CheckCircle2,
  ChevronDown,
  CircleDot,
  Clock3,
  Filter,
  Layers3,
  PackageCheck,
  Search,
  ShieldCheck,
  ShoppingBag,
  SlidersHorizontal,
  Sparkles,
  Store,
  Tag,
  Truck,
  Mail,
  FileText,
  XCircle,
  Zap,
} from "lucide-react";
import { cn } from "@/lib/utils";

type UnitOfMeasure = "Unidad" | "Display" | "Caja";
type InventoryStatus = "Stock saludable" | "Bajo stock" | "Sin stock";

type PreviewProduct = {
  id: number;
  name: string;
  brand: string;
  reference: string;
  unit: UnitOfMeasure;
  quantity: number;
  status: InventoryStatus;
  observations: string;
  updated: string;
};

type HeroSlide = {
  eyebrow: string;
  title: string;
  description: string;
  cta: string;
  metricLabel: string;
  metricValue: string;
  icon: LucideIcon;
  gradient: string;
  glow: string;
};

const products: PreviewProduct[] = [
  {
    id: 1,
    name: "Leche Entera x 12",
    brand: "Alpina",
    reference: "ALPINA-001",
    unit: "Caja",
    quantity: 45,
    status: "Stock saludable",
    observations: "Caja de 12 unidades de leche entera de 1 litro para distribución.",
    updated: "hace 17 días",
  },
  {
    id: 2,
    name: "Yogurt Fresa Familiar",
    brand: "Alpina",
    reference: "ALPINA-001",
    unit: "Unidad",
    quantity: 18,
    status: "Stock saludable",
    observations: "Producto refrigerado de alta rotación en punto de venta.",
    updated: "hace 6 días",
  },
  {
    id: 3,
    name: "Queso Campesino",
    brand: "Colanta",
    reference: "COLANTA-002",
    unit: "Unidad",
    quantity: 0,
    status: "Sin stock",
    observations: "Producto lácteo empacado al vacío, requiere cadena de frío.",
    updated: "hace 15 días",
  },
  {
    id: 4,
    name: "Chocolate de Mesa Tradicional",
    brand: "Nutresa",
    reference: "NUTRESA-003",
    unit: "Display",
    quantity: 32,
    status: "Stock saludable",
    observations: "Display comercial para exhibición en góndola.",
    updated: "hace 10 días",
  },
  {
    id: 5,
    name: "Gaseosa Manzana x 30",
    brand: "Postobon",
    reference: "POSTOBON-004",
    unit: "Caja",
    quantity: 64,
    status: "Stock saludable",
    observations: "Caja retornable para canal tradicional.",
    updated: "hace 5 días",
  },
  {
    id: 6,
    name: "Papel Higiénico Familiar",
    brand: "Familia",
    reference: "FAMILIA-005",
    unit: "Display",
    quantity: 11,
    status: "Stock saludable",
    observations: "Display de producto de aseo para venta por volumen.",
    updated: "hace 4 días",
  },
  {
    id: 7,
    name: "Salchichas Tradicionales",
    brand: "Zenu",
    reference: "ZENU-007",
    unit: "Unidad",
    quantity: 8,
    status: "Bajo stock",
    observations: "Producto cárnico refrigerado con rotación semanal.",
    updated: "hace 8 días",
  },
  {
    id: 8,
    name: "Chocoramo Display",
    brand: "Ramo",
    reference: "RAMO-008",
    unit: "Display",
    quantity: 0,
    status: "Sin stock",
    observations: "Display de producto individual para exhibición comercial.",
    updated: "hace 11 días",
  },
];

const heroSlides: HeroSlide[] = [
  {
    eyebrow: "Storefront conectado",
    title: "Catálogo comercial vivo para productos, marcas y disponibilidad.",
    description:
    "Una experiencia ecommerce moderna para explorar inventario publicado desde el panel administrativo.",
    cta: "Explorar catálogo",
    metricLabel: "productos activos",
    metricValue: "8",
    icon: ShoppingBag,
    gradient: "from-[#0F2148] via-[#0D47C9] to-[#16316C]",
    glow: "bg-[#FFC247]/20",
  },
  {
    eyebrow: "Stock saludable",
    title: "Disponibilidad clara antes de tomar decisiones comerciales.",
    description:
    "El inventario visible comunica qué productos están disponibles y cuáles requieren atención comercial.",
    cta: "Ver disponibles",
    metricLabel: "unidades",
    metricValue: "178",
    icon: PackageCheck,
    gradient: "from-[#10213F] via-[#0D47C9] to-[#16316C]",
    glow: "bg-[#FFC247]/18",
  },
  {
    eyebrow: "Alertas operativas",
    title: "Bajo stock y sin stock convertidos en señales visuales.",
    description:
      "Las alertas se vuelven útiles para priorizar reposición, publicación y gestión comercial.",
    cta: "Revisar alertas",
    metricLabel: "alertas",
    metricValue: "3",
    icon: AlertTriangle,
    gradient: "from-[#0F2148] via-[#0D47C9] to-[#16316C]",
    glow: "bg-[#FFC247]/20",
  },
];

const brandTheme: Record<string, string> = {
  Alpina: "from-sky-100 via-white to-blue-50 text-sky-700",
  Colanta: "from-cyan-100 via-white to-slate-50 text-cyan-700",
  Nutresa: "from-orange-100 via-white to-amber-50 text-orange-700",
  Postobon: "from-rose-100 via-white to-orange-50 text-rose-700",
  Familia: "from-blue-100 via-white to-indigo-50 text-blue-700",
  Noel: "from-amber-100 via-white to-yellow-50 text-amber-700",
  Zenu: "from-emerald-100 via-white to-green-50 text-emerald-700",
  Ramo: "from-yellow-100 via-white to-orange-50 text-yellow-700",
};

const brandNames = ["Todas", ...Array.from(new Set(products.map((product) => product.brand)))];
const unitOptions: Array<"Todas" | UnitOfMeasure> = ["Todas", "Unidad", "Display", "Caja"];
const statusOptions: Array<"Todos" | InventoryStatus> = [
  "Todos",
  "Stock saludable",
  "Bajo stock",
  "Sin stock",
];

const unitClasses: Record<UnitOfMeasure, string> = {
  Unidad: "border-sky-200 bg-sky-50 text-sky-700",
  Display: "border-violet-200 bg-violet-50 text-violet-700",
  Caja: "border-orange-200 bg-orange-50 text-orange-700",
};

const inventoryMeta: Record<
  InventoryStatus,
  {
    badge: string;
    dot: string;
    icon: LucideIcon;
    text: string;
  }
> = {
  "Stock saludable": {
    badge: "border-emerald-200 bg-emerald-50 text-emerald-700",
    dot: "bg-emerald-500 shadow-emerald-500/25",
    icon: CheckCircle2,
    text: "Disponible",
  },
  "Bajo stock": {
    badge: "border-amber-200 bg-amber-50 text-amber-700",
    dot: "bg-amber-500 shadow-amber-500/25",
    icon: AlertTriangle,
    text: "Reposición",
  },
  "Sin stock": {
    badge: "border-rose-200 bg-rose-50 text-rose-700",
    dot: "bg-rose-500 shadow-rose-500/25",
    icon: XCircle,
    text: "Sin unidades",
  },
};

const showcaseProducts = [products[4], products[0], products[6], products[7]];

const textureStyle = {
  backgroundColor: "#FAFAF7",
  backgroundImage:
    "radial-gradient(circle at 20% 20%, rgba(11,111,239,0.06), transparent 22%), radial-gradient(circle at 80% 10%, rgba(255,176,0,0.08), transparent 20%), radial-gradient(circle at 70% 80%, rgba(11,111,239,0.04), transparent 24%), linear-gradient(rgba(15,33,72,0.018) 1px, transparent 1px), linear-gradient(90deg, rgba(15,33,72,0.018) 1px, transparent 1px)",
  backgroundSize: "auto, auto, auto, 22px 22px, 22px 22px",
};

function getBrandAccent(brand: string) {
  return brandTheme[brand] ?? "from-slate-100 via-white to-slate-50 text-slate-700";
}

function slugify(value: string) {
  return value
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/(^-|-$)/g, "");
}

function getBrandImage(brand: string) {
  return `/catalog/brands/${slugify(brand)}.png`;
}

function getProductImage(product: PreviewProduct) {
  return `/catalog/products/${slugify(product.name)}.png`;
}

export default function DesignPreviewPage() {
  const [activeSlide, setActiveSlide] = useState(0);
  const [activeShowcase, setActiveShowcase] = useState(0);
  const [search, setSearch] = useState("");
  const [selectedBrand, setSelectedBrand] = useState("Todas");
  const [selectedUnit, setSelectedUnit] = useState<"Todas" | UnitOfMeasure>("Todas");
  const [selectedStatus, setSelectedStatus] = useState<"Todos" | InventoryStatus>("Todos");
  const [reducedMotion, setReducedMotion] = useState(false);

  useEffect(() => {
    const mediaQuery = window.matchMedia("(prefers-reduced-motion: reduce)");

    const updateReducedMotion = () => {
      setReducedMotion(mediaQuery.matches);
    };

    updateReducedMotion();
    mediaQuery.addEventListener("change", updateReducedMotion);

    return () => {
      mediaQuery.removeEventListener("change", updateReducedMotion);
    };
  }, []);

  useEffect(() => {
    if (reducedMotion) {
      return;
    }

    const timer = window.setTimeout(() => {
      setActiveSlide((current) => (current + 1) % heroSlides.length);
    }, 5200);

    return () => {
      window.clearTimeout(timer);
    };
  }, [activeSlide, reducedMotion]);

  const filteredProducts = useMemo(() => {
    const normalizedSearch = search.trim().toLowerCase();

    return products.filter((product) => {
      const searchableText = [
        product.name,
        product.brand,
        product.reference,
        product.unit,
        product.status,
        product.observations,
      ]
        .join(" ")
        .toLowerCase();

      const matchesSearch = normalizedSearch === "" || searchableText.includes(normalizedSearch);
      const matchesBrand = selectedBrand === "Todas" || product.brand === selectedBrand;
      const matchesUnit = selectedUnit === "Todas" || product.unit === selectedUnit;
      const matchesStatus = selectedStatus === "Todos" || product.status === selectedStatus;

      return matchesSearch && matchesBrand && matchesUnit && matchesStatus;
    });
  }, [search, selectedBrand, selectedStatus, selectedUnit]);

  const activeProducts = products.filter((product) => product.quantity > 0).length;
  const totalInventory = products.reduce((total, product) => total + product.quantity, 0);
  const stockAlerts = products.filter((product) => product.status !== "Stock saludable").length;
  const slide = heroSlides[activeSlide];

  return (
    <main className="min-h-screen text-[#10213F] antialiased" style={textureStyle}>
      <header className="sticky top-0 z-50 border-b border-black/[0.06] bg-[#FAFAF7]/85 backdrop-blur-2xl">
        <div className="mx-auto flex max-w-7xl items-center gap-4 px-5 py-3.5">
          <BrandMark />

          <nav className="ml-4 hidden items-center gap-1 lg:flex">
            <HeaderLink href="#catálogo">Catálogo</HeaderLink>
            <HeaderLink href="#marcas">Marcas</HeaderLink>
            <HeaderLink href="#destacados">Destacados</HeaderLink>
          </nav>

          <div className="ml-auto hidden w-full max-w-md items-center gap-2 rounded-full border border-black/[0.08] bg-white/90 px-4 py-2.5 shadow-sm md:flex">
            <Search className="h-4 w-4 text-[#10213F]/35" />
            <input
              value={search}
              onChange={(event) => setSearch(event.target.value)}
              placeholder="Buscar producto, marca o referencia"
              className="w-full bg-transparent text-sm font-medium text-[#10213F] outline-none placeholder:text-[#10213F]/35"
            />
          </div>

          <a
            href="http://localhost:8080/admin"
            className="hidden rounded-full bg-[#0B6FEF] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:-translate-y-0.5 hover:bg-[#095BC4] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000] focus-visible:ring-offset-2 sm:block"
          >
            Admin
          </a>
        </div>
      </header>

      <section className="mx-auto max-w-7xl px-5 pb-20 pt-6">
        <HeroCarousel
          slide={slide}
          activeSlide={activeSlide}
          setActiveSlide={setActiveSlide}
          activeProducts={activeProducts}
          totalInventory={totalInventory}
          stockAlerts={stockAlerts}
        />

        <BenefitsBar />

        <section id="marcas" className="mt-12">
          <SectionHeading
            eyebrow="Explora por marca"
            title="Marcas listas para publicación comercial"
            description="Una entrada rápida al catálogo con una navegación clara, visual y orientada a ecommerce."
          />

          <div className="mt-5 grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8">
            {brandNames.slice(1).map((brand) => (
              <button
                key={brand}
                type="button"
                onClick={() => setSelectedBrand(brand)}
                className={cn(
                  "group rounded-2xl border p-4 text-left transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/[0.05] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]",
                  selectedBrand === brand
                    ? "border-[#0B6FEF] bg-[#0B6FEF] text-white shadow-xl shadow-blue-500/[0.12]"
                    : "border-black/[0.07] bg-white/90 text-[#10213F] hover:border-black/[0.14]",
                )}
              >
                <BrandVisual brand={brand} selected={selectedBrand === brand} />
                <p className="mt-4 truncate text-sm font-semibold">{brand}</p>
                <p
                  className={cn(
                    "mt-1 text-xs font-medium",
                    selectedBrand === brand ? "text-white/60" : "text-[#10213F]/40",
                  )}
                >
                  {products.filter((product) => product.brand === brand).length} productos
                </p>
              </button>
            ))}
          </div>
        </section>

        <section id="destacados" className="mt-14">
          <SectionHeading
            eyebrow="Showcase"
            title="Productos destacados del inventario"
            description="Una vitrina interactiva para resaltar referencias clave, disponibilidad y alertas comerciales."
          />

          <div className="mt-6 grid gap-5 lg:grid-cols-[0.82fr_1.18fr]">
            <FeaturedProduct
              product={showcaseProducts[activeShowcase]}
              onPrevious={() =>
                setActiveShowcase((current) =>
                  current === 0 ? showcaseProducts.length - 1 : current - 1,
                )
              }
              onNext={() =>
                setActiveShowcase((current) => (current + 1) % showcaseProducts.length)
              }
            />

            <div className="grid gap-4 sm:grid-cols-2">
              {showcaseProducts.map((product, index) => (
                <button
                  key={product.id}
                  type="button"
                  onClick={() => setActiveShowcase(index)}
                  className={cn(
                    "rounded-2xl border bg-white/90 p-4 text-left transition hover:-translate-y-1 hover:shadow-xl hover:shadow-black/[0.05] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]",
                    activeShowcase === index
                      ? "border-[#0B6FEF] shadow-xl shadow-blue-500/[0.08]"
                      : "border-black/[0.07] hover:border-black/[0.14]",
                  )}
                >
                  <div className="flex items-start gap-4">
                    <ProductThumb product={product} size="sm" />
                    <div className="min-w-0">
                      <p className="text-xs font-bold uppercase tracking-[0.18em] text-[#10213F]/35">
                        {product.brand}
                      </p>
                      <p className="mt-1 line-clamp-2 text-sm font-semibold text-[#10213F]">
                        {product.name}
                      </p>
                      <p className="mt-2 text-xs font-medium text-[#10213F]/45">
                        {product.quantity} unidades · {product.status}
                      </p>
                    </div>
                  </div>
                </button>
              ))}
            </div>
          </div>
        </section>

        <section id="catálogo" className="mt-14">
          <div className="rounded-2xl border border-black/[0.07] bg-white/90 p-4 shadow-sm">
            <div className="grid gap-3 lg:grid-cols-[1fr_auto] lg:items-center">
              <div className="flex items-center gap-3 rounded-xl bg-[#F6F3EC] px-4 py-3">
                <Search className="h-4 w-4 text-[#10213F]/35" />
                <input
                  value={search}
                  onChange={(event) => setSearch(event.target.value)}
                  placeholder="Buscar en el catálogo"
                  className="w-full bg-transparent text-sm font-medium text-[#10213F] outline-none placeholder:text-[#10213F]/35"
                />
              </div>

              <div className="flex flex-wrap gap-2">
                <PillSelect
                  icon={Building2}
                  label="Marca"
                  value={selectedBrand}
                  options={brandNames}
                  onChange={setSelectedBrand}
                />
                <PillSelect
                  icon={Layers3}
                  label="Unidad"
                  value={selectedUnit}
                  options={unitOptions}
                  onChange={(value) => setSelectedUnit(value as "Todas" | UnitOfMeasure)}
                />
                <PillSelect
                  icon={Filter}
                  label="Estado"
                  value={selectedStatus}
                  options={statusOptions}
                  onChange={(value) => setSelectedStatus(value as "Todos" | InventoryStatus)}
                />
              </div>
            </div>
          </div>

          <div className="mt-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <SectionHeading
              eyebrow="Catálogo"
              title="Todos los productos"
              description={`${filteredProducts.length} resultados filtrados de ${products.length} productos disponibles en la preview.`}
              compact
            />

            <button
              type="button"
              onClick={() => {
                setSearch("");
                setSelectedBrand("Todas");
                setSelectedUnit("Todas");
                setSelectedStatus("Todos");
              }}
              className="inline-flex w-fit items-center gap-2 rounded-full border border-black/[0.08] bg-white/90 px-4 py-2 text-sm font-medium text-[#10213F]/60 transition hover:border-black/[0.14] hover:text-[#10213F] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
            >
              <SlidersHorizontal className="h-4 w-4" />
              Limpiar filtros
            </button>
          </div>

          {filteredProducts.length > 0 ? (
            <div className="mt-5 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
              {filteredProducts.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          ) : (
            <EmptyState
              onReset={() => {
                setSearch("");
                setSelectedBrand("Todas");
                setSelectedUnit("Todas");
                setSelectedStatus("Todos");
              }}
            />
          )}
        </section>
        <Footer />
      </section>
    </main>
  );
}

function BrandMark() {
  return (
    <a href="#" className="flex shrink-0 items-center gap-3">
      <Image
        src="/tiendapp-header-logo.png"
        alt="TiendAPP"
        width={190}
        height={62}
        priority
        className="h-12 w-auto object-contain"
      />
    </a>
  );
}

function HeaderLink({ href, children }: { href: string; children: ReactNode }) {
  return (
    <a
      href={href}
      className="rounded-full px-4 py-2 text-[17px] font-semibold text-[#10213F]/70 transition hover:bg-black/[0.04] hover:text-[#0B6FEF] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
    >
      {children}
    </a>
  );
}

function HeroCarousel({
  slide,
  activeSlide,
  setActiveSlide,
  activeProducts,
  totalInventory,
  stockAlerts,
}: {
  slide: HeroSlide;
  activeSlide: number;
  setActiveSlide: (index: number) => void;
  activeProducts: number;
  totalInventory: number;
  stockAlerts: number;
}) {
  const SlideIcon = slide.icon;

  return (
    <section
      className={cn(
        "relative overflow-hidden rounded-[2rem] bg-gradient-to-br text-white shadow-2xl shadow-black/[0.12]",
        slide.gradient,
      )}
    >
      <div className={cn("absolute -right-20 -top-20 h-80 w-80 rounded-full blur-3xl", slide.glow)} />
      <div className="absolute -bottom-24 left-1/3 h-72 w-72 rounded-full bg-white/10 blur-3xl" />

      <div className="relative grid gap-10 p-7 sm:p-10 lg:grid-cols-[1fr_410px] lg:items-center">
        <div>
          <div className="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-medium text-blue-100 backdrop-blur">
            <SlideIcon className="h-4 w-4" />
            {slide.eyebrow}
          </div>

          <h1
            className="mt-6 max-w-4xl text-4xl font-medium tracking-[-0.055em] sm:text-6xl"
            style={{ fontFamily: "Georgia, Times New Roman, serif" }}
          >
            {slide.title}
          </h1>

          <p className="mt-5 max-w-2xl text-base leading-8 text-white/70 sm:text-lg">
            {slide.description}
          </p>

          <div className="mt-8 flex flex-wrap gap-3">
            <a
              href="#catálogo"
              className="inline-flex items-center gap-2 rounded-full bg-[#FFB000] px-5 py-3 text-sm font-semibold text-[#10213F] shadow-xl shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-[#F29C00] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
            >
              {slide.cta}
              <ArrowRight className="h-4 w-4" />
            </a>
            <a
              href="#destacados"
              className="inline-flex items-center gap-2 rounded-full border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
            >
              Ver destacados
            </a>
          </div>

          <div className="mt-8 flex items-center gap-2">
            {heroSlides.map((item, index) => (
              <button
                key={item.title}
                type="button"
                onClick={() => setActiveSlide(index)}
                className={cn(
                  "h-2.5 rounded-full transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white",
                  activeSlide === index ? "w-10 bg-[#FFB000]" : "w-2.5 bg-white/35 hover:bg-white/60",
                )}
                aria-label={`Ver slide ${index + 1}`}
                aria-pressed={activeSlide === index}
              />
            ))}
          </div>
        </div>

        <div className="rounded-[1.75rem] border border-white/15 bg-white/10 p-5 backdrop-blur-xl">
          <div className="rounded-[1.35rem] bg-white p-5 text-[#10213F] shadow-2xl shadow-black/20">
            <div className="flex items-start justify-between gap-4">
              <div>
                <p className="text-xs font-bold uppercase tracking-[0.22em] text-[#10213F]/35">
                  Vista comercial
                </p>
                <h2 className="mt-2 text-2xl font-semibold tracking-tight">
                  Inventario publicado
                </h2>
              </div>
              <div className="grid h-12 w-12 place-items-center rounded-xl bg-[#0B6FEF] text-white">
                <Store className="h-6 w-6" />
              </div>
            </div>

            <div className="mt-6 grid gap-3">
              <HeroMetric icon={PackageCheck} label="Disponibles" value={activeProducts} />
              <HeroMetric icon={Boxes} label="Unidades" value={totalInventory} />
              <HeroMetric icon={AlertTriangle} label="Alertas" value={stockAlerts} warning />
            </div>

            <div className="mt-5 rounded-xl bg-[#F6F3EC] p-4">
              <p className="text-xs font-bold uppercase tracking-[0.2em] text-[#10213F]/35">
                {slide.metricLabel}
              </p>
              <div className="mt-2 flex items-end justify-between">
                <p className="text-5xl font-semibold tracking-tight">{slide.metricValue}</p>
                <BadgeCheck className="h-8 w-8 text-emerald-500" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

function BenefitsBar() {
  return (
    <section className="mt-6 grid gap-3 rounded-2xl border border-black/[0.07] bg-white/85 p-3 shadow-sm md:grid-cols-4">
      <Benefit icon={Truck} title="Distribución" text="Unidades por caja, display o unidad." />
      <Benefit icon={Clock3} title="Actualizado" text="Inventario visible y reciente." />
      <Benefit icon={ShieldCheck} title="Confiable" text="Información centralizada y actualizada." />
      <Benefit icon={Zap} title="Rápido" text="Consulta ágil para equipos comerciales." />
    </section>
  );
}

function Benefit({ icon: Icon, title, text }: { icon: LucideIcon; title: string; text: string }) {
  return (
    <div className="flex items-center gap-3 rounded-xl bg-[#F6F3EC] p-4">
      <div className="grid h-10 w-10 place-items-center rounded-xl bg-white text-[#0B6FEF] shadow-sm">
        <Icon className="h-5 w-5" />
      </div>
      <div>
        <p className="text-sm font-semibold text-[#10213F]">{title}</p>
        <p className="text-xs font-medium leading-5 text-[#10213F]/45">{text}</p>
      </div>
    </div>
  );
}

function SectionHeading({
  eyebrow,
  title,
  description,
  compact = false,
}: {
  eyebrow: string;
  title: string;
  description: string;
  compact?: boolean;
}) {
  return (
    <div>
      <p className="inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-[#10213F]/35 shadow-sm">
        <CircleDot className="h-3.5 w-3.5" />
        {eyebrow}
      </p>
      <h2
        className={cn(
          "mt-3 font-medium tracking-[-0.04em] text-[#10213F]",
          compact ? "text-2xl" : "text-3xl sm:text-4xl",
        )}
        style={{ fontFamily: "Georgia, Times New Roman, serif" }}
      >
        {title}
      </h2>
      <p className="mt-2 max-w-2xl text-sm font-medium leading-6 text-[#10213F]/50">
        {description}
      </p>
    </div>
  );
}

function StatusPill({ status }: { status: PreviewProduct["status"] }) {
  const styles = {
    "Stock saludable": "bg-emerald-50 text-emerald-700 ring-emerald-200",
    "Bajo stock": "bg-amber-50 text-amber-700 ring-amber-200",
    "Sin stock": "bg-rose-50 text-rose-700 ring-rose-200",
  } satisfies Record<PreviewProduct["status"], string>;

  const Icon =
    status === "Stock saludable"
      ? CheckCircle2
      : status === "Bajo stock"
        ? AlertTriangle
        : XCircle;

  return (
    <span
      className={cn(
        "inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold ring-1",
        styles[status],
      )}
    >
      <Icon className="h-4 w-4" />
      {status}
    </span>
  );
}

function ProductThumb({ product, size }: { product: PreviewProduct; size: "sm" | "lg" }) {
  const [imageFailed, setImageFailed] = useState(false);
  const imageSrc = getProductImage(product);
  const isLarge = size === "lg";

  return (
    <div
      className={cn(
        "relative grid shrink-0 place-items-center overflow-hidden bg-white font-bold",
        isLarge
          ? "h-full w-full rounded-[1.5rem]"
          : "h-20 w-20 rounded-2xl",
        !isLarge && getBrandAccent(product.brand),
      )}
    >
      {!imageFailed ? (
        <Image
          src={imageSrc}
          alt={product.name}
          width={isLarge ? 360 : 96}
          height={isLarge ? 240 : 96}
          className={cn(
            isLarge ? "h-auto max-h-[240px] w-auto max-w-[360px] object-contain p-2" : "h-full w-full object-contain p-2",
          )}
          onError={() => setImageFailed(true)}
        />
      ) : (
        <span className={cn(isLarge ? "text-4xl" : "text-xl")}>
          {product.brand.slice(0, 2).toUpperCase()}
        </span>
      )}
    </div>
  );
}

function ProductImageHeader({ product }: { product: PreviewProduct }) {
  const [imageFailed, setImageFailed] = useState(false);
  const imageSrc = getProductImage(product);

  return (
    <div className="relative overflow-hidden bg-[#F8F6F1] p-5">
      <div className="relative flex h-56 items-center justify-center rounded-[1.5rem] bg-white shadow-inner">
        {!imageFailed ? (
          <Image
            src={imageSrc}
            alt={product.name}
            fill
            sizes="(min-width: 1280px) 25vw, (min-width: 640px) 50vw, 100vw"
            className="object-contain p-5"
            onError={() => setImageFailed(true)}
          />
        ) : (
          <div
            className={cn(
              "grid h-24 w-24 place-items-center rounded-2xl bg-gradient-to-br text-3xl font-bold",
              getBrandAccent(product.brand),
            )}
          >
            {product.brand.slice(0, 2).toUpperCase()}
          </div>
        )}
      </div>

      <span className="absolute left-7 top-7 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-xs font-semibold text-[#10213F] shadow-sm ring-1 ring-black/[0.06] backdrop-blur">
        <Tag className="h-3.5 w-3.5" />
        {product.reference}
      </span>
    </div>
  );
}

function FeaturedProduct({
  product,
  onPrevious,
  onNext,
}: {
  product: PreviewProduct;
  onPrevious?: () => void;
  onNext?: () => void;
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
        <div className="mx-auto flex h-[280px] max-w-[420px] items-center justify-center rounded-[1.5rem] bg-white shadow-xl shadow-black/15">
          <ProductThumb product={product} size="lg" />
        </div>
      </div>

      <div className="relative mt-6">
        <p className="text-xs font-bold uppercase tracking-[0.32em] text-white/45">
          {product.brand} · {product.reference}
        </p>

        <h3 className="mt-3 text-3xl font-semibold leading-tight tracking-tight text-white">
          {product.name}
        </h3>

        <p className="mt-4 text-base leading-7 text-white/70">
          {product.observations}
        </p>

        <div className="mt-5 flex flex-wrap items-center gap-2.5">
          <StatusPill status={product.status} />
          <span className="rounded-full bg-white/10 px-4 py-2 text-sm font-bold ring-1 ring-white/10">
            {product.quantity} unidades
          </span>
          <span className="rounded-full bg-white/10 px-4 py-2 text-sm font-bold ring-1 ring-white/10">
            {product.unit}
          </span>
        </div>
      </div>
    </article>
  );
}

function ProductCard({ product }: { product: PreviewProduct }) {
  const meta = inventoryMeta[product.status];
  const StatusIcon = meta.icon;

  return (
    <article className="group overflow-hidden rounded-2xl border border-black/[0.07] bg-white/90 shadow-sm shadow-black/[0.03] transition duration-300 hover:-translate-y-1 hover:border-black/[0.12] hover:shadow-xl hover:shadow-black/[0.07]">
      <ProductImageHeader product={product} />

      <div className="p-5">
        <div className="flex items-start justify-between gap-3">
          <h3 className="line-clamp-2 text-[16px] font-semibold leading-snug tracking-tight text-[#10213F]">
            {product.name}
          </h3>
          <span
            className={cn(
              "shrink-0 rounded-full border px-2.5 py-1 text-xs font-semibold",
              unitClasses[product.unit],
            )}
          >
            {product.unit}
          </span>
        </div>

        <p className="mt-3 line-clamp-2 min-h-[2.75rem] text-sm leading-6 text-[#10213F]/50">
          {product.observations}
        </p>

        <div className="mt-5 rounded-xl bg-[#F6F3EC] p-3">
          <div className="flex items-end justify-between gap-3">
            <div>
              <p className="text-xs font-bold uppercase tracking-[0.18em] text-[#10213F]/35">
                Inventario
              </p>
              <div className="mt-1 flex items-end gap-1.5">
                <span className="text-3xl font-semibold tracking-tight text-[#10213F]">
                  {product.quantity}
                </span>
                <span className="pb-1 text-xs font-semibold text-[#10213F]/35">unds.</span>
              </div>
            </div>

            <span
              className={cn(
                "inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold",
                meta.badge,
              )}
            >
              <StatusIcon className="h-3.5 w-3.5" />
              {meta.text}
            </span>
          </div>
        </div>

        <div className="mt-5 flex items-center justify-between gap-3">
          <p className="text-xs font-medium text-[#10213F]/35">Actualizado {product.updated}</p>
          <button
            type="button"
            className="inline-flex items-center gap-1.5 rounded-full bg-[#0B6FEF] px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-[#095BC4] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
          >
            Consultar
            <ArrowRight className="h-3.5 w-3.5" />
          </button>
        </div>
      </div>
    </article>
  );
}

function HeroMetric({
  icon: Icon,
  label,
  value,
  warning = false,
}: {
  icon: LucideIcon;
  label: string;
  value: number;
  warning?: boolean;
}) {
  return (
    <div className="flex items-center gap-3 rounded-xl bg-[#F6F3EC] p-4">
      <div
        className={cn(
          "grid h-10 w-10 place-items-center rounded-xl",
          warning ? "bg-amber-100 text-amber-600" : "bg-blue-100 text-blue-600",
        )}
      >
        <Icon className="h-5 w-5" />
      </div>
      <div>
        <p className="text-2xl font-semibold tracking-tight text-[#10213F]">{value}</p>
        <p className="text-xs font-bold uppercase tracking-[0.18em] text-[#10213F]/35">{label}</p>
      </div>
    </div>
  );
}

function PillSelect({
  icon: Icon,
  label,
  value,
  options,
  onChange,
}: {
  icon: LucideIcon;
  label: string;
  value: string;
  options: string[];
  onChange: (value: string) => void;
}) {
  return (
    <label className="inline-flex items-center gap-2 rounded-full border border-black/[0.08] bg-white/90 px-3 py-2 shadow-sm">
      <Icon className="h-4 w-4 text-[#10213F]/35" />
      <span className="hidden text-xs font-bold uppercase tracking-[0.16em] text-[#10213F]/35 sm:block">
        {label}
      </span>
      <select
        value={value}
        onChange={(event) => onChange(event.target.value)}
        className="cursor-pointer appearance-none bg-transparent pr-7 text-sm font-semibold text-[#10213F]/70 outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
      >
        {options.map((option) => (
          <option key={option} value={option}>
            {option}
          </option>
        ))}
      </select>
      <ChevronDown className="pointer-events-none -ml-6 h-3.5 w-3.5 text-[#10213F]/25" />
    </label>
  );
}

function EmptyState({ onReset }: { onReset: () => void }) {
  return (
    <div className="rounded-2xl border border-dashed border-black/[0.12] bg-white/90 p-12 text-center shadow-sm">
      <div className="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-[#F6F3EC] text-[#10213F]/45">
        <Search className="h-7 w-7" />
      </div>
      <h3 className="mt-5 text-2xl font-medium tracking-tight text-[#10213F]">
        No encontramos productos
      </h3>
      <p className="mx-auto mt-2 max-w-md text-sm leading-6 text-[#10213F]/50">
        Ajusta la búsqueda, cambia la marca o limpia los filtros para volver a ver el catálogo.
      </p>
      <button
        type="button"
        onClick={onReset}
        className="mt-6 inline-flex items-center gap-2 rounded-full bg-[#0B6FEF] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#095BC4] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFB000]"
      >
        <SlidersHorizontal className="h-4 w-4" />
        Limpiar filtros
      </button>
    </div>
  );
}
function BrandVisual({ brand, selected }: { brand: string; selected: boolean }) {
  const [imageFailed, setImageFailed] = useState(false);
  const imageSrc = getBrandImage(brand);

  return (
    <div
      className={cn(
        "relative grid h-24 w-24 shrink-0 place-items-center overflow-hidden rounded-[1.5rem] bg-gradient-to-br text-xl font-bold transition group-hover:scale-105 sm:h-28 sm:w-28",
        selected ? "from-white/20 to-white/10 text-white" : getBrandAccent(brand),
      )}
    >
      {!imageFailed ? (
        <Image
          src={imageSrc}
          alt={brand}
          width={160}
          height={160}
          className="h-full w-full object-cover"
          onError={() => setImageFailed(true)}
        />
      ) : (
        brand.slice(0, 2).toUpperCase()
      )}
    </div>
  );
}







function Footer() {
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
              <SocialButton label="LinkedIn" href="#">in</SocialButton>
              <SocialButton label="Instagram" href="#">IG</SocialButton>
              <SocialButton label="Facebook" href="#">f</SocialButton>
              <SocialButton label="YouTube" href="#">YT</SocialButton>
            </div>
          </div>
        </div>

        <div className="grid gap-4 border-t border-white/10 bg-[#0B3FB8]/45 px-8 py-6 text-sm text-white/86 md:grid-cols-3">
          <LegalLink>Política de tratamiento de datos personales.</LegalLink>
          <LegalLink>Términos y condiciones del sitio web y las plataformas Tiendapp.</LegalLink>
          <LegalLink>Derechos de propiedad registrados ®</LegalLink>
        </div>
      </div>
    </footer>
  );
}

function SocialButton({ label, href, children }: { label: string; href: string; children: ReactNode }) {
  const [imageFailed, setImageFailed] = useState(false);
  const imageSrc = `/catalog/social/${slugify(label)}.png`;

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
          src={imageSrc}
          alt=""
          width={48}
          height={48}
          className="h-8 w-8 object-contain transition group-hover:scale-110"
          onError={() => setImageFailed(true)}
        />
      ) : (
        <span className="text-2xl font-black tracking-tight transition group-hover:scale-110">
          {children}
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
      <FileText className="h-4 w-4 shrink-0 text-[#FFB000]" />
      {children}
    </a>
  );
}