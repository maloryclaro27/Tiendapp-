# Database Performance Notes

This document consolidates the MySQL/InnoDB database audit performed for the
Tiendapp catalog. It records only evidence observed during the audit: real
`SHOW CREATE TABLE`, `SHOW INDEX`, `EXPLAIN`, migration execution, rollback,
and test output.

## 1. Real Schema

Captured after applying the final index migration.

### brands

```sql
CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_brands_reference` (`reference`),
  KEY `idx_brands_active` (`deleted_at`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### products

```sql
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_of_measure` enum('Unidad','Display','Caja') COLLATE utf8mb4_unicode_ci NOT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_in_inventory` int unsigned NOT NULL DEFAULT '0',
  `inventory_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_products_brand` (`brand_id`),
  KEY `idx_products_catalog` (`deleted_at`,`brand_id`,`unit_of_measure`,`name`),
  KEY `idx_products_available` (`deleted_at`,`quantity_in_inventory`,`brand_id`),
  KEY `idx_products_active_inv_updated` (`deleted_at`,`inventory_updated_at`),
  KEY `idx_products_active_brand_inv_updated` (`deleted_at`,`brand_id`,`inventory_updated_at`),
  KEY `idx_products_active_unit_inv_updated` (`deleted_at`,`unit_of_measure`,`inventory_updated_at`),
  KEY `idx_products_active_brand_unit_inv_updated` (`deleted_at`,`brand_id`,`unit_of_measure`,`inventory_updated_at`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

## 2. Final Indexes

Primary keys exist on both tables and are omitted from the business-index
count below. The audit focused on the two non-primary brand indexes and the
seven non-primary product indexes.

| Table | Index | Columns | Real query served | Evidence |
|---|---|---|---|---|
| brands | `idx_brands_reference` | `reference` | Seeder lookup in `backend/database/seeders/ProductSeeder.php:89`: `Brand::where('reference', ...)` | `EXPLAIN SELECT * FROM brands WHERE reference = 'ALPINA-001' ...` used `key=idx_brands_reference`, `type=const`, `rows=1`. |
| brands | `idx_brands_active` | `deleted_at`, `name` | Active brand lists ordered by name in `backend/app/Http/Controllers/Api/V1/BrandController.php:18-29`, `select()` at `37-40`, and admin `backend/app/Http/Controllers/Admin/BrandController.php:18-29` | `brands_with_count` used `key=idx_brands_active`; `brands_select` used `key=idx_brands_active`, `Extra=Using index condition`. |
| products | `idx_products_brand` | `brand_id` | Direct FK child lookup and relation support for `Brand::products()` in `backend/app/Models/Brand.php:20-23` | `EXPLAIN SELECT * FROM products WHERE brand_id = 1` used `key=idx_products_brand`, `rows=4`. Soft-delete-aware catalog queries prefer composite indexes, which is expected. |
| products | `idx_products_catalog` | `deleted_at`, `brand_id`, `unit_of_measure`, `name` | Combined brand + unit + name sort, allowed by API filters in `backend/app/Http/Controllers/Api/V1/ProductController.php:21-24` and `Product::scopeSorted()` in `backend/app/Models/Product.php:96-104` | `brand_unit_sort_name` used `key=idx_products_catalog`, `Extra=Using index condition`, no filesort. |
| products | `idx_products_available` | `deleted_at`, `quantity_in_inventory`, `brand_id` | Stock filters in `backend/app/Models/Product.php:85-93`, stock sorting in `Product.php:100-101`, and dashboard stock metrics in `backend/app/Services/DashboardMetricsService.php:15-29` | `stock_asc_without_idx_products_stock` used `key=idx_products_available`; `stock_desc_without_idx_products_stock` used `key=idx_products_available`; metric stock queries used `idx_products_available`. |
| products | `idx_products_active_inv_updated` | `deleted_at`, `inventory_updated_at` | Default catalog ordering in `backend/app/Models/Product.php:103` and product index controllers `Api/V1/ProductController.php:18-25`, `Admin/ProductController.php:18-27` | Validated against final schema: `catalog_default` used `key=idx_products_active_inv_updated`, `Extra=Using where; Backward index scan`. `available_filter` also used this index with no filesort. |
| products | `idx_products_active_brand_inv_updated` | `deleted_at`, `brand_id`, `inventory_updated_at` | Brand-filtered catalog in `Product::scopeByBrand()` at `backend/app/Models/Product.php:67-74` plus default inventory ordering | Validated against final schema: `brand_filter` used `key=idx_products_active_brand_inv_updated`, `Extra=Using where; Backward index scan`. |
| products | `idx_products_active_unit_inv_updated` | `deleted_at`, `unit_of_measure`, `inventory_updated_at` | Unit-filtered catalog in `Product::scopeByUnit()` at `backend/app/Models/Product.php:76-83` plus default inventory ordering | Validated against final schema: `unit_filter` used `key=idx_products_active_unit_inv_updated`, `Extra=Using where; Backward index scan`. |
| products | `idx_products_active_brand_unit_inv_updated` | `deleted_at`, `brand_id`, `unit_of_measure`, `inventory_updated_at` | Brand + unit filtered catalog from combined `scopeByBrand()`, `scopeByUnit()`, and default `scopeSorted()` | Validated against final schema: `brand_unit_filter` used `key=idx_products_active_brand_unit_inv_updated`, `Extra=Using where; Backward index scan`. |

## 3. Removed Indexes

| Table | Removed index | Why it was removed | Evidence |
|---|---|---|---|
| brands | `idx_brands_name` | Active brand queries are soft-delete-aware and order by name through `deleted_at, name`. | Initial `SHOW INDEX FROM brands` showed both `idx_brands_name(name)` and `idx_brands_active(deleted_at,name)`. `brands_with_count` and `brands_select` used `idx_brands_active`, not `idx_brands_name`. |
| products | `idx_products_stock` | Stock queries are soft-delete-aware; `idx_products_available(deleted_at, quantity_in_inventory, brand_id)` covers them better. | `EXPLAIN ... IGNORE INDEX (idx_products_stock)` showed `out_of_stock_without_idx_products_stock`, `stock_asc_without_idx_products_stock`, `stock_desc_without_idx_products_stock`, and `metric_out_of_stock_without_idx_products_stock` using `idx_products_available`. |
| products | `idx_products_inv_updated` | The real catalog always excludes soft-deleted rows, so a simple `inventory_updated_at` index does not match the query shape. | Initial default catalog EXPLAIN used `idx_products_catalog` and still had `Using filesort`; temporary `idx_products_active_inv_updated(deleted_at, inventory_updated_at)` removed filesort for default catalog. |
| products | `idx_products_unit` | The simple unit index filtered unit values but could not satisfy the default `ORDER BY inventory_updated_at`. | Initial `unit_filter` used `key=idx_products_unit`, `Extra=Using where; Using filesort`. With `idx_products_active_unit_inv_updated` and `IGNORE INDEX (idx_products_unit)`, the query used the composite index and removed filesort. |

## 4. EXPLAIN By Real Use Case

These SELECTs mirror the Eloquent query shapes used by the API/admin product
listings. Laravel SoftDeletes adds `deleted_at IS NULL`.

### 4.1 Catalog default

```sql
SELECT *
FROM products
WHERE deleted_at IS NULL
ORDER BY inventory_updated_at DESC
LIMIT 12;
```

Validated against final schema:

```text
key=idx_products_active_inv_updated
rows=10
Extra=Using where; Backward index scan
```

Result: filesort disappeared.

### 4.2 Brand filter

```sql
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND brand_id = 1
ORDER BY inventory_updated_at DESC
LIMIT 12;
```

Validated against final schema:

```text
key=idx_products_active_brand_inv_updated
rows=2
Extra=Using where; Backward index scan
```

Result: filesort disappeared.

### 4.3 Unit filter

```sql
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND unit_of_measure = 'Caja'
ORDER BY inventory_updated_at DESC
LIMIT 12;
```

Validated against final schema:

```text
key=idx_products_active_unit_inv_updated
rows=3
Extra=Using where; Backward index scan
```

Result: filesort disappeared.

### 4.4 Brand + unit filter

```sql
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND brand_id = 1
  AND unit_of_measure = 'Caja'
ORDER BY inventory_updated_at DESC
LIMIT 12;
```

Validated against final schema:

```text
key=idx_products_active_brand_unit_inv_updated
rows=1
Extra=Using where; Backward index scan
```

Result: filesort disappeared.

### 4.5 Available filter

```sql
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND quantity_in_inventory > 0
ORDER BY inventory_updated_at DESC
LIMIT 12;
```

Validated against final schema:

```text
key=idx_products_active_inv_updated
rows=10
Extra=Using where; Backward index scan
```

Result: filesort disappeared. MySQL scans active products in inventory update
order and applies the availability predicate as a residual filter.

### 4.6 Low stock filter

```sql
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND quantity_in_inventory BETWEEN 1 AND 10
ORDER BY inventory_updated_at DESC
LIMIT 12;
```

Validated against final schema:

```text
key=idx_products_available
key_len=9
rows=2
Extra=Using index condition; Using filesort
```

Result: filesort remains as an accepted limitation.

### 4.7 Out of stock filter

```sql
SELECT *
FROM products
WHERE deleted_at IS NULL
  AND quantity_in_inventory <= 0
ORDER BY inventory_updated_at DESC
LIMIT 12;
```

Validated against final schema:

```text
key=idx_products_available
key_len=9
rows=2
Extra=Using index condition; Using filesort
```

Historical context: before removing the simple stock index, MySQL had selected
`idx_products_stock` for this query:

```text
key=idx_products_stock
rows=2
Extra=Using where; Using filesort
```

Result: filesort remains as an accepted limitation.

## 5. Documented Limitation: Stock Ranges

`low_stock` and `out_of_stock` intentionally keep a filesort when ordered by
`inventory_updated_at`.

The technical reason is the B-tree range rule. `low_stock` uses:

```sql
quantity_in_inventory BETWEEN 1 AND 10
```

and `out_of_stock` uses:

```sql
quantity_in_inventory <= 0
```

Once MySQL uses a range condition on `quantity_in_inventory`, later columns in
the same B-tree index cannot guarantee a global order by
`inventory_updated_at`. The remaining sort is therefore expected.

A generated `stock_status` column could turn these range predicates into an
equality predicate, for example `stock_status = 'low_stock'`, and an index like
`(deleted_at, stock_status, inventory_updated_at)` could remove the filesort.
That was intentionally not implemented because it is more invasive than the
scope of this technical test requires. The result set for low/out-of-stock is
small in the current catalog, and the existing `idx_products_available` still
serves the filtering efficiently.

## 6. Documented Limitation: LIKE Search

Product search currently uses contains-style matching:

```php
// backend/app/Models/Product.php:61-64
$query->where('name', 'like', "%{$search}%")
    ->orWhere('observations', 'like', "%{$search}%");
```

Brand search also uses contains-style matching:

```php
// backend/app/Http/Controllers/Admin/BrandController.php:23-25
$query->where('name', 'like', "%{$search}%")
    ->orWhere('reference', 'like', "%{$search}%");

// backend/app/Http/Controllers/Api/V1/BrandController.php:23-25
$query->where('name', 'like', "%{$search}%")
    ->orWhere('reference', 'like', "%{$search}%");
```

The storefront exposes this as a free text search:

```text
storefront/src/components/storefront/ProductFilters.tsx:64
placeholder="Buscar producto o referencia"

storefront/src/components/storefront/StorefrontHeader.tsx:36
Buscar productos, marcas o referencias
```

`LIKE 'texto%'` would be more compatible with a normal B-tree index, but it
would only support prefix search. That is less useful for the current UI, which
communicates broad free-text search and also searches `observations`, not only
`name`.

`FULLTEXT` would be the scalable alternative for larger catalogs, but it was
not implemented because it would expand the scope of this test. For hundreds or
low thousands of products, the current `LIKE '%texto%'` behavior is an accepted
tradeoff and should be documented rather than over-engineered.

## 7. Charset And Collation

Migration files do not explicitly declare `charset`, `collation`, or `utf8mb4`
per table. This was verified with:

```powershell
Select-String -Path .\backend\database\migrations\*.php -Pattern "charset","collation","utf8mb4"
```

The command returned no migration matches.

The real MySQL schema was then verified with `SHOW CREATE TABLE` after applying
the final migration. Both tables use:

```sql
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

Text columns also show `COLLATE utf8mb4_unicode_ci`.

Decision: no migration change is required for this test. The schema inherits
Laravel/MySQL modern defaults, and the result was verified against the real
engine. Explicit table-level declarations could be added in a stricter
production hardening pass, but they are not necessary here.

## 8. Referential Integrity: Restrictive FK

The `products.brand_id` foreign key is enforced by MySQL/InnoDB:

```sql
CONSTRAINT `products_brand_id_foreign`
FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT
```

Direct engine-level deletion test:

```sql
DELETE FROM brands WHERE id = 1;
```

MySQL returned the real error:

```text
ERROR 1451 (23000): Cannot delete or update a parent row:
a foreign key constraint fails (`tiendapp`.`products`,
CONSTRAINT `products_brand_id_foreign`
FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT)
```

Follow-up SELECT confirmed the row was not deleted:

```text
id  name    deleted_at
1   Alpina  NULL

active_products_for_brand_1
2
```

Conclusion: referential integrity is guaranteed at the database engine level,
not only by Laravel application logic.

## 9. Migration Reversibility

The final migration was tested in both directions:

```powershell
docker compose exec backend php artisan migrate
docker compose exec backend php artisan migrate:rollback --step=1
docker compose exec backend php artisan migrate
```

Observed output:

```text
2026_06_30_000000_refine_catalog_indexes ... DONE
2026_06_30_000000_refine_catalog_indexes ... DONE
2026_06_30_000000_refine_catalog_indexes ... DONE
```

Conclusion: `up()` and `down()` both execute successfully in the real Docker
MySQL environment, and the migration is practically reversible.

## 10. Testing Status

The full Laravel test suite was executed after applying the new indexes:

```powershell
docker compose exec backend php artisan test
```

Observed result:

```text
Tests: 15 passed (103 assertions)
Duration: 57.33s
```

This confirms the index migration and the dashboard ordering change did not
break the current automated test suite.

Known testing limitation: `backend/phpunit.xml` uses SQLite in memory:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

Therefore the automated suite does not validate MySQL query plans, collation,
InnoDB foreign-key behavior, or index selection. MySQL behavior was validated
manually in this audit with `SHOW CREATE TABLE`, `SHOW INDEX`, `EXPLAIN`, and
the FK delete test. A future improvement would be an additional
`phpunit.mysql.xml` configuration that runs the same suite against a separate
`tiendapp_testing` MySQL database.
