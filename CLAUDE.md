# CLAUDE.md — GameGuideX

Guía de contexto para retomar este proyecto en una sesión nueva. Léelo
completo antes de tocar código. El mapa de archivos más detallado está
en [`PROJECT_STRUCTURE.md`](PROJECT_STRUCTURE.md) — este documento es
el resumen de decisiones y estado; ese otro es la referencia técnica.

**No contiene contraseñas, tokens ni claves.** Todo secreto vive en
`.env` (ignorado por Git) o en el gestor de contraseñas del usuario.

---

## 1. Objetivo del proyecto

GameGuideX es una **enciclopedia/plataforma gamer**: guías, catálogo de
videojuegos y "Centros de Información" por franquicia (Monster Hunter,
Dragon Ball, con más planeados). Nace de un proyecto académico
("DigiDex", enfocado solo en Digimon) que se está evolucionando hacia
un producto profesional, serio y minimalista — dejando atrás la
estética de maqueta con la que empezó.

**Principio rector de todo el proyecto, repetido explícitamente por el
usuario varias veces: NO INVENTAR DATOS.** Nunca mostrar cifras, XP,
niveles, contadores o funcionalidades que no tengan datos reales
detrás. Si no existe el dato, se muestra un estado vacío honesto, no
un número simulado.

---

## 2. Arquitectura y stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13 + PHP 8.4 |
| Vistas | Blade (sin React/Vue/Angular — decisión explícita del usuario) |
| Assets | Vite |
| Estilos | CSS plano con variables, sin Tailwind ni preprocesador |
| Base de datos | **Supabase / PostgreSQL** (activa) |

**Ejecutar el proyecto requiere PHP >= 8.4.1.** El PHP de XAMPP (8.2) no
sirve para `artisan`. Usar el de Herd:
`C:\Users\<usuario>\.config\herd\bin\php.bat`

### Base de datos activa

El proyecto corre sobre **Supabase**, proyecto `GameGuideX2`
(`kbbtgobatjxegbkkzphc`, región us-east-2). Es el **segundo** proyecto
con ese nombre: el primero (`GameGuideX`, luego renombrado intentos como
`GameGuideX2` con ref `ypunrdijxjybzsujelkh`) se volvió inaccesible
tras hibernar y el usuario lo eliminó desde el dashboard de Supabase.

Conexión verificada y funcionando: **pooler en modo sesión**, puerto
`5432` del host `aws-0-us-east-2.pooler.supabase.com` (NO el `6543` de
modo transacción, que rompe las consultas preparadas de PDO; NO la
conexión directa `db.<ref>.supabase.co`, que en el proyecto anterior
solo respondía por IPv6).

La configuración completa (sin contraseña) está en `.env`. La
contraseña real solo vive ahí — pídesela al usuario si hace falta
reconectar, nunca la guardes en un archivo versionado.

Hay un **bloque de MySQL local comentado** en `.env` como respaldo, por
si hay que volver atrás.

### Nota importante sobre las herramientas MCP de Supabase

La integración MCP de Supabase de esta sesión de Claude está autenticada
con una cuenta/organización de Supabase que **no siempre coincide** con
la cuenta que el usuario usa en su navegador. Ya pasó una vez que
`list_projects` no mostraba el proyecto que el usuario veía en su
dashboard. Si vuelve a pasar, no asumas que el proyecto no existe:
pregunta al usuario o conéctate directo por PDO con las credenciales
que te dé (ver `.env`).

---

## 3. Estructura de archivos importantes

Ver `PROJECT_STRUCTURE.md` para el mapa completo. Lo esencial:

| Qué | Dónde |
|---|---|
| Layout principal | `resources/views/layouts/app.blade.php` |
| Header / Sidebar / Footer | `resources/views/partials/` |
| **Sistema visual completo** | `resources/css/shell.css` (tokens, componentes, todo) |
| Entrypoint de Vite | `resources/css/app.css` (solo tipografía + reset + import de shell.css) |
| Registro de Centros de Información | `config/centros.php` |
| Servicio que resuelve Centros con datos reales | `app/Services/CentrosInformacion.php` |
| Componente de tarjeta de Centro | `resources/views/components/centro-card.blade.php` |
| Rutas | `routes/web.php`, `routes/api.php` |
| Seeder maestro | `database/seeders/DatabaseSeeder.php` |

### Convención de nombres CSS

Todo el sistema visual usa el prefijo `gtx-`. Si ves una clase sin ese
prefijo (salvo `container`, `footer`, `flash-message`), es resto de
código antiguo y no debería usarse en vistas nuevas.

---

## 4. Funcionalidades terminadas

- **Catálogo de videojuegos** (`/juegos`) con filtros (franquicia,
  plataforma, año), búsqueda y paginación.
- **Enciclopedia de monstruos** (`/monstruos`) — Monster Hunter: 57
  monstruos, materiales, debilidades, partes rompibles.
- **Centro de Dragon Ball** (`/guias/dragon-ball`,
  `/dragon-ball/personajes`) — 90 personajes de Budokai Tenkaichi 3,
  transformaciones relacionadas, técnicas.
- **Guías** (`/guias`) con categorías reales y búsqueda.
- **Sistema de Centros de Información escalable**: los Centros no están
  hardcodeados en las vistas. Se declaran una vez en `config/centros.php`
  (nombre, franquicia, categorías) y `CentrosInformacion` calcula sus
  contadores reales agrupando por franquicia. Añadir un Centro nuevo no
  requiere tocar controladores ni vistas — solo esa config.
- **Autenticación** (login/registro/logout) con sesión.
- **Header + Sidebar + Footer profesionales**, responsive (el sidebar se
  vuelve drawer en móvil), con buscador funcional en el header.
- **Buscadores case-insensitive** en PostgreSQL (ver §5).
- **Buscador global** (`/buscar`, `BusquedaController`): un solo cuadro
  de texto en el header consulta juegos, guías, monstruos y personajes
  de Dragon Ball a la vez, agrupados por tipo. Reutiliza el scope
  `buscar()` que cada modelo ya tenía.
- **Favoritos e historial reales** (no placeholders): tablas
  `favoritos` y `historial`, ambas con relación polimórfica
  (`elemento_type`/`elemento_id` + morphMap en `AppServiceProvider`)
  para apuntar a cualquiera de los 4 tipos de contenido sin una tabla
  por tipo. El botón `<x-favorito-boton>` vive en las 4 fichas
  individuales; el historial se registra solo cuando hay sesión
  iniciada, con `updateOrCreate` (no crece sin límite si el usuario
  repite visitas). `/favoritos` y `/estadisticas` ahora muestran datos
  reales del usuario en vez del estado vacío fijo.
- **Importador de Steam** (`php artisan steam:importar-juegos --buscar="..."`)
  para enriquecer el catálogo con datos reales (portada, año, género,
  desarrollador) sin inventar nada. Ya existía pero su endpoint de
  búsqueda (`ISteamApps/GetAppList`) fue retirado por Steam; se
  reemplazó por `store.steampowered.com/api/storesearch` (§5, punto 10).
  No se ejecutó en bulk sobre el catálogo curado — hacerlo podría
  sobreescribir descripciones/franquicias ya revisadas a mano; probado
  solo con juegos fuera del catálogo actual.

---

## 5. Problemas corregidos

1. **Bug crítico de compatibilidad con PostgreSQL**: el código usaba
   `->where('col', 'like', ...)` en 39 sitios. En MySQL `LIKE` ignora
   mayúsculas; en PostgreSQL no. Se migró a `whereLike()`/`orWhereLike()`
   (métodos nativos de Laravel 13, sin helpers caseros), que emiten
   `ILIKE` en PostgreSQL. Sin esto, todos los buscadores habrían dejado
   de encontrar resultados en mayúsculas de forma silenciosa.
2. **Centro de Digimon eliminado**: dependía de una ruta inexistente
   (`route('digimon.index')`) y rompía `/estadisticas` y `/favoritos`.
3. **Centro de Dragon Ball conectado**: tenía migración, seeder y
   vistas, pero le faltaban los modelos (`PersonajeDragonBall`,
   `TecnicaDragonBall`), el controlador y las rutas.
4. **Bug de navbar**: usaba `auth()->user()->name` (columna inexistente;
   el modelo usa `nombre`), dejando el nombre de usuario en blanco.
5. **CSS muerto eliminado**: 7 hojas de estilo por sección
   (`guides.css`, `games.css`, `monsters.css`, `monster-hunter.css`,
   `dragon-ball.css`, `account-pages.css`, `monster-icons.css`) que
   duplicaban tarjetas y botones. El CSS pasó de ~200KB a ~20KB.
6. **61 de 63 estilos inline** convertidos a clases del sistema (los 2
   que quedan son `background-image` dinámico desde la base de datos).
7. **Drawer móvil roto**: usaba `left: calc(-1 * var(...))`, que el
   navegador no resolvía bien. Se cambió a `transform: translateX()`.
8. **Padding del header duplicado/perdido**: al centralizar el CSS, se
   unificó en un solo sitio (`.gtx-shell`) el espacio reservado para el
   header fijo y el sidebar, para que ninguna página tenga que
   compensarlo por su cuenta.
9. **4 Centros de Información escritos a mano en 3 archivos distintos**
   (portada, página de guías, sidebar) → refactorizados al sistema
   declarativo de `config/centros.php` + `CentrosInformacion`.
10. **Importador de Steam roto**: `ImportarJuegosSteam` usaba
    `api.steampowered.com/ISteamApps/GetAppList/v2/` para traer los
    ~150 000 AppID de Steam y filtrarlos en PHP por nombre. Steam
    retiró ese endpoint (responde 404 "Method 'GetAppList' not found").
    Se reemplazó por `store.steampowered.com/api/storesearch`, que
    busca por nombre directamente en el servidor de Steam — más simple
    y no depende de descargar el listado completo.

---

## 6. Decisiones técnicas y visuales

- **Sin frameworks frontend nuevos.** Todo en Blade + CSS plano, por
  pedido explícito del usuario.
- **Paleta**: fondo negro/grafito, paneles apenas diferenciados, texto
  blanco/gris, **cyan como acento reservado** para estados activos,
  botones principales y enlaces importantes — nunca decorativo por
  todas partes. El color ambiental de la interfaz lo aportan las
  imágenes reales de los videojuegos (portadas de Centros con imagen de
  fondo, no solo texto).
- **Layout de referencia**: header delgado (marca + nav + buscador +
  cuenta) + sidebar fijo con navegación y Centros + contenido principal.
  En móvil el sidebar es un drawer.
- **Centros de Información como registro declarativo**, no como HTML
  repetido: permite categorías distintas por Centro (Monster Hunter
  tiene monstruos/materiales, Dragon Ball tiene personajes/técnicas) sin
  forzar un esquema común.
- **Categorías con 0 elementos se ocultan**, no se muestran en cero.
- **`whereLike()` nativo de Laravel** en vez de convertir manualmente a
  minúsculas o usar SQL crudo — es la solución soportada por el
  framework para portabilidad MySQL/PostgreSQL.
- **Seeders siempre idempotentes** (`updateOrCreate`/`firstOrCreate`):
  se pueden re-ejecutar sin duplicar datos. Verificado corriendo la
  cadena completa dos veces seguidas con conteos idénticos.

---

## 7. Tareas pendientes

Cerradas en la sesión del 2026-08-27: sembrado de Supabase verificado
(48 juegos, 57 monstruos, 90 personajes de Dragon Ball, 22 técnicas, 25
materiales, 10 partes rompibles, 2 guías), app probada completa contra
Supabase (todas las rutas, búsqueda en mayúsculas confirma que `ILIKE`
funciona), RLS habilitado en las 20 tablas del proyecto Supabase
(lectura pública en tablas de contenido, bloqueo total en tablas
internas/sensibles — ver detalle abajo), CSS confirmado sin clases
muertas, y flujo completo de registro/login/logout/favoritos/
estadísticas probado en vivo (estados vacíos honestos, sin datos
inventados).

**Decisión sobre las imágenes de Dragon Ball (2026-08-27): no se van a
conseguir las 270 imágenes de personajes.** En su lugar, el buscador
por nombre/saga/raza de `/dragon-ball/personajes` (ya implementado en
`DragonBallController@personajes`, scope `buscar()` del modelo
`PersonajeDragonBall`) es la forma en que la gente encuentra al
personaje que necesita — la tarjeta muestra un placeholder con la
inicial en vez de arte del juego, y esto es un diseño definitivo, no un
estado temporal a corregir. Mismo criterio aplica a Monster Hunter si
en algún momento se plantea la misma duda con sus 57 monstruos: mejor
buscador funcional que depender de conseguir arte oficial.

Cerradas en la sesión del 2026-08-27 (segunda etapa): buscador global
multi-tipo (`/buscar`), sistema de favoritos/historial real (tablas
`favoritos` y `historial`, RLS habilitado en ambas igual que el resto),
y verificación/arreglo del importador de Steam como la vía de "APIs
externas" para enriquecer el catálogo (ver §5 punto 10). Probado en
vivo: crear cuenta, favoritear un personaje y un monstruo, ver
`/favoritos` agrupado por tipo, ver `/estadisticas` con conteos e
historial reales mezclando tipos.

En orden de prioridad sugerido para lo que sigue:

1. **Seguridad Supabase — pulir políticas RLS**: hoy las tablas de
   contenido tienen política de solo-lectura pública y las internas
   (incluidas `favoritos`/`historial`) están completamente bloqueadas
   desde la API REST (PostgREST) — solo Laravel las usa, vía el rol
   `postgres`. Si en el futuro se necesita acceso desde PostgREST
   (p. ej. una app móvil que hable directo con Supabase), hay que
   escribir esa política específica entonces, no abrir todo de golpe.
2. **Enriquecer el catálogo de juegos con Steam** (opcional, bajo
   demanda): el comando `steam:importar-juegos` ya funciona, pero no se
   corrió en bulk sobre los 48 juegos existentes porque podría
   sobreescribir descripciones y franquicias ya curadas a mano. Si se
   quiere usarlo para juegos que faltan en el catálogo, correrlo con
   `--buscar` apuntando a juegos puntuales y revisar el resultado antes
   de darlo por bueno.
3. **Producción y seguridad avanzada**: como estaba planeado, es lo
   último de la lista original, después de que el resto de
   funcionalidades esté probado.

---

## 8. Reglas a respetar al continuar

Estas son instrucciones explícitas del usuario, repetidas en varios
mensajes — no son sugerencias:

- **No inventar datos ni funcionalidades.** Ni cifras, ni XP, ni
  secciones de navegación hacia páginas que no existen.
- **No introducir frameworks frontend nuevos** (React/Vue/Angular/etc.)
  sin que el usuario lo pida explícitamente.
- **Antes de instalar cualquier dependencia nueva**, explicar: para qué
  sirve, qué problema resuelve, por qué el stack actual no basta, qué
  impacto tiene, qué mantenimiento implica. Esperar aprobación.
- **No hacer cambios masivos sin avisar.** Analizar alcance, explicar
  qué va a cambiar, y recién ahí implementar. Cambios pequeños y
  reversibles.
- **No modificar el esquema de la base de datos sin explicar antes por
  qué y qué impacto tiene.** No eliminar datos existentes. No crear
  tablas redundantes.
- **Nunca exponer secretos** (contraseñas, tokens, API keys) en código,
  Blade, CSS, JS, ni en documentos como este.
- **Después de cambios importantes, verificar**: que Laravel siga
  funcionando, que Vite compile, que las rutas respondan, que no haya
  errores de consola, que desktop y móvil se vean bien. No dar algo por
  terminado solo porque "se ve bien" visualmente.
- **Al terminar una etapa**, resumir: qué se hizo, qué archivos se
  modificaron/crearon, de dónde vienen los datos usados, qué probar, y
  sugerir un mensaje de commit (no commitear salvo que se pida).
- **El proyecto todavía no es un repositorio Git.** Si se inicializa,
  confirmar primero que `.env` seguirá en `.gitignore`.

---

## 9. Comandos para ejecutar y comprobar el proyecto

```bash
# Usar SIEMPRE este PHP (8.4), no el de XAMPP (8.2, no sirve para artisan)
PHP="C:\Users\<usuario>\.config\herd\bin\php.bat"

# Instalar dependencias (una sola vez / tras cambios en composer.json o package.json)
composer install
npm install

# Compilar assets (obligatorio tras tocar CSS/JS — no hay watch corriendo por defecto)
npm run build

# Limpiar caché de config tras tocar .env
"$PHP" artisan config:clear

# Migrar y sembrar (seguro de repetir, todo es idempotente)
"$PHP" artisan migrate --force
"$PHP" artisan db:seed --force

# Levantar servidor local
"$PHP" artisan serve --port=8877

# Verificar sintaxis de un archivo PHP
"$PHP" -l ruta/al/archivo.php

# Ver todas las rutas registradas
"$PHP" artisan route:list
```

**Nota sobre `db:seed` contra Supabase**: tarda 2-3 minutos (latencia de
red, no error) por la cantidad de `updateOrCreate` anidados en
`MonsterHunterSeeder` y `DragonBallSeeder`. Si se ejecuta en primer
plano puede agotar timeouts de terminal — lanzarlo en segundo plano y
revisar el log si tarda más de un minuto.
