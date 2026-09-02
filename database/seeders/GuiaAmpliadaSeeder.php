<?php

namespace Database\Seeders;

use App\Models\Guia;
use App\Models\Juego;
use Illuminate\Database\Seeder;

/**
 * Segunda tanda de guías, más largas y completas que las de
 * GuiaSeeder. Se mantiene en un archivo aparte para no reescribir
 * las dos guías originales de Teostra y poder hacer crecer el
 * contenido por franquicia sin que el seeder se vuelva enorme.
 */
class GuiaAmpliadaSeeder extends Seeder
{
    public function run(): void
    {
        $juegos = [
            'mhw' => Juego::where('nombre', 'Monster Hunter: World')->first(),
            'bt3' => Juego::where('nombre', 'Dragon Ball Z: Budokai Tenkaichi 3')->first(),
            'elden' => Juego::where('nombre', 'Elden Ring')->first(),
            'souls' => Juego::where('nombre', 'DARK SOULS™: REMASTERED')->first(),
            'zelda' => Juego::where('nombre', 'The Legend of Zelda: Breath of the Wild')->first(),
        ];

        foreach ($juegos as $clave => $juego) {
            if (!$juego) {
                $this->command->error("No se encontró el juego para la clave '{$clave}', se omiten sus guías.");
            }
        }

        $guias = [];

        if ($juegos['mhw']) {
            $guias = array_merge($guias, $this->guiasMonsterHunterWorld($juegos['mhw']->id));
        }

        if ($juegos['bt3']) {
            $guias = array_merge($guias, $this->guiasBudokaiTenkaichi3($juegos['bt3']->id));
        }

        if ($juegos['elden']) {
            $guias = array_merge($guias, $this->guiasEldenRing($juegos['elden']->id));
        }

        if ($juegos['souls']) {
            $guias = array_merge($guias, $this->guiasDarkSouls($juegos['souls']->id));
        }

        if ($juegos['zelda']) {
            $guias = array_merge($guias, $this->guiasZeldaBotw($juegos['zelda']->id));
        }

        foreach ($guias as $guia) {
            Guia::updateOrCreate(['slug' => $guia['slug']], $guia);
        }

        $this->command->info(count($guias) . ' guías ampliadas agregadas o actualizadas.');
    }

    private function guiasMonsterHunterWorld(int $juegoId): array
    {
        return [
            [
                'juego_id' => $juegoId,
                'titulo' => 'Guía para principiantes: primeras horas en Monster Hunter World',
                'slug' => 'guia-principiantes-monster-hunter-world',
                'tipo' => 'Consejo',
                'descripcion' => 'Qué hacer en tus primeras misiones, cómo funciona el equipo, y los errores más comunes que cometen los cazadores nuevos.',
                'donde_conseguir' => 'No aplica: esta guía cubre el arranque del juego, desde que llegas al Nuevo Mundo hasta que terminas las misiones de rango bajo.',
                'pasos' => "1. Completa las misiones de la historia en orden; desbloquean zonas y sistemas nuevos (Centro de Investigación, forja, entrega de misiones opcionales).\n2. Antes de cada misión revisa el Centro de Aprovisionamiento: lleva siempre botiquines, antídotos, cebo para trampa y bombas si vas a cazar un monstruo grande.\n3. Usa el Escáner de Rastros: caminar sobre huellas, arañazos y restos aumenta el 'nivel de investigación' del monstruo y activa el modo sigiloso, que te acerca sin alertarlo.\n4. No ignores a los Pequeños Monstruos ni la recolección: muchos materiales de armas tempranas salen de minado, tala y capturas, no solo de cazar.\n5. Sube de arma probando el modo de entrenamiento en el Centro de Reunión antes de comprometerte a un árbol de armas.\n6. Mejora tu equipo de campamento base (silbato, garra de gancho) apenas puedas: facilitan mucho la movilidad.\n7. Une misiones de investigación de bajo riesgo a tu misión principal para acumular recompensas extra sin gastar tiempo aparte.",
                'requisitos' => 'Ninguno, aplica desde el inicio del juego.',
                'consejos' => 'La armadura defensiva de rango bajo se queda obsoleta rápido; no te encariñes con un set, actualízalo cada 2-3 monstruos nuevos que caces. Comer en el Cantinero antes de cada misión da bonificaciones temporales de vida y resistencia que marcan la diferencia en peleas largas.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Fácil',
                'palabras_clave' => 'monster hunter world, guia principiantes, como empezar, consejos iniciales, equipo basico',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo derrotar a Rathalos, el Rey de los Cielos',
                'slug' => 'como-derrotar-a-rathalos',
                'tipo' => 'Jefe',
                'descripcion' => 'Rathalos es el wyvern volador emblemático de la serie: vuela en círculos, escupe fuego y ataca en picado con las garras envenenadas.',
                'donde_conseguir' => 'Aparece en misiones de la historia y, más adelante, en investigaciones en el Bosque Ancestral y las Tierras Devastadas.',
                'pasos' => "1. Prioriza romper las alas: reduce su tiempo en el aire y facilita que se quede en el suelo.\n2. Cuando esté posado, ataca la cabeza para acumular aturdimiento y evitar que despegue de nuevo.\n3. Mantén distancia lateral (nunca de frente) cuando prepare la bola de fuego: el impacto directo hace mucho daño y puede envenenar el área.\n4. Cuidado con el vuelo en picado: retrocede en diagonal en vez de correr en línea recta, es más fácil de esquivar.\n5. Lleva antídoto o comida con resistencia al veneno: sus garras envenenan en varios golpes.\n6. Si aparece Rathian (su contraparte hembra) en la misma zona, sepáralos con bombas o cebo: pelear contra los dos a la vez es mucho más difícil.",
                'requisitos' => 'Ninguno especial, aparece desde el rango bajo/medio de la historia.',
                'consejos' => 'Las armas de trueno o dragón suelen rendir bien porque no tiene gran resistencia a ninguno de los dos. Cazar a Rathalos primero facilita mucho las misiones posteriores donde aparece junto a Rathian.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Media',
                'palabras_clave' => 'rathalos, monster hunter world, como derrotar rathalos, wyvern volador, jefe',
                'imagen' => null,
                'destacada' => false,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo derrotar a Nergigante sin morir en el intento',
                'slug' => 'como-derrotar-a-nergigante',
                'tipo' => 'Jefe',
                'descripcion' => 'Nergigante es uno de los combates más exigentes del juego base: regenera púas constantemente y castiga los errores con combos letales.',
                'donde_conseguir' => 'Se desbloquea al avanzar en la historia principal (misión de rango alto) y luego reaparece en investigaciones de las Tierras Devastadas.',
                'pasos' => "1. Rompe las púas del lomo apenas puedas: mientras están rotas hace bastante menos daño en sus embestidas.\n2. No te quedes pegado a él tras un combo largo: suele encadenar un contraataque en cuanto termina de atacar.\n3. Cuando empiece a brillar y a formar púas nuevas en el aire, aléjate: viene un aullido que las dispara en área.\n4. Usa flashbang cuando esté volando bajo para derribarlo y ganar una ventana grande de daño.\n5. Lleva cuernos de resistencia o comida que aumente la vida máxima: sus combos pueden quitar más de la mitad de la barra de un tirón si no esquivas bien.\n6. Repite el ciclo de romper púas -> atacar cabeza -> retirarte antes del aullido hasta derribarlo.",
                'requisitos' => 'Haber avanzado hasta la misión de historia donde aparece por primera vez (rango alto).',
                'consejos' => 'Es de los pocos monstruos donde vale más la pena jugar defensivo que agresivo: perder la paciencia y quedarse a meter un ataque extra es la causa más común de carreteo (fallar la misión).',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Difícil',
                'palabras_clave' => 'nergigante, monster hunter world, como derrotar nergigante, jefe dificil, boss endgame',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo derrotar a Diablos en el Bosque Ancestral',
                'slug' => 'como-derrotar-a-diablos',
                'tipo' => 'Jefe',
                'descripcion' => 'Diablos es un wyvern terrestre territorial que embiste en línea recta y se entierra para atacar desde abajo.',
                'donde_conseguir' => 'Se encuentra principalmente en las Tierras Devastadas y, en misiones de historia, en el Bosque Ancestral.',
                'pasos' => "1. Rompe los cuernos: además de dar el material más valioso, reduce el daño de sus embestidas y cabezazos.\n2. Cuando empiece a temblar el suelo y desaparezca, está a punto de enterrarse: prepárate a esquivar en cuanto veas el rastro de tierra acercándose.\n3. Ataca la cola cuando esté enterrado emergiendo o después de una embestida fallida, es el momento más seguro para posicionarte.\n4. No te quedes justo enfrente en línea recta salvo que vayas a esquivar: las embestidas tienen mucho alcance.\n5. Usa trampas de foso o de acero combinadas con bombas cuando esté cansado (empieza a babear) para inmovilizarlo y meter daño garantizado.",
                'requisitos' => 'Ninguno especial.',
                'consejos' => 'Si peleas contra dos Diablos a la vez (misión de rango alto avanzada), sepáralos cuanto antes: perseguir a uno mientras el otro te embiste por sorpresa es la muerte más común en esta pelea.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Media',
                'palabras_clave' => 'diablos, monster hunter world, como derrotar diablos, wyvern terrestre, cuernos diablos',
                'imagen' => null,
                'destacada' => false,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo derrotar a Odogaron, el depredador veloz',
                'slug' => 'como-derrotar-a-odogaron',
                'tipo' => 'Jefe',
                'descripcion' => 'Odogaron es de los monstruos más rápidos y agresivos del juego base: encadena zarpazos que provocan sangrado y apenas da respiro entre ataques.',
                'donde_conseguir' => 'Habita principalmente en el Bosque Putrefacto, aunque también aparece en investigaciones de otras zonas.',
                'pasos' => "1. Evita quedarte parado esquivando en el sitio: su velocidad de ataque castiga la esquiva tardía, así que muévete de posición constantemente.\n2. Cuando te provoque sangrado (icono de gota roja), usa una ración de carne asada o similar para curarlo: mientras sangras, correr o rodar quita vida poco a poco.\n3. Ataca las patas delanteras para romperlas y reducir su movilidad; es más fácil que apuntar a la cabeza por lo errático de sus movimientos.\n4. No lo pelees en zonas estrechas con salientes: usa sus saltos entre paredes para meter golpes cuando aterriza.\n5. Lleva un arma con buena movilidad (espada larga, doble espada o arco) si no tienes experiencia esquivando sus combos de zarpazos encadenados.",
                'requisitos' => 'Ninguno especial.',
                'consejos' => 'Su patrón es más sobre timing que sobre memorizar ataques concretos: jugar un poco más pasivo al principio para aprender el ritmo compensa más que intentar ser agresivo desde el primer minuto.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Media',
                'palabras_clave' => 'odogaron, monster hunter world, como derrotar odogaron, sangrado, monstruo rapido',
                'imagen' => null,
                'destacada' => false,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo sobrevivir a Vaal Hazak y su efluvio',
                'slug' => 'como-derrotar-a-vaal-hazak',
                'tipo' => 'Jefe',
                'descripcion' => 'Vaal Hazak es un dragón anciano que envuelve la zona en una nube de efluvio tóxico que reduce la curación y drena vida constantemente.',
                'donde_conseguir' => 'Habita en el Santuario Putrefacto, tras completar las misiones de historia que lo desbloquean.',
                'pasos' => "1. Lleva o fabrica equipo con resistencia al efluvio (talismanes/decoraciones de purificación) apenas te enfrentes a él por primera vez: sin eso, tus objetos curativos hacen mucho menos efecto dentro de la niebla.\n2. Rompe la cabeza para reducir el alcance de su mordida de efluvio, uno de sus ataques más peligrosos.\n3. Busca las zonas despejadas del mapa (sin niebla morada) para curarte con eficacia completa cuando la pelea se complique.\n4. Cuando levante vuelo y empiece a generar una nube densa alrededor suyo, aléjate: viene una explosión de efluvio en área.\n5. Si tu curación se ve muy reducida, retírate a una zona limpia en vez de insistir en curarte dentro de la niebla; perder tiempo ahí suele costar más que retroceder.",
                'requisitos' => 'Haber avanzado en la historia hasta desbloquear el Santuario Putrefacto.',
                'consejos' => 'Los insectos vigoréanos (recolectables que dan un buff de curación/resistencia) son muy útiles en esta pelea porque contrarrestan parte del efecto del efluvio.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Difícil',
                'palabras_clave' => 'vaal hazak, monster hunter world, efluvio, como derrotar vaal hazak, dragon anciano',
                'imagen' => null,
                'destacada' => false,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo derrotar a Xeno\'jiiva, el jefe final',
                'slug' => 'como-derrotar-a-xenojiiva',
                'tipo' => 'Jefe',
                'descripcion' => 'Xeno\'jiiva es el jefe final de la historia principal: una pelea en dos fases dentro del Núcleo del Mundo, con ataques de energía en área muy amplios.',
                'donde_conseguir' => 'Es la misión final de la historia principal, tras seguir a Zorah Magdaros hasta el Núcleo del Mundo.',
                'pasos' => "1. En la primera fase, prioriza esquivar sus rayos de energía en vez de intentar meter daño constante: el mapa es amplio y da espacio de sobra para moverse.\n2. Cuando abra la boca y empiece a cargar un ataque de aliento, corre perpendicular a él, no en línea recta hacia atrás.\n3. Al pasar a la segunda fase (cambia de forma y se vuelve más agresivo), sigue el mismo patrón de prioridad: esquivar primero, atacar en las ventanas después de sus combos largos.\n4. Usa munición o ataques a distancia si tu arma lo permite durante los momentos en que está lejos preparando un ataque de área, para no perder tiempo de daño.\n5. Lleva el máximo de objetos curativos que puedas cargar: es una pelea larga y no hay zona de descanso segura como en otras misiones.",
                'requisitos' => 'Haber completado todas las misiones de historia previas del juego base.',
                'consejos' => 'No hace falta un equipo de gama alta especial: cualquier set de rango alto bien mejorado, con buena defensa general, es suficiente si te enfocas en esquivar más que en ser agresivo.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Difícil',
                'palabras_clave' => 'xenojiiva, monster hunter world, jefe final, como derrotar xenojiiva, nucleo del mundo',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
        ];
    }

    private function guiasBudokaiTenkaichi3(int $juegoId): array
    {
        return [
            [
                'juego_id' => $juegoId,
                'titulo' => 'Guía de combate: cómo dominar el sistema de Ki y el teletransporte',
                'slug' => 'guia-combate-ki-teletransporte-budokai-tenkaichi-3',
                'tipo' => 'Consejo',
                'descripcion' => 'El corazón del combate en Budokai Tenkaichi 3 es la gestión del Ki: sin entender cuándo cargarlo, gastarlo o guardarlo, es fácil quedar indefenso ante un rival experimentado.',
                'donde_conseguir' => 'No aplica: es un sistema disponible desde el primer combate del juego.',
                'pasos' => "1. Mantén pulsado el botón de Ki para cargarlo cuando estés a distancia seguros del rival; cargar de cerca te deja expuesto a un combo.\n2. Usa el teletransporte (esquiva + dirección) justo antes de recibir un golpe cargado: consume Ki pero te reposiciona detrás o al lado del rival.\n3. No abuses del teletransporte con la barra de Ki baja: si te quedas sin Ki en pleno combate pierdes movilidad y defensa por varios segundos.\n4. Aprende a interrumpir combos rivales con un Ki Blast rápido cuando te embisten desde lejos, en vez de intentar bloquear siempre.\n5. Guarda Ki para el Contraataque de Teletransporte (Instant Transmission) que se activa cuando el rival te ataca justo cuando te tele-transportas encima suyo: es de las herramientas más fuertes del juego una vez que le agarras el timing.\n6. Combina Rush (ataques cuerpo a cuerpo básicos) con Ki Blasts sueltos para forzar al rival a bloquear y abrir huecos para un combo mayor.",
                'requisitos' => 'Ninguno, aplica a cualquier personaje y modo de juego.',
                'consejos' => 'Contra la IA en dificultades altas, el teletransporte defensivo (justo antes del impacto) es más confiable que intentar esquivar corriendo: la IA reacciona muy rápido a los cambios de dirección en el suelo.',
                'plataformas' => 'PlayStation 2 y Wii',
                'dificultad' => 'Media',
                'palabras_clave' => 'budokai tenkaichi 3, ki, teletransporte, combos, sistema de combate, dragon ball z',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Guía de las transformaciones de Goku, de Super Saiyan a Super Saiyan 4',
                'slug' => 'guia-transformaciones-goku-budokai-tenkaichi-3',
                'tipo' => 'Personaje',
                'descripcion' => 'Goku es el personaje con más transformaciones jugables del roster: cada una cambia su velocidad, potencia de Ki y técnicas disponibles.',
                'donde_conseguir' => 'Todas las formas de Goku son personajes seleccionables independientes en el roster (Goku, Goku SSJ, Goku SSJ2, Goku SSJ3, Goku SSJ4), no una transformación en combate.',
                'pasos' => "1. Goku base es más lento pero tiene un Ki más manejable para jugadores nuevos: úsalo para practicar el sistema de combos sin gastar Ki tan rápido.\n2. Goku Super Saiyan gana velocidad y potencia de ataque a costa de vaciar el Ki más rápido en Rush prolongados.\n3. Goku Super Saiyan 2 aumenta el daño de sus Blasts especiales; es una buena opción intermedia para el Kamehameha cargado.\n4. Goku Super Saiyan 3 sacrifica algo de movilidad por un daño mucho mayor: mejor en combates uno contra uno donde puedas jugar más paciente.\n5. Goku Super Saiyan 4 (versión de Dragon Ball GT) tiene el kit más agresivo de todos, pensado para rush constante y técnicas Ultimate de alto costo de Ki.\n6. Practica cada forma en el Modo Entrenamiento antes de llevarla al Modo Historia o Ultimate Battle: cada una tiene combos finales distintos.",
                'requisitos' => 'Algunas formas (como SSJ3 y SSJ4) se desbloquean avanzando en el Modo Dragon Universe o Ultimate Battle según la ruta de personaje elegida.',
                'consejos' => 'Si vas a jugar contra otra persona, elegir la forma según el rival importa: contra un personaje muy veloz, SSJ2 suele dar mejor equilibrio entre velocidad y daño que SSJ3.',
                'plataformas' => 'PlayStation 2 y Wii',
                'dificultad' => 'Media',
                'palabras_clave' => 'goku, super saiyan, ssj4, budokai tenkaichi 3, transformaciones goku, dragon ball z',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo avanzar en el Modo Dragon Universe sin frustrarte',
                'slug' => 'guia-modo-dragon-universe-budokai-tenkaichi-3',
                'tipo' => 'Consejo',
                'descripcion' => 'Dragon Universe es el modo historia del juego: recorre los arcos principales de Dragon Ball Z (y algo de GT) combate por combate, con condiciones especiales en algunas batallas.',
                'donde_conseguir' => 'Disponible desde el menú principal del juego, dividido por sagas.',
                'pasos' => "1. Elige primero la saga Saiyan si es tu primera vez: tiene los combates más simples y sirve para aprender el ritmo del modo.\n2. Lee siempre las condiciones especiales antes de cada batalla (algunas piden derrotar al rival en un tiempo límite o sin usar cierta técnica): ignorarlas es la causa más común de perder combates que deberían ser fáciles.\n3. Sube de nivel a tus personajes jugando batallas de práctica o repitiendo capítulos ya completados si un combate posterior se siente muy difícil.\n4. Usa los objetos y Z Items que se desbloquean por saga para reforzar defensa o Ki antes de los jefes de cada arco (Freezer, Cell, Buu).\n5. Guarda el progreso al terminar cada saga, no solo al final del modo: es largo y dividir el avance evita perder horas de progreso.",
                'requisitos' => 'Ninguno, disponible desde el inicio.',
                'consejos' => 'Las batallas contra jefes de saga (Freezer en Namek, Cell en los Juegos de Cell, Buu en la saga final) suelen ser más fáciles si subes antes el nivel del personaje principal de esa saga en Ultimate Battle.',
                'plataformas' => 'PlayStation 2 y Wii',
                'dificultad' => 'Media',
                'palabras_clave' => 'dragon universe, budokai tenkaichi 3, modo historia, sagas dragon ball z, como avanzar',
                'imagen' => null,
                'destacada' => false,
                'publicada' => true,
            ],
        ];
    }

    private function guiasEldenRing(int $juegoId): array
    {
        return [
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo derrotar a Margit, el Presagio Desdichado',
                'slug' => 'como-derrotar-a-margit-elden-ring',
                'tipo' => 'Jefe',
                'descripcion' => 'Margit es el primer gran filtro de dificultad de Elden Ring: un jefe rápido con un combo de martillo devastador y un ataque de cuchillas voladoras a distancia.',
                'donde_conseguir' => 'Se encuentra en la entrada al Castillo Tibia Estormont, en Limgrave, bloqueando el camino hacia la primera Legendaria del juego.',
                'pasos' => "1. Si te cuesta la pelea, no fuerces el combate a nivel bajo: explorar Limgrave completo antes de enfrentarlo sube tu nivel y equipo de forma natural.\n2. Usa un objeto de invocación de espíritu (como los Lobos Espectrales, obtenibles temprano) para dividir su atención; reduce muchísimo la presión.\n3. Aprende a distinguir su combo de martillo de dos golpes: el segundo golpe llega con más retraso del que parece, no esquives los dos al mismo tiempo.\n4. Cuando lance las cuchillas voladoras en abanico, corre hacia un lado en vez de hacia atrás: el patrón cubre más terreno en línea recta que a los costados.\n5. Aprovecha la ventana después de su salto con martillo (cuando aterriza) para meter dos o tres golpes seguros.\n6. Si tienes un talismán o objeto que reduzca el daño físico, úsalo: casi todo su kit es daño físico puro.",
                'requisitos' => 'Ninguno obligatorio, aunque se recomienda explorar Limgrave antes de intentarlo.',
                'consejos' => 'Margit vuelve a aparecer más adelante como Morgott en el Castillo Tumba de Leyndell, con el mismo moveset base más ataques nuevos: dominar esta pelea temprano ayuda directamente más adelante.',
                'plataformas' => 'PlayStation 4, PlayStation 5, Xbox One, Xbox Series X/S y PC',
                'dificultad' => 'Difícil',
                'palabras_clave' => 'margit, elden ring, como derrotar margit, presagio desdichado, jefe limgrave',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Guía de builds iniciales: qué clase elegir en Elden Ring',
                'slug' => 'guia-builds-iniciales-elden-ring',
                'tipo' => 'Consejo',
                'descripcion' => 'La clase inicial solo define tu punto de partida (estadísticas y equipo), no te encierra en un estilo de juego para siempre, pero elegir bien ahorra tiempo redistribuyendo puntos.',
                'donde_conseguir' => 'La elección de clase ocurre en la creación de personaje, antes de entrar al mundo.',
                'pasos' => "1. Si nunca jugaste un Souls, elige Guerrero, Héroe o Bandido: tienen buen equilibrio entre vida, daño físico y son fáciles de entender sin gestionar maná/FP.\n2. Si te interesa la magia desde el principio, elige Astrólogo (hechizos) o Profeta (invocaciones/fe) en vez de subir esas estadísticas desde cero con otra clase.\n3. Vagabundo es la opción más equilibrada de estadísticas si no sabes qué build quieres jugar todavía.\n4. Evita repartir puntos en demasiadas estadísticas distintas al principio: concentra la subida en Vigor (vida) y una o dos estadísticas de daño según tu arma.\n5. Prueba el arma inicial de tu clase en los primeros enemigos de Limgrave; si no te convence, la Herrería y los vendedores tempranos ya ofrecen alternativas.",
                'requisitos' => 'Ninguno.',
                'consejos' => 'Subir Vigor (vida) de forma constante en las primeras 20-30 horas es más importante que perseguir un build \"óptimo\": sobrevivir un golpe extra evita más muertes que un poco más de daño.',
                'plataformas' => 'PlayStation 4, PlayStation 5, Xbox One, Xbox Series X/S y PC',
                'dificultad' => 'Fácil',
                'palabras_clave' => 'elden ring, clases, builds iniciales, que clase elegir, guia principiantes',
                'imagen' => null,
                'destacada' => false,
                'publicada' => true,
            ],
        ];
    }

    private function guiasDarkSouls(int $juegoId): array
    {
        return [
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo derrotar a Ornstein y Smough',
                'slug' => 'como-derrotar-a-ornstein-y-smough',
                'tipo' => 'Jefe',
                'descripcion' => 'El combate doble en Anor Londo es uno de los más recordados de la saga: dos jefes con estilos opuestos que se refuerzan mutuamente si uno muere primero.',
                'donde_conseguir' => 'Se accede tras cruzar Anor Londo, en la sala del trono al final de la zona.',
                'pasos' => "1. Decide desde el principio a cuál enfocar primero: Ornstein (rápido, con lanza y relámpagos) es más seguro de eliminar primero para jugadores nuevos; Smough (lento, más daño por golpe) es más arriesgado pero la pelea termina antes si le va bien a un jugador experimentado.\n2. Si Ornstein muere primero, Smough absorbe su relámpago y se vuelve más grande y letal: prepárate para una segunda fase más agresiva.\n3. Si Smough muere primero, Ornstein absorbe su tamaño y se vuelve un jefe único gigante con ambos estilos combinados.\n4. Usa los pilares de la sala para romper línea de visión y separar a los dos jefes cuando ataquen juntos.\n5. Contra Ornstein, mantente a su costado o espalda: sus ataques de lanza y salto con relámpago son frontales.\n6. Contra Smough, prioriza la esquiva lateral: su martillo tiene un alcance amplio pero es lento de recuperar tras un golpe fallado.",
                'requisitos' => 'Haber llegado a Anor Londo siguiendo la ruta principal de la historia.',
                'consejos' => 'Invocar un fantasma aliado (si tienes humanidad) ayuda mucho en esta pelea específicamente porque divide la atención de los dos jefes al mismo tiempo, algo que en solitario es casi imposible de lograr.',
                'plataformas' => 'PlayStation 3, Xbox 360, PC, PlayStation 4, Xbox One y Nintendo Switch (Remastered)',
                'dificultad' => 'Difícil',
                'palabras_clave' => 'ornstein y smough, dark souls, como derrotar ornstein smough, anor londo, jefe doble',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
        ];
    }

    private function guiasZeldaBotw(int $juegoId): array
    {
        return [
            [
                'juego_id' => $juegoId,
                'titulo' => 'Guía de inicio: la Meseta del Prólogo y las primeras Torres Sheikah',
                'slug' => 'guia-meseta-prologo-breath-of-the-wild',
                'tipo' => 'Consejo',
                'descripcion' => 'La Meseta del Prólogo enseña, sin decirlo explícitamente, todas las mecánicas base del juego: escalar, cocinar, usar las runas y sobrevivir al clima.',
                'donde_conseguir' => 'Es la zona inicial obligatoria del juego, tras despertar en el Santuario de la Resurrección.',
                'pasos' => "1. Visita los cuatro Santuarios de la Meseta (练, Owa Daim, Ja Baij y Keh Namut) en el orden que prefieras: cada uno enseña una runa distinta (Bomba, Magnesis, Estasis, Criosíntesis).\n2. Cocina antes de explorar zonas altas o frías: una comida simple con pimiento picante o setas heladas evita el daño ambiental sin gastar objetos de resistencia.\n3. Usa Estasis en rocas o troncos para crear rampas improvisadas y llegar a cofres en zonas altas antes de tener mucha resistencia.\n4. No abandones la Meseta hasta activar la Torre Sheikah central: revela el mapa de toda la región y facilita mucho la navegación después.\n5. Recoge la Paravela apenas termines los cuatro Santuarios: es obligatoria para salir de la Meseta y cambia por completo la exploración del resto del mapa.\n6. Guarda armas duplicadas de buena calidad antes de salir: fuera de la Meseta el equipo inicial se rompe rápido si no llevas repuestos.",
                'requisitos' => 'Ninguno, es la zona tutorial obligatoria.',
                'consejos' => 'No hay prisa: la Meseta no tiene límite de tiempo y completarla al 100% (los 4 santuarios, cofres y el altar final) deja a Link mucho mejor preparado que salir apenas se pueda.',
                'plataformas' => 'Nintendo Switch y Wii U',
                'dificultad' => 'Fácil',
                'palabras_clave' => 'breath of the wild, meseta del prologo, guia inicial, santuarios, runas sheikah, zelda botw',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
            [
                'juego_id' => $juegoId,
                'titulo' => 'Cómo conseguir la Espada Maestra',
                'slug' => 'como-conseguir-la-espada-maestra-botw',
                'tipo' => 'Objeto',
                'descripcion' => 'La Espada Maestra es el arma más icónica del juego: no se compra ni se fabrica, hay que demostrar ser digno de portarla frente al Bosque Kolog.',
                'donde_conseguir' => 'En el Bosque Kolog, en el corazón del Bosque Perdido al noroeste del mapa, cerca de la Aldea Kakariko.',
                'pasos' => "1. Llega al Bosque Perdido y sigue las antorchas encendidas para no perderte en la niebla: alejarte del camino marcado te devuelve al inicio.\n2. Al llegar al Bosque Kolog, guarda todas tus armas equipadas antes de acercarte al pedestal: si llevas un arma en mano no podrás sujetar la espada.\n3. Sujeta la empuñadura y mantenla firme: la prueba consiste en resistir varios segundos de resistencia invisible.\n4. Si no tienes suficiente vida (menos de un total de 13 corazones aproximadamente), la espada no se dejará sacar todavía: sube vida con Contenedores de Corazón de Santuarios antes de volver a intentarlo.\n5. Una vez conseguida, recuerda que se recarga sola tras un tiempo si se queda sin energía en combate; no se rompe como el resto de las armas.",
                'requisitos' => 'Tener una cantidad suficiente de corazones (aproximadamente 13 contenedores de vida) para poder sacarla del pedestal.',
                'consejos' => 'No hace falta completar santuarios de combate para conseguirla, cualquier santuario que dé Contenedor de Corazón sirve; la parte más lenta suele ser juntar la vida necesaria, no encontrar el bosque.',
                'plataformas' => 'Nintendo Switch y Wii U',
                'dificultad' => 'Media',
                'palabras_clave' => 'espada maestra, breath of the wild, como conseguir espada maestra, bosque kolog, zelda botw',
                'imagen' => null,
                'destacada' => false,
                'publicada' => true,
            ],
        ];
    }
}
