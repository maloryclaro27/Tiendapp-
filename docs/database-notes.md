# Database Notes

Technical notes for the Tiendapp Catalog database design, relationships, indexes, query patterns, and scalability considerations.

## Scope

This document explains the database decisions behind the Laravel + MySQL catalog implementation for brands, products, inventory status, filters, sorting, and API/admin queries.

## Relational Design

The catalog uses a normalized relational model centered on two business entities:

- `brands`: stores product brand information.
- `products`: stores catalog products and references each product to a brand.

The product availability state is not stored as a separate duplicated column. It is derived from `quantity_in_inventory`, which avoids inconsistencies between stored availability flags and inventory quantity.

This design keeps the schema simple, normalized, and appropriate for the expected catalog size of a technical test while still being defendable for hundreds or thousands of products.

## Tables

### brands

Stores brand records managed from the Laravel admin panel.

Relevant columns:

- `id`: primary key.
- `name`: brand display name.
- `reference`: unique brand identifier.
- `created_at` and `updated_at`: audit timestamps.
- `deleted_at`: soft delete marker.

### products

Stores products managed from the Laravel admin panel and exposed through the public API.

Relevant columns:

- `id`: primary key.
- `brand_id`: foreign key to `brands.id`.
- `name`: product display name.
- `unit_of_measure`: controlled unit value.
- `observations`: product notes or commercial description.
- `quantity_in_inventory`: inventory quantity used to derive availability.
- `inventory_updated_at`: timestamp for inventory freshness.
- `created_at` and `updated_at`: audit timestamps.
- `deleted_at`: soft delete marker.

## Relationships

The relationship between brands and products is one-to-many:

- A brand has many products.
- A product belongs to one brand.

At database level, `products.brand_id` references `brands.id`.

The foreign key uses restricted deletion to avoid orphan products. At application level, the admin prevents deleting a brand that still has associated products.

## Soft Deletes

Both `brands` and `products` use Laravel SoftDeletes through the `deleted_at` column.

This decision keeps historical records available without physically deleting rows from the database. It is useful for admin workflows, auditability, and avoiding accidental permanent data loss during catalog management.

Soft delete behavior is handled consistently in the application:

- Product creation and update validate `brand_id` only against non-deleted brands.
- Brand deletion is blocked when the brand still has associated products.
- Normal Eloquent queries automatically exclude soft-deleted records unless explicitly requested.

For this technical test, this is a defendable balance between data integrity and implementation complexity.

## Inventory Semantics

Inventory availability is derived from `quantity_in_inventory`.

The catalog uses these rules:

| Status | Rule |
|---|---|
| Available / In stock | `quantity_in_inventory > 0` |
| Healthy stock | `quantity_in_inventory > Product::LOW_STOCK_MAX` |
| Low stock | `quantity_in_inventory BETWEEN 1 AND Product::LOW_STOCK_MAX` |
| Out of stock | `quantity_in_inventory <= 0` |

The low stock threshold is centralized in `Product::LOW_STOCK_MAX`.

This avoids storing duplicated availability state and keeps the API, admin dashboard, product listing, and storefront consistent.

## Existing Indexes

### brands

| Index | Columns | Type | Purpose |
|---|---|---|---|
| `idx_brands_reference` | `reference` | Unique | Enforces unique brand references and supports exact lookup by reference. |
| `idx_brands_name` | `name` | B-tree | Supports ordering and simple name-based scans. |
| `idx_brands_active` | `deleted_at`, `name` | Composite B-tree | Supports active brand listings ordered by name while respecting soft deletes. |

### products

| Index | Columns | Type | Purpose |
|---|---|---|---|
| `idx_products_brand` | `brand_id` | B-tree | Supports the product-brand relationship and brand filters. |
| `idx_products_unit` | `unit_of_measure` | B-tree | Supports unit of measure filters. |
| `idx_products_stock` | `quantity_in_inventory` | B-tree | Supports stock filters and stock sorting. |
| `idx_products_inv_updated` | `inventory_updated_at` | B-tree | Supports inventory update ordering. |
| `idx_products_catalog` | `deleted_at`, `brand_id`, `unit_of_measure`, `name` | Composite B-tree | Supports common active catalog filters by brand, unit, and name. |
| `idx_products_available` | `deleted_at`, `quantity_in_inventory`, `brand_id` | Composite B-tree | Supports active availability and stock-range queries. |

These indexes are reasonable for the current catalog filters and for the scale expected in this technical test.

## Main Query Patterns

The catalog uses a small set of repeated query patterns across the admin panel and API.

### Product catalog listing

Used by:

- Admin product list.
- Public products API.
- Storefront product grid.

Typical query behavior:

- Excludes soft-deleted products by default.
- Eager loads the related brand to avoid N+1 queries.
- Applies optional filters for search, brand, unit of measure, and availability.
- Applies sorting by inventory, name, or inventory update date.
- Uses pagination to limit result size.

### Brand listing

Used by:

- Admin brand list.
- Public brands API.
- Storefront brand rail/select options.

Typical query behavior:

- Excludes soft-deleted brands by default.
- Orders brands by name.
- Uses `withCount('products')` where product counts are needed.
- Uses pagination for the full brands endpoint.

### Metrics

Used by:

- Admin dashboard.
- Public metrics API.
- Storefront hero metrics.

Typical query behavior:

- Counts total products.
- Counts available, healthy stock, low stock, and out-of-stock products.
- Sums total inventory units.
- Calculates percentages in the service layer.

The metrics implementation favors readability and explicitness. For larger datasets, the product counts could be consolidated into fewer conditional aggregate queries.

## Query Efficiency

### Eloquent relationship loading

Product listing queries eager load the `brand` relationship with `with('brand')`.

This avoids N+1 queries when rendering product lists in the admin, API resources, and storefront. Without eager loading, each product row could trigger an additional brand query.

### Filtering

The most important filters are backed by indexed columns:

- `brand_id`
- `unit_of_measure`
- `quantity_in_inventory`
- `inventory_updated_at`
- `deleted_at`

Availability filters are implemented as range checks on `quantity_in_inventory`, which is indexed.

### Sorting

The catalog supports sorting by:

- product name;
- inventory quantity ascending;
- inventory quantity descending;
- inventory update timestamp.

The current indexes support these sort fields reasonably for the expected data volume. For larger catalogs, additional composite indexes could be evaluated based on real query plans.

### Text search

Product search currently uses `LIKE "%term%"` on product text fields.

This is acceptable for a small technical test catalog and keeps the implementation simple. For larger datasets, a `FULLTEXT` index or dedicated search service would be more appropriate.

The current design intentionally avoids adding `FULLTEXT` prematurely because the catalog scale is small and the filters by brand, unit, stock, and ordering are more important for this exercise.

## EXPLAIN Validation

The following query patterns should be validated with `EXPLAIN` when evaluating the schema against a real MySQL database.

### Product catalog with default sorting

```sql
EXPLAIN
SELECT *
FROM products
WHERE deleted_at IS NULL
ORDER BY inventory_updated_at DESC
LIMIT 6;
```

Expected goal:

- Use an index related to `inventory_updated_at` or a composite index involving `deleted_at`.
- Avoid scanning unnecessary rows as the catalog grows.

### Product filter by brand

```sql
EXPLAIN
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND brand_id = 1
ORDER BY inventory_updated_at DESC
LIMIT 6;
```

Expected goal:

- Use `brand_id` or a composite catalog index.
- Keep filtering selective when a brand has many products.

### Product filter by stock availability

```sql
EXPLAIN
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND quantity_in_inventory > 0
ORDER BY inventory_updated_at DESC
LIMIT 6;
```

Expected goal:

- Use an index involving `quantity_in_inventory`.
- Keep range filtering efficient for availability views.

### Brand listing

```sql
EXPLAIN
SELECT *
FROM brands
WHERE deleted_at IS NULL
ORDER BY name ASC;
```

Expected goal:

- Use the composite active brand index on `deleted_at` and `name`.

These `EXPLAIN` checks are not required for the application to work, but they make the database design more defensible in a senior technical review.

## Scalability Considerations

The current design is appropriate for a small and medium catalog, including hundreds or thousands of products.

Strong points:

- Product-brand relationship is normalized.
- Availability is derived from inventory quantity instead of duplicated state.
- Common filters use indexed columns.
- Product listing uses pagination.
- API input validation limits `per_page` to a maximum of 50.
- Product queries eager load brands to avoid N+1 issues.
- Seeders are idempotent and provide realistic test data.

Known tradeoffs:

- Text search uses `LIKE "%term%"`, which is simple but not ideal for large datasets.
- Dashboard metrics currently use several explicit aggregate queries for readability.
- Pagination uses offset pagination, which is acceptable for this scope but can become less efficient at very high page numbers.
- Product images are currently stored as local storefront assets instead of being served from backend storage.

For this technical test, these tradeoffs are acceptable because they keep the system understandable, functional, and easy to evaluate. For production-scale catalogs, the next step would be validating real query plans and adding targeted optimizations only where measurements justify them.

## Future Improvements

The following improvements are intentionally left as future work because they should be justified by real data volume, query plans, or production requirements:

- Add `FULLTEXT` indexes for product and brand search if text search becomes a bottleneck.
- Consider a composite index on `deleted_at` and `inventory_updated_at` if default catalog ordering becomes expensive.
- Consider a composite index on `deleted_at`, `brand_id`, and `inventory_updated_at` if brand-filtered catalog pages become a frequent high-volume query.
- Consolidate dashboard metrics into conditional aggregate queries if product volume grows significantly.
- Replace offset pagination with cursor pagination for very large catalogs.
- Move catalog images to Laravel Storage and expose `image_url` and `logo_url` through API resources.
- Add automated query-plan checks or documented `EXPLAIN` output for the most important catalog queries.

The current implementation avoids adding unnecessary indexes or infrastructure before there is evidence that they are needed.