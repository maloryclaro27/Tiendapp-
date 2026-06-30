import type { ProductFilters as ProductFilterValues } from "@/lib/api";
import { getBrands, getMetrics, getProducts } from "@/lib/api";
import { BrandRail } from "@/components/storefront/BrandRail";
import { ProductFilters } from "@/components/storefront/ProductFilters";
import { ProductGrid } from "@/components/storefront/ProductGrid";
import { StorefrontFooter } from "@/components/storefront/StorefrontFooter";
import { StorefrontHeader } from "@/components/storefront/StorefrontHeader";
import { StorefrontHero } from "@/components/storefront/StorefrontHero";

type PageSearchParams = Promise<Record<string, string | string[] | undefined>>;

function firstParam(value: string | string[] | undefined) {
  return Array.isArray(value) ? value[0] : value;
}

function buildFilters(searchParams: Record<string, string | string[] | undefined>): ProductFilterValues {
  return {
    search: firstParam(searchParams.search),
    brand_id: firstParam(searchParams.brand_id),
    unit_of_measure: firstParam(searchParams.unit_of_measure),
    availability: firstParam(searchParams.availability),
    sort: firstParam(searchParams.sort),
    page: firstParam(searchParams.page),
    per_page: 9,
  };
}

export default async function Home({
  searchParams,
}: {
  searchParams: PageSearchParams;
}) {
  const resolvedSearchParams = await searchParams;
  const filters = buildFilters(resolvedSearchParams);

  const [productsResponse, brands, metrics] = await Promise.all([
    getProducts(filters),
    getBrands(),
    getMetrics(),
  ]);

  return (
    <main className="min-h-screen bg-[#F6F3EC]">
      <StorefrontHeader />
      <StorefrontHero metrics={metrics} />
      <BrandRail brands={brands} />
      <ProductFilters brands={brands} filters={filters} />
      <ProductGrid products={productsResponse.data} />
      <StorefrontFooter />
    </main>
  );
}
