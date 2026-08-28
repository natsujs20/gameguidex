# Mapa técnico de GameGuideX

Documento de referencia rápida: dónde está cada cosa. Actualizar cuando
haya cambios estructurales (rutas nuevas, carpetas movidas, nuevos
Centros de Información, cambios de infraestructura).

> No incluir aquí credenciales ni valores de `.env`.

---

## 1. Stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13 + PHP 8.4 |
| Vistas | Blade (sin framework frontend) |
| Assets | Vite |
| Estilos | CSS plano con variables (sin Tailwind ni preprocesador) |
| Datos | **Actualmente MySQL local.** Supabase/PostgreSQL pendiente (ver §8) |

**Importante para ejecutar el proyecto:** requiere PHP >= 8.4.1. El PHP de
XAMPP (8.2) no sirve para `artisan`. Usar el de Herd:
`C:\Users\<usuario>\.config\herd\bin\php.bat`

```bash
npm run build
```

---

## 2. Layout y sistema visual

| Elemento | Archivo |
|---|---|
| Layout principal | `resources/views/layouts/app.blade.php` |
| Header (marca, nav, buscador, cuenta) | `resources/views/partials/navbar.blade.php` |
| Sidebar (navegación + centros) | `resources/views/partials/sidebar.blade.php` |
| Footer | `resources/views/partials/footer.blade.php` |
| **Sistema visual completo** | `resources/css/shell.css` |
| Reset + base + `.container` | `resources/css/app.css` (entrypoint de Vite) |
| JS del drawer móvil | inline al final de `layouts/app.blade.php` |

### Cómo está organizado el CSS

`app.css` es el entrypoint y solo contiene tipografía, import de
`shell.css`, reset y estilos base de elementos HTML.

`shell.css` contiene **toda** la identidad visual, en este orden:

1. **Tokens** (`:root`) — colores, espaciado, radios, medidas del layout
2. **Estructura** — header, sidebar, área de contenido, footer, mensajes
3. **Componentes base** — `.gtx-card`, `.gtx-btn`
4. **Componentes de página** — hero, catálogos, fichas, tablas, auth

**Para cambiar la identidad visual, empieza por los tokens de `:root`.**

Convención de nombres: todas las clases del sistema usan el prefijo
`gtx-`. Si ves una clase sin ese prefijo (salvo `container`, `footer`,
`flash-message`), probablemente sea código antiguo.

### Medidas del layout

El header y el sidebar son `position: fixed`. El espacio que ocupan se
reserva **en un solo sitio** (`.gtx-shell` en `shell.css`): `padding-top`
para el header y `margin-left` para el sidebar. Las páginas no deben
volver a compensarlo por su cuenta.

---

## 3. Páginas y rutas

Rutas en `routes/web.php`.

| Página | Ruta | Controlador | Vista |
|---|---|---|---|
| Inicio | `/` | `HomeController@index` | `welcome.blade.php` |
| Guías (índice) | `/guias` | `GuiaController@index` | `guias/index.blade.php` |
| Guía individual | `/guias/{slug}` | `GuiaController@show` | `guias/show.blade.php` |
| Centro Monster Hunter | `/guias/monster-hunter` | `MonsterHunterController@index` | `guias/monster-hunter/index.blade.php` |
| Centro Dragon Ball | `/guias/dragon-ball` | `DragonBallController@centro` | `guias/dragon-ball/index.blade.php` |
| Personajes Dragon Ball | `/dragon-ball/personajes` | `DragonBallController@personajes` | `dragon-ball/personajes/index.blade.php` |
| Ficha de personaje | `/dragon-ball/personajes/{slug}` | `DragonBallController@show` | `dragon-ball/personajes/show.blade.php` |
| Monstruos | `/monstruos` | `MonstruoController@index` | `monstruos/index.blade.php` |
| Ficha de monstruo | `/monstruos/{slug}` | `MonstruoController@show` | `monstruos/show.blade.php` |
| Videojuegos | `/juegos` | `JuegoController@index` | `juegos/index.blade.php` |
| Ficha de videojuego | `/juegos/{id}` | `JuegoController@show` | `juegos/show.blade.php` |
| Buscador global | `/buscar` | `BusquedaController@index` | `busqueda/index.blade.php` |
| Favoritos | `/favoritos` *(requiere login)* | `FavoritoController@index` | `favoritos/index.blade.php` |
| Alternar favorito | `POST /favoritos/alternar` *(requiere login)* | `FavoritoController@alternar` | — (redirect back) |
| Estadísticas | `/estadisticas` | `EstadisticasController@index` | `estadisticas/index.blade.php` |
| Login / Registro / Logout | `/login`, `/registro`, `/logout` | `AuthController` | `auth/*.blade.php` |

### Navegación

- **Header**: Explorar, Juegos, Guías + buscador global + cuenta.
- **Sidebar**: navegación general + Centros de Información.

El buscador del header apunta a `/buscar` (`BusquedaController`), que
consulta juegos, guías, monstruos y personajes de Dragon Ball a la vez
usando el scope `buscar()` de cada modelo.

**Regla:** solo se enlazan rutas que existen. Secciones de la referencia
visual como "Herramientas" o "Noticias" no están porque no tienen página.

---

## 4. Favoritos e historial

`/favoritos` y `/estadisticas` muestran datos reales del usuario
autenticado (no estados vacíos fijos): favoritos agrupados por tipo y
las últimas fichas visitadas. Ambas tablas (`favoritos`, `historial`)
usan una relación polimórfica (`elemento_type` + `elemento_id`) para
apuntar a un `Juego`, `Monstruo`, `Guia` o `PersonajeDragonBall` sin una
tabla por tipo — el mapeo de claves cortas está en el `morphMap` de
`AppServiceProvider::boot()`.

- Botón de favorito: componente `<x-favorito-boton :elemento tipo>`,
  presente en las 4 fichas individuales.
- Tarjeta genérica para mostrar cualquiera de los 4 tipos sin saber su
  clase de antemano: componente `<x-elemento-card :elemento>`.
- El historial se registra solo si hay sesión iniciada
  (`$request->user()->registrarVisita($elemento)` en cada método
  `show()`), con `updateOrCreate` para no crecer sin límite si el
  usuario repite visitas.
- Si en el futuro se agrega un quinto tipo favoriteable, hay que:
  1. Agregarlo al `morphMap` de `AppServiceProvider`.
  2. Agregarlo a `FavoritoController::TIPOS`.
  3. Agregar su caso en `x-elemento-card`.
  4. Poner `<x-favorito-boton>` en su vista `show`.

---

## 5. Modelos (`app/Models/`)

| Modelo | Tabla | Notas |
|---|---|---|
| `User` | `usuarios` | Columnas en español (`nombre`, `correo`, `clave`) |
| `Juego` | `juegos` | Scopes `monsterHunter()`, `dragonBall()`; accessor `imagen_url` |
| `Guia` | `guias` | |
| `Monstruo` | `monstruos` | Accessors `imagen_url`, `inicial` |
| `Material`, `FuenteMaterial`, `ParteMonstruo`, `DebilidadMonstruo` | | Datos de Monster Hunter |
| `PersonajeDragonBall` | `personajes_dragon_ball` | |
| `TecnicaDragonBall` | `tecnicas_dragon_ball` | |
| `Proyecto` | `proyectos` | Usado solo por la API |
| `Favorito` | `favoritos` | Relación polimórfica (`elemento_type`/`elemento_id`), sin `updated_at` |
| `HistorialVisita` | `historial` | Relación polimórfica; `visitado_en` se actualiza con `updateOrCreate` |

---

## 6. Migraciones y seeders

- Migraciones: `database/migrations/`
- Seeders: `database/seeders/` — uno por catálogo (`JuegoSeeder`,
  `MonsterHunterSeeder`, `DragonBallSeeder`, `GuiaSeeder`, etc.)

```bash
php artisan migrate
php artisan db:seed --class=JuegoSeeder
```

---

## 7. API y autenticación

- Rutas API: `routes/api.php`
- Auth web (sesión): `app/Http/Controllers/AuthController.php`
- Auth API (JWT propio): `app/Http/Controllers/Api/AuthController.php`
  + `app/Http/Middleware/JwtMiddleware.php`
- Configuración JWT: `config/jwt.php`

**Pendiente:** `laravel/sanctum` está instalado pero sin uso confirmado.
Decidir en la fase de seguridad si se elimina o reemplaza al JWT manual.

---

## 8. Datos e infraestructura — Supabase / PostgreSQL

Proyecto de Supabase: **GameGuideX** (`qicvqsrmtqxpiuofwaqj`, us-east-2).

### Estado

El código **ya es compatible con PostgreSQL**; falta únicamente activar
la conexión (requiere la contraseña de la base de datos). Mientras tanto
la app sigue corriendo sobre MySQL local.

`.env` contiene el bloque de Supabase preparado y comentado, con
instrucciones paso a paso.

### Cómo conectarse (importante)

Usar el **connection pooler en modo sesión**, no la conexión directa:

| Parámetro | Valor |
|---|---|
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | `aws-0-us-east-2.pooler.supabase.com` |
| `DB_PORT` | `5432` (modo sesión) |
| `DB_DATABASE` | `postgres` |
| `DB_USERNAME` | `postgres.qicvqsrmtqxpiuofwaqj` |
| `DB_SSLMODE` | `require` |

Motivos comprobados en este entorno:

- `db.qicvqsrmtqxpiuofwaqj.supabase.co:5432` (conexión directa) **no es
  accesible**: solo responde por IPv6.
- El puerto `6543` del pooler es *modo transacción* y rompe las consultas
  preparadas que usa PDO. Por eso se usa el `5432` del pooler.

### Migrar los datos

No se importa `desarrollo_software_1_backup.sql`: es un dump **MySQL** y
no es compatible con PostgreSQL. El catálogo se reconstruye con los
seeders, que son idempotentes:

```bash
php artisan migrate
php artisan db:seed
```

`DatabaseSeeder` ejecuta la cadena completa en el orden correcto
(juegos → centros de información → guías). Verificado: al ejecutarlo dos
veces los conteos no cambian.

### Compatibilidad PostgreSQL — revisado

| Punto | Estado |
|---|---|
| `LIKE` (case-sensitive en PG) | **Corregido**: 39 usos migrados a `whereLike()`/`orWhereLike()` nativos de Laravel, que emiten `ILIKE` en PG |
| `enum` en migraciones | OK — Laravel lo compila a `varchar` + `CHECK` |
| `unsignedTinyInteger` / `unsignedSmallInteger` | OK — se compilan a `smallint` |
| `orderByRaw` con `CASE` | OK — sintaxis válida en PG |
| `groupBy` de `MonstruoController` | OK — opera sobre una colección, no sobre SQL |
| `SELECT DISTINCT ... ORDER BY` | OK — la columna ordenada está en el `SELECT` |
| Driver `pdo_pgsql` | Instalado ✔ |

---

## 9. Seguridad — estado actual

- `.env` está en `.gitignore` ✔
- El proyecto todavía no es un repositorio Git, así que no hay
  credenciales versionadas ✔
- No hay claves ni secretos en Blade, CSS ni JS ✔

---

## 10. Assets

- Imágenes: `public/imagenes/` (`juegos/`, `monster-hunter/`, `categorias/`)
- **Falta:** `public/imagenes/dragon-ball/budokai-tenkaichi-3/`
  (iconos, ilustraciones y retratos de los 90 personajes). Mientras no
  existan, las vistas muestran un placeholder con la inicial.

---

## 11. Registro de decisiones

- Se eliminó el Centro de Digimon (controlador, servicio, vistas) porque
  dependía de rutas inexistentes y rompía `/estadisticas` y `/favoritos`.
- Se conectó el Centro de Dragon Ball, que tenía migración, seeder y
  vistas pero le faltaban modelos, controlador y rutas.
- Se unificó toda la interfaz en un solo sistema visual (`shell.css`).
  Se eliminaron 7 hojas de estilo por secciones (`guides.css`,
  `games.css`, `monsters.css`, `monster-hunter.css`, `dragon-ball.css`,
  `account-pages.css`, `monster-icons.css`) que duplicaban tarjetas,
  botones y badges. El CSS pasó de ~123 KB a ~20 KB.
- Se eliminaron los componentes Blade `featured-digimon`, `featured-game`,
  `category-card` y `buscador-guias` porque no los usaba ninguna vista.
- Los estilos inline repetidos se convirtieron en clases del sistema.
  Solo quedan 2 `style=` en `welcome.blade.php`, ambos con
  `background-image` dinámico procedente de la base de datos.
