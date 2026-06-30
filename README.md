# Tiendapp Catalog - Prueba Tecnica Full Stack

Solucion Full Stack para la gestion administrativa de marcas y productos, junto con un storefront ecommerce desarrollado en Next.js que consume la API real del backend Laravel.

El proyecto prioriza una entrega profesional: arquitectura clara, API REST versionada, validaciones robustas, Docker, filtros, paginacion, metricas reales de inventario, pruebas automatizadas y una interfaz moderna.

---

## Stack tecnico

Backend:
- Laravel
- PHP-FPM
- MySQL 8
- Eloquent ORM
- Soft deletes
- Form Requests
- API REST versionada
- Unit tests
- Feature / integration tests

Admin:
- Laravel Blade
- Tailwind CSS
- Dashboard con metricas reales
- CRUD de marcas y productos

Storefront:
- Next.js 16
- React 19
- TypeScript
- Tailwind CSS
- Server Components
- Client Components
- Consumo de API real
- Loading state
- Error state
- Filtros y paginacion

DevOps:
- Docker Compose
- Nginx
- MySQL
- Backend PHP-FPM
- Storefront Next.js standalone
- Node 22 Alpine LTS

---

## Estructura del proyecto

```text
tiendapp-catalog/
  backend/                  Aplicacion Laravel
  storefront/               Ecommerce Next.js
  docker/
    backend/                Dockerfile PHP-FPM
    nginx/                  Configuracion Nginx
  docker-compose.yml
  README.md
```

---

## Servicios y puertos

| Servicio | URL / Puerto | Descripcion |
|---|---:|---|
| Storefront Next.js | http://localhost:3000 | Ecommerce publico |
| Admin Laravel | http://localhost:8080/admin | Panel administrativo |
| API Laravel | http://localhost:8080/api/v1 | API REST |
| MySQL | localhost:3307 | Base de datos local expuesta |

---

## Levantar el proyecto con Docker

Desde la raiz del proyecto:

1. Levantar los servicios:

```bash
docker compose up -d --build
```

2. El contenedor backend ejecuta un entrypoint no destructivo que:

- instala dependencias PHP con Composer si no existe vendor/
- crea .env desde .env.example si falta
- genera APP_KEY si esta vacia
- inicia php-fpm

No ejecuta migraciones ni seeders automaticamente para evitar operaciones destructivas sobre la base de datos.

3. Ejecutar migraciones y seeders:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

4. Abrir la aplicacion:

- Storefront: http://localhost:3000
- Admin Laravel: http://localhost:8080/admin
- API: http://localhost:8080/api/v1

---

## Evaluacion rapida

Para revisar la prueba de forma rapida:

```bash
docker compose up -d --build
docker compose exec backend php artisan migrate:fresh --seed
docker compose exec backend php artisan test
```

Luego abrir:

- Storefront: http://localhost:3000
- Admin Laravel: http://localhost:8080/admin
- API metrics: http://localhost:8080/api/v1/metrics

El backend cuenta con un entrypoint no destructivo que prepara dependencias, .env y APP_KEY cuando faltan. Las migraciones y seeders se ejecutan manualmente para mantener control sobre la base de datos.

---

## Variables de entorno del backend

El archivo backend/.env.example esta alineado con Docker y MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tiendapp
DB_USERNAME=tiendapp_user
DB_PASSWORD=tiendapp_password
```

---

## Variables de entorno del storefront

El storefront usa variables separadas para llamadas publicas e internas:

```env
NEXT_PUBLIC_API_BASE_URL=http://localhost:8080/api/v1
TIENDAPP_INTERNAL_API_URL=http://nginx/api/v1
NEXT_PUBLIC_ADMIN_URL=http://localhost:8080/admin
```

En Docker estas variables ya estan configuradas en docker-compose.yml.

---

## Funcionalidades principales

Admin Laravel:
- Crear, editar, listar y eliminar marcas.
- Crear, editar, listar y eliminar productos.
- Relacionar productos con marcas.
- Gestionar unidades de medida.
- Gestionar cantidades de inventario.
- Usar soft deletes.
- Visualizar metricas reales.
- Filtrar y ordenar productos.
- Revisar alertas operativas de inventario.

Storefront Next.js:
- Home conectada a la API Laravel.
- Hero comercial con metricas reales.
- Seccion de productos destacados.
- Carril de marcas.
- Filtros por busqueda, marca, unidad, disponibilidad y orden.
- Paginacion visible.
- Conservacion de filtros en querystring.
- Estados de carga y error.
- Cards de producto con estado visual de inventario.
- Imagenes locales por slug.

API REST:
- API versionada bajo /api/v1.
- Respuestas paginadas.
- Validacion formal de filtros.
- Respuestas 422 ante parametros invalidos.

---

## Endpoints principales

| Metodo | Endpoint | Descripcion |
|---|---|---|
| GET | /api/v1/metrics | Metricas generales del catalogo |
| GET | /api/v1/products | Lista paginada de productos |
| GET | /api/v1/brands | Lista paginada de marcas |
| GET | /api/v1/brands/select | Lista simple de marcas para selects |

---

## Filtros de productos

Endpoint:

```text
GET /api/v1/products
```

Parametros soportados:

| Parametro | Ejemplo | Descripcion |
|---|---|---|
| search | leche | Busca productos por texto |
| brand_id | 1 | Filtra por marca |
| unit_of_measure | Caja | Filtra por unidad |
| availability | low_stock | Filtra por estado de inventario |
| sort | stock_desc | Ordena resultados |
| page | 2 | Pagina actual |
| per_page | 6 | Cantidad por pagina, maximo 50 |

Valores permitidos para availability:
- available
- in_stock
- healthy_stock
- low_stock
- out_of_stock

Valores permitidos para sort:
- name
- stock_asc
- stock_desc
- updated_asc

Valores permitidos para unit_of_measure:
- Unidad
- Display
- Caja

---

## Validacion y robustez de la API

La API usa Form Requests dedicados:

- backend/app/Http/Requests/Api/ProductIndexRequest.php
- backend/app/Http/Requests/Api/BrandIndexRequest.php

Esto permite:
- Validar parametros antes de ejecutar consultas.
- Responder 422 ante filtros invalidos.
- Limitar per_page a maximo 50.
- Validar que brand_id exista y no este eliminado por soft delete.
- Mantener controladores limpios usando validated().

Ejemplo:

```bash
curl "http://localhost:8080/api/v1/products?per_page=100"
```

Respuesta esperada:

```text
422 Unprocessable Entity
```

---

## Semantica de inventario

| Estado | Condicion |
|---|---|
| Stock saludable | Inventario mayor al umbral de bajo stock |
| Bajo stock | Inventario entre 1 y el umbral definido |
| Sin stock | Inventario menor o igual a 0 |

Esta logica se centraliza en el backend para evitar inconsistencias entre dashboard, API y storefront.

---

## Datos de demostracion

Los seeders generan marcas y productos representativos:

Marcas:
- Alpina
- Colanta
- Nutresa
- Postobon
- Familia
- Noel
- Zenu
- Ramo

Productos:
- Leche Entera x 12
- Yogurt Fresa Familiar
- Queso Campesino
- Chocolate de Mesa Tradicional
- Gaseosa Manzana x 30
- Papel Higienico Familiar
- Galletas Surtidas
- Salchichas Tradicionales
- Chocoramo Display

---

## Comandos utiles

Backend:

```bash
docker compose exec backend php artisan migrate:fresh --seed
docker compose exec backend php artisan test
docker compose exec backend php artisan route:list
```

Storefront:

```bash
cd storefront
npm run lint
npm run build
```

Docker:

```bash
docker compose config --services
docker compose up -d --build
docker compose ps
docker compose logs -f storefront
docker compose down
```

---

## Pruebas automatizadas

El backend cuenta con pruebas unitarias y feature/integration tests para cubrir:

- Reglas unitarias de inventario.
- CRUD de marcas.
- CRUD de productos.
- Soft deletes.
- Metricas de catalogo.
- Filtros de productos.
- Validacion de parametros invalidos en API.
- Paginacion invalida en marcas.

Ejecutar:

```bash
docker compose exec backend php artisan test
```

Resultado esperado en el estado actual:

```text
15 passed / 103 assertions
```

---

## Decisiones tecnicas relevantes

API versionada:
Se usa /api/v1 para permitir evolucion futura sin romper consumidores existentes.

Form Requests:
La validacion de filtros se extrae de los controladores para mantener separacion de responsabilidades.

Scopes de Eloquent:
La logica de busqueda, filtros, disponibilidad y ordenamiento se centraliza en el modelo.

Soft deletes:
Se preserva trazabilidad en marcas y productos eliminados.

Next.js Server Components:
La home obtiene productos, marcas y metricas desde el servidor.

Docker standalone para Next.js:
El storefront usa output: "standalone" y Dockerfile multi-stage para producir una imagen mas limpia.

Imagenes del catalogo:
Actualmente viven en storefront/public/catalog. La arquitectura permite migrarlas despues a Laravel Storage exponiendo image_url y logo_url desde la API.

---

## Estrategia futura de imagenes

Fase actual:
- storefront/public/catalog/brands
- storefront/public/catalog/products

Fase futura:
- brands.logo_path
- products.image_path
- Laravel Storage
- php artisan storage:link
- API expone logo_url / image_url
- Next.js consume URLs reales de API

Esta decision evita guardar imagenes como BLOB en MySQL y mantiene la base de datos enfocada en informacion relacional.

---

## Estado actual de entrega

La solucion actualmente ofrece:

- Backend Laravel funcional.
- Admin conectado a MySQL.
- API REST versionada.
- Validaciones robustas.
- Storefront Next.js conectado a datos reales.
- Paginacion y filtros.
- Estados visuales modernos.
- Docker Compose con backend, nginx, MySQL y storefront.
- Tests backend.
- Seeders con datos realistas.

---

## Proximas mejoras recomendadas

- Agregar healthchecks para nginx y storefront.
- Hacer el backend Docker completamente autocontenido instalando dependencias en build.
- Agregar pruebas e2e del storefront.
- Implementar paginacion con ventana y elipsis para catalogos muy grandes.
- Ampliar busqueda de productos para incluir referencia de marca.
- Agregar coleccion Postman u OpenAPI.
- Exponer imagenes desde Laravel Storage.

---

## Autoria

Prueba tecnica desarrollada para Tiendapp S.A.S. con enfoque en calidad de entrega, experiencia de usuario, mantenibilidad y criterios reales de operacion de catalogo.