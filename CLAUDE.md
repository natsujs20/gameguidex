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

### Repositorio Git y despliegue

El proyecto **ya es un repositorio Git**, alojado en GitHub como
`natsujs20/gameguidex`, y se despliega automáticamente en **Railway**
(`https://gameguidex-production.up.railway.app`) sobre la misma base de
Supabase que se usa en local — no hay una base separada para
producción.

Flujo de trabajo establecido para cualquier cambio:

1. Rama nueva desde `main` (`git checkout -b tipo/nombre-descriptivo`).
2. Commit + `git push -u origin <rama>`.
3. `gh pr create` con resumen y plan de pruebas.
4. `gh pr merge <n> --merge --delete-branch` (con confirmación del
   usuario primero).
5. Redeploy en Railway con `connect-service-source` (proyecto
   `e2aee89f-c127-4aa2-bf6a-ff99d896b2ee`, servicio
   `e7e09400-f426-4b43-9e98-bd6e82a2969e`, rama `main`) — **no uses
   `redeploy` a secas**, reutiliza el build anterior y no recoge el
   código nuevo.
6. Verificar con `curl` contra la URL de producción antes de dar el
   cambio por terminado.

`.env` sigue sin subirse (confirmado con `git check-ignore -v .env`
antes de cada push). Las variables de producción (incluida la
contraseña de Supabase y `JWT_SECRET`) viven en las variables de
entorno de Railway, no en el repositorio.

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
- **Trailers de Steam en la ficha de juego** (`Juego::trailer_url`):
  extraídos con `steam:importar-juegos` desde `movies[].mp4|webm` de
  `appdetails`, con fallback a `hls_h264` (Steam dejó de exponer
  `mp4`/`webm` directos para varios juegos). Reproducción vía `hls.js`
  en `resources/js/app.js` para navegadores sin HLS nativo.
- **Perfil de usuario** (`/perfil`, `PerfilController`, requiere login):
  edición de nombre/correo, cambio de clave (pide la clave actual),
  eliminar cuenta (pide confirmar la clave, borra en cascada
  favoritos/historial/juegos jugados), y "juegos jugados"
  (`juegos_jugados`, tabla pivote con `jugado_en`) que el usuario marca
  desde la ficha de cada juego. Estadísticas del perfil (total
  favoritos, visitas, franquicia favorita, últimos vistos) calculadas
  en el momento a partir de datos reales, nunca guardadas como cifra
  fija.
- **Recuperación de clave por correo** (`/clave-olvidada`,
  `PasswordResetController`): flujo manual con el broker de contraseñas
  de Laravel en vez de `Password::reset()` directo — ver §5, punto 12.
  En producción el correo solo se registra en el log
  (`MAIL_MAILER=log`), no se envía de verdad todavía; hay un driver de
  Resend configurado en `config/services.php` pendiente de una API key.
- **API REST de Proyectos** (`/api/proyectos`, JWT propio): CRUD
  completo (`GET` lista paginada, `GET` por id, `POST`, `PUT`/`PATCH`,
  `DELETE`) que cumple exactamente los códigos HTTP de un ejercicio de
  evaluación (201 al crear con todos los campos obligatorios, 200
  paginado al listar, 404 en ids inexistentes vía route model binding,
  200 con los campos actualizados, 204 sin cuerpo al eliminar). Cada
  proyecto pertenece a un usuario (`created_by`) y `ProyectoController`
  verifica esa propiedad antes de mostrar/editar/borrar (403 si no es
  el dueño).
- **Seguridad**: límite de intentos (`throttle:5,1`) en login, registro,
  cambio y recuperación de clave (web y API) — antes no existía ningún
  límite y permitía fuerza bruta sin restricción (ver §5, punto 13).
  Middleware `SecurityHeaders` global con CSP, `X-Frame-Options`,
  `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy` y
  HSTS.
- **15 guías largas adicionales** (`GuiaAmpliadaSeeder`, además de las 2
  originales de `GuiaSeeder`): Monster Hunter: World, Dragon Ball Z:
  Budokai Tenkaichi 3, Elden Ring, Dark Souls Remastered y Zelda:
  Breath of the Wild — 17 guías en total.
- **Arte real para 38 de los 90 personajes de Dragon Ball** (ver
  decisión revisada en §7): descargado desde `dragonball-api.com` hacia
  las rutas de `icono`/`ilustracion`/`retrato` que ya estaban guardadas
  en la BD. Los 52 restantes siguen con el placeholder + buscador
  (comportamiento intencional, no pendiente).
- **Logo real en el header** (`public/imagenes/logo.png` /
  `logo-icono.png`, recorte automático con PIL): reemplaza el ícono "G"
  de texto. La intro animada con video que se probó junto con el logo
  se **descartó** (ver §6) — el logo en el header se mantuvo.

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
11. **Duplicados y sobreescritura del importador de Steam**: coincidía
    juegos por nombre exacto (`where('nombre', ...)`), así que
    diferencias de mayúsculas/puntuación creaban un juego duplicado en
    vez de enlazar el existente, y podía sobreescribir descripciones
    curadas a mano. Se cambió a `orWhereLike()` (case-insensitive) y se
    agregó un chequeo: si el juego existente no vino de Steam
    originalmente, solo se rellenan `steam_app_id`/`steam_url`/
    `trailer_url`, nunca se sobreescribe el resto.
12. **`Password::reset()` incompatible con el esquema en español**: el
    helper de Laravel arma la búsqueda del usuario con **todas** las
    claves del arreglo de credenciales cuyo nombre contenga
    "password"; como esta app usa la columna `clave`, terminaba
    buscando `where('clave', $claveSinCifrar)`, que nunca encuentra
    nada. Se resolvió validando el token a mano con
    `Password::broker()->tokenExists()`/`deleteToken()` en vez de usar
    `Password::reset()` directo (`PasswordResetController::restablecer`).
13. **Sin límite de intentos en login/registro/recuperación de clave**:
    verificado en producción con `curl` — 10 intentos de login
    fallidos seguidos, con sesión y CSRF válidos, pasaban todos sin
    bloqueo. Permitía fuerza bruta de contraseñas sin restricción
    contra cualquier cuenta conocida. Corregido con `throttle:5,1` en
    esas rutas (web y API) y en `/perfil/clave`/`DELETE /perfil`.
14. **Faltaban cabeceras de seguridad estándar**: no había
    `Content-Security-Policy`, `X-Frame-Options`, `X-Content-Type-
    Options` ni `Strict-Transport-Security` — la página podía embeberse
    en un iframe ajeno (clickjacking). Corregido con el middleware
    `SecurityHeaders` (`app/Http/Middleware/SecurityHeaders.php`),
    aplicado globalmente.
15. **API de Proyectos no cumplía los códigos HTTP exactos de un
    desafío de evaluación**: `descripcion` era opcional (debía ser
    obligatoria igual que `nombre`), el listado no estaba paginado, y
    `DELETE` devolvía 200 con un JSON en vez de 204 sin cuerpo.
    Corregido en `Api\ProyectoController` y verificado extremo a
    extremo contra Supabase (201/422/200/404/204 en cada caso).

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
- **Intro animada del logo (video) descartada** (2026-09-05): se
  implementó (video una vez por sesión de pestaña, con botón de
  saltar) pero se decidió quitarla antes de la defensa del proyecto:
  pesaba 2.2MB para algo que se ve una sola vez, era un punto de falla
  extra en una demo en vivo, y no aportaba a lo que se evalúa
  (funcionalidad). El logo en el header se mantuvo.
- **Imágenes de Dragon Ball — decisión de 2026-08-27 revisada
  parcialmente (2026-09-05)**: se había decidido no conseguir las 270
  imágenes de personajes (ver histórico más abajo). Se retomó la idea
  usando `dragonball-api.com` (API pública, gratis, sin auth) y se
  consiguió arte real para 38 de los 90 personajes de nuestra base,
  cruzando nombre/transformación contra la API. **No se aplicó a
  ciegas**: se descartaron 2 imágenes que resultaron ser fan-art de
  DeviantArt con firma de autor (detectadas por el nombre de archivo:
  sufijos `-preview`, `removebg`, `by_<usuario>`, y una firma visible
  en la imagen), y se dejaron sin imagen los personajes donde la forma
  de la API no correspondía exactamente a la nuestra (mejor placeholder
  honesto que arte del personaje/forma equivocada). El resto de la
  decisión original sigue vigente: el buscador funcional es la forma
  principal de encontrar personajes, no depender de tener arte de los
  90.

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

> **Actualización 2026-09-05**: esta decisión se revisó parcialmente,
> no se abandonó — ver el detalle completo en §6. Se consiguió arte
> real para 38 de los 90 personajes vía una API pública, con criterio
> estricto (sin fan-art, sin coincidencias forzadas). Los 52 restantes
> siguen exactamente con el mismo comportamiento descrito arriba.

Cerradas en la sesión del 2026-08-27 (segunda etapa): buscador global
multi-tipo (`/buscar`), sistema de favoritos/historial real (tablas
`favoritos` y `historial`, RLS habilitado en ambas igual que el resto),
y verificación/arreglo del importador de Steam como la vía de "APIs
externas" para enriquecer el catálogo (ver §5 punto 10). Probado en
vivo: crear cuenta, favoritear un personaje y un monstruo, ver
`/favoritos` agrupado por tipo, ver `/estadisticas` con conteos e
historial reales mezclando tipos.

Cerradas en la sesión del 2026-09-02 (perfil y branding): perfil de
usuario completo (edición de datos, cambio de clave, "juegos jugados"),
recuperación de clave por correo (§5 punto 12), trailers de Steam en
la ficha de juego, y logo real en el header. La intro animada con
video que se probó junto con el logo se implementó y luego se
descartó (ver §6) tras evaluar el trade-off para la defensa.

Cerradas en la sesión del 2026-09-05 (seguridad, desafío final,
contenido): auditoría de seguridad completa contra producción con
`curl` — encontrado y corregido el login sin límite de intentos (§5
punto 13) y las cabeceras de seguridad faltantes (§5 punto 14); API
de Proyectos ajustada a los códigos HTTP exactos de un desafío de
evaluación (§5 punto 15); 15 guías largas nuevas (17 en total); arte
real para 38 de los 90 personajes de Dragon Ball (ver actualización en
el punto anterior y detalle en §6). Todo verificado en producción tras
cada cambio, no solo en local.

En orden de prioridad sugerido para lo que sigue:

1. **Envío real de correos**: "¿Olvidaste tu clave?" hoy solo registra
   el correo en el log (`MAIL_MAILER=log`), no lo envía. Hay un driver
   de Resend ya configurado en `config/services.php` — falta cargarle
   una API key real (local y en Railway) para que el flujo de
   recuperación de clave funcione de punta a punta en producción.
2. **Seguridad Supabase — pulir políticas RLS**: hoy las tablas de
   contenido tienen política de solo-lectura pública y las internas
   (incluidas `favoritos`/`historial`) están completamente bloqueadas
   desde la API REST (PostgREST) — solo Laravel las usa, vía el rol
   `postgres`. Si en el futuro se necesita acceso desde PostgREST
   (p. ej. una app móvil que hable directo con Supabase), hay que
   escribir esa política específica entonces, no abrir todo de golpe.
3. **Más imágenes de Dragon Ball** (opcional, bajo demanda): quedan 52
   de 90 personajes sin arte real porque `dragonball-api.com` no tenía
   una forma equivalente confiable (ver §6). Si se encuentra otra
   fuente con más cobertura, aplicar el mismo criterio estricto
   (descartar fan-art y coincidencias forzadas) antes de usarla.
4. **Enriquecer el catálogo de juegos con Steam** (opcional, bajo
   demanda): el comando `steam:importar-juegos` ya funciona, pero no se
   corrió en bulk sobre los 48 juegos existentes porque podría
   sobreescribir descripciones y franquicias ya curadas a mano. Si se
   quiere usarlo para juegos que faltan en el catálogo, correrlo con
   `--buscar` apuntando a juegos puntuales y revisar el resultado antes
   de darlo por bueno.
5. **Seguridad avanzada adicional** (opcional): lo cubierto en la
   sesión del 2026-09-05 (rate limiting + cabeceras) atiende los
   hallazgos más críticos encontrados probando la app en vivo. Cosas
   como 2FA, rotación de `JWT_SECRET`, o un WAF delante de Railway
   quedan fuera de alcance salvo que se pidan explícitamente.

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
- **Todo cambio va por rama → PR → merge → redeploy en Railway**, nunca
  commit directo a `main` sin avisar (ver flujo completo en §2). Pedir
  confirmación antes de mergear un PR y antes de forzar el redeploy.

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
