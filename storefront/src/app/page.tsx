import type { ProductFilters as ProductFilterValues } from "@/lib/api";
import { getBrands, getMetrics, getProducts } from "@/lib/api";
import { BrandRail } from "@/components/storefront/BrandRail";
import { FeaturedProducts } from "@/components/storefront/FeaturedProducts";
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
    per_page: 6,
  };
}

export default async function Home({
  searchParams,
}: {
  searchParams: PageSearchParams;
}) {
  const resolvedSearchParams = await searchParams;
  const filters = buildFilters(resolvedSearchParams);

  const adminUrl = process.env.NEXT_PUBLIC_ADMIN_URL ?? "http://localhost:8080/admin";

  const [productsResponse, brands, metrics] = await Promise.all([
    getProducts(filters),
    getBrands(),
    getMetrics(),
  ]);

  return (
    <main className="min-h-screen bg-[#F6F3EC]">
      <StorefrontHeader adminUrl={adminUrl} />
      <StorefrontHero metrics={metrics} />
      <BrandRail brands={brands} />
      <FeaturedProducts products={productsResponse.data} />
      <ProductFilters brands={brands} filters={filters} />
      <ProductGrid
        products={productsResponse.data}
        meta={productsResponse.meta}
        filters={filters}
      />
      <StorefrontFooter />
    </main>
  );
}
