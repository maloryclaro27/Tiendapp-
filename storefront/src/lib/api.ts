export type UnitOfMeasure = "Unidad" | "Display" | "Caja";

export type AvailabilityFilter =
  | "available"
  | "in_stock"
  | "healthy_stock"
  | "low_stock"
  | "out_of_stock";

export type ProductSort =
  | "name"
  | "stock_asc"
  | "stock_desc"
  | "updated_asc";

export type Brand = {
  id: number;
  name: string;
  reference: string;
  products_count?: number;
  created_at: string;
  updated_at: string;
};

export type Product = {
  id: number;
  name: string;
  unit_of_measure: UnitOfMeasure;
  observations: string;
  quantity_in_inventory: number;
  is_available: boolean;
  inventory_status: "Stock saludable" | "Bajo stock" | "Sin stock";
  inventory_updated_at: string;
  created_at: string;
  updated_at: string;
  brand: Brand | null;
};

export type PaginationLink = {
  url: string | null;
  label: string;
  page: number | null;
  active: boolean;
};

export type PaginatedResponse<T> = {
  data: T[];
  links: {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  };
};

export type CollectionResponse<T> = {
  data: T[];
};

export type CatalogMetrics = {
  total_brands: number;
  total_products: number;
  total_inventory_units: number;
  available_products: number;
  healthy_stock_products: number;
  low_stock_products: number;
  out_of_stock_products: number;
  stock_alerts: number;
  stock_health_percent: number;
  available_percent: number;
  healthy_stock_percent: number;
  low_stock_percent: number;
  out_of_stock_percent: number;
};

type MetricsApiResponse = CatalogMetrics | { data: CatalogMetrics };

export type ProductFilters = {
  search?: string;
  brand_id?: string | number;
  unit_of_measure?: string;
  availability?: string;
  sort?: string;
  page?: string | number;
  per_page?: string | number;
};

const DEFAULT_PUBLIC_API_BASE_URL = "http://localhost:8080/api/v1";

function apiBaseUrl(): string {
  if (typeof window === "undefined") {
    return (
      process.env.TIENDAPP_INTERNAL_API_URL ??
      process.env.API_INTERNAL_URL ??
      process.env.NEXT_PUBLIC_API_BASE_URL ??
      DEFAULT_PUBLIC_API_BASE_URL
    );
  }

  return process.env.NEXT_PUBLIC_API_BASE_URL ?? DEFAULT_PUBLIC_API_BASE_URL;
}

function buildQueryString(params: ProductFilters = {}): string {
  const searchParams = new URLSearchParams();

  Object.entries(params).forEach(([key, value]) => {
    if (value === undefined || value === null || value === "") {
      return;
    }

    searchParams.set(key, String(value));
  });

  const queryString = searchParams.toString();

  return queryString ? `?${queryString}` : "";
}

async function apiFetch<T>(
  path: string,
  init?: RequestInit,
): Promise<T> {
  const response = await fetch(`${apiBaseUrl()}${path}`, {
    ...init,
    headers: {
      Accept: "application/json",
      ...init?.headers,
    },
  });

  if (!response.ok) {
    throw new Error(`API request failed: ${response.status} ${response.statusText}`);
  }

  return response.json() as Promise<T>;
}

export async function getProducts(
  filters: ProductFilters = {},
): Promise<PaginatedResponse<Product>> {
  return apiFetch<PaginatedResponse<Product>>(
    `/products${buildQueryString({
      per_page: 12,
      ...filters,
    })}`,
    {
      next: {
        revalidate: 60,
      },
    },
  );
}

export async function getBrands(): Promise<Brand[]> {
  const response = await apiFetch<CollectionResponse<Brand>>("/brands/select", {
    next: {
      revalidate: 60,
    },
  });

  return response.data;
}

export async function getMetrics() {
  const response = await apiFetch<MetricsApiResponse>("/metrics", {
    next: { revalidate: 60 },
  });

  return "data" in response ? response.data : response;
}
