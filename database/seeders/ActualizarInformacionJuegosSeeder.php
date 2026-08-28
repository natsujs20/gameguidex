<?php

namespace Database\Seeders;

use App\Models\Juego;
use Illuminate\Database\Seeder;

class ActualizarInformacionJuegosSeeder extends Seeder
{
    public function run(): void
    {
        $juegos = [

            'Digimon World' => [
                'descripcion' => 'Un joven es transportado misteriosamente a File Island, donde descubre que la antigua y próspera File City ha quedado prácticamente abandonada. Junto a su compañero Digimon deberá explorar el Mundo Digital, encontrar a sus habitantes y convencerlos de regresar. La aventura combina exploración, combates y un profundo sistema de crianza en el que alimentar, entrenar y cuidar al Digimon influye directamente en sus evoluciones.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon World 2' => [
                'descripcion' => 'El protagonista se convierte en Guard Tamer y se une a uno de los equipos encargados de proteger Digital City. A bordo del Digi-Beetle explora peligrosos dominios digitales repletos de trampas, enemigos y tesoros. A diferencia del primer juego, permite formar equipos de tres Digimon y utiliza combates por turnos con un sistema centrado en capturar, fusionar y mejorar criaturas.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon World 3' => [
                'descripcion' => 'Junior y sus amigos ingresan a Digimon Online para disfrutar de una aventura virtual, pero quedan atrapados cuando una misteriosa organización toma el control del sistema. El protagonista deberá recorrer ciudades, enfrentarse a líderes y descubrir una conspiración capaz de afectar tanto el mundo digital como el real. Incluye combates por turnos, evoluciones, cartas coleccionables y numerosas zonas para explorar.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon Rumble Arena' => [
                'descripcion' => 'Los Digimon más conocidos de las primeras temporadas se enfrentan en rápidos combates dentro de escenarios cerrados. Cada personaje posee movimientos especiales, ataques característicos y la capacidad de evolucionar temporalmente durante la pelea. Las arenas incluyen peligros y objetos que pueden cambiar el resultado de cada enfrentamiento.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon World 4' => [
                'descripcion' => 'El Mundo Digital vuelve a estar en peligro debido a una misteriosa amenaza vinculada con el servidor Yamato. Los jugadores controlan directamente a sus Digimon mientras recorren mazmorras, utilizan armas y derrotan grandes grupos de enemigos. Su aventura puede jugarse en solitario o de manera cooperativa, permitiendo desarrollar distintas habilidades y evoluciones.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon World DS' => [
                'descripcion' => 'El jugador llega al Mundo Digital y comienza su formación como domador bajo la supervisión de ClavisAngemon. Una serie de incidentes lo llevará a investigar una amenaza que pone en peligro a los Digimon y a las distintas áreas digitales. El juego permite escanear criaturas, convertirlas en compañeros, entrenarlas en granjas y desbloquear numerosas rutas evolutivas.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon World Dawn' => [
                'descripcion' => 'Un misterioso virus ataca Sunshine City y provoca que numerosos Digimon pierdan sus evoluciones. Como miembro de Light Fang, el protagonista deberá investigar el origen del desastre y recuperar el prestigio de su equipo. La aventura incluye misiones, granjas digitales, combates por turnos, fusiones y una gran variedad de Digimon de tipo luz, sagrado y bestia.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon World Dusk' => [
                'descripcion' => 'Tras el ataque que afecta a las ciudades del Mundo Digital, el protagonista se une al equipo Night Crow para descubrir quién está detrás del incidente. Esta versión presenta misiones y Digimon diferentes a Dawn, con mayor presencia de criaturas oscuras, mecánicas y demoníacas. Conserva el sistema de crianza, evolución, granjas y combates estratégicos por turnos.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Digimon Story: Cyber Sleuth' => [
                'descripcion' => 'Después de sufrir un extraño encuentro dentro del ciberespacio EDEN, el protagonista queda atrapado entre el mundo físico y el digital. Convertido en asistente de una detective, deberá investigar desapariciones, hackers y criaturas que están atravesando la barrera entre ambos mundos. Permite escanear, criar y evolucionar cientos de Digimon para formar equipos de combate.',
                'estado_disponibilidad' => 'Disponible mediante Complete Edition',
                'enlace_oficial' => 'https://store.steampowered.com/app/1042550/Digimon_Story_Cyber_Sleuth_Complete_Edition/',
                'texto_enlace' => 'Ver Complete Edition en Steam',
            ],

            "Digimon Story: Cyber Sleuth - Hacker's Memory" => [
                'descripcion' => 'Keisuke Amazawa es acusado de un crimen que no cometió después de que su identidad digital fuera robada. Para limpiar su nombre se une al grupo de hackers Hudie y comienza a investigar los sectores más peligrosos de EDEN. Su historia ocurre paralelamente a Cyber Sleuth y muestra los mismos acontecimientos desde una perspectiva mucho más cercana al mundo de los hackers.',
                'estado_disponibilidad' => 'Disponible mediante Complete Edition',
                'enlace_oficial' => 'https://store.steampowered.com/app/1042550/Digimon_Story_Cyber_Sleuth_Complete_Edition/',
                'texto_enlace' => 'Ver Complete Edition en Steam',
            ],

            'Digimon Story Cyber Sleuth: Complete Edition' => [
                'descripcion' => 'Esta edición reúne las historias completas de Cyber Sleuth y Hacker’s Memory en un solo juego. El jugador puede investigar misterios digitales como detective o experimentar el conflicto desde el lado de los hackers. Incluye combates por turnos, granjas digitales y más de 300 Digimon que pueden ser coleccionados, entrenados y evolucionados.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/1042550/Digimon_Story_Cyber_Sleuth_Complete_Edition/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Digimon World: Next Order' => [
                'descripcion' => 'El protagonista es transportado a un Mundo Digital dominado por Machinedramon fuera de control. Junto a dos compañeros Digimon deberá restaurar el orden, rescatar habitantes y reconstruir Floatia hasta convertirla nuevamente en una gran ciudad. El sistema de crianza exige alimentar, entrenar y cuidar simultáneamente a ambos compañeros mientras envejecen y evolucionan.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/1530160/Digimon_World_Next_Order/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Digimon Survive' => [
                'descripcion' => 'Takuma Momozuka y un grupo de estudiantes quedan atrapados en un mundo desconocido habitado por misteriosas criaturas. Mientras buscan una manera de regresar, deberán sobrevivir a peligros que pondrán a prueba su confianza y sus relaciones. Las decisiones del jugador modifican las evoluciones de Agumon, el destino de los personajes y el desenlace de la historia.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/871980/Digimon_Survive/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Digimon Story: Time Stranger' => [
                'descripcion' => 'Una aventura que conecta el mundo humano y el Mundo Digital mientras el protagonista investiga un misterio relacionado con el colapso de ambos mundos. La historia se desarrolla a través del tiempo y permite explorar la relación entre humanos y Digimon mientras se intenta evitar una catástrofe. Incluye exploración, crianza de criaturas y combates estratégicos por turnos.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/1984270/Digimon_Story_Time_Stranger/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Monster Hunter: World' => [
                'descripcion' => 'La Comisión de Investigación viaja al Nuevo Mundo para estudiar la migración de los Dragones Ancianos. Como cazador, el jugador explora grandes ecosistemas, rastrea monstruos y aprende el comportamiento de cada criatura antes de enfrentarse a ella. Los materiales obtenidos permiten fabricar armas y armaduras cada vez más poderosas.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/582010/Monster_Hunter_World/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Monster Hunter Rise' => [
                'descripcion' => 'La aldea Kamura se prepara para enfrentar una nueva invasión conocida como el Frenesí. El cazador deberá proteger a sus habitantes mientras descubre qué provoca el comportamiento de los monstruos. El cordóptero y los camaradas Canyne permiten moverse con mayor libertad, explorar verticalmente y ejecutar nuevas técnicas durante las cacerías.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/1446780/MONSTER_HUNTER_RISE/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Monster Hunter Wilds' => [
                'descripcion' => 'Una expedición viaja a las misteriosas Tierras Prohibidas para investigar regiones que se creían deshabitadas. Los ecosistemas cambian constantemente debido al clima, alterando el territorio y el comportamiento de las manadas. El cazador deberá adaptarse, proteger a sus habitantes y descubrir la conexión entre un joven llamado Nata y el peligroso Arkveld.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/2246340/Monster_Hunter_Wilds/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Pokémon Esmeralda' => [
                'descripcion' => 'El protagonista recorre la región de Hoenn con el objetivo de convertirse en Campeón y completar la Pokédex. Durante el viaje queda atrapado en el conflicto entre el Equipo Magma y el Equipo Aqua, cuyos planes despiertan a Groudon y Kyogre. La historia culmina con la aparición de Rayquaza y posteriormente permite explorar el desafiante Frente Batalla.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Pokémon Rojo Fuego' => [
                'descripcion' => 'Esta nueva versión de Pokémon Rojo permite regresar a Kanto para comenzar una aventura desde Pueblo Paleta. El entrenador deberá capturar Pokémon, conseguir ocho medallas y enfrentarse al Equipo Rocket antes de desafiar al Alto Mando. También incorpora las Islas Sete, nuevas criaturas y contenido adicional después de completar la historia principal.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Pokémon Escarlata' => [
                'descripcion' => 'El jugador comienza sus estudios en una academia de la región de Paldea y participa en una búsqueda del tesoro completamente libre. Puede avanzar entre el desafío de los gimnasios, la lucha contra el Team Star y la investigación de los Pokémon dominantes. Las tres rutas terminan conectándose con el misterioso Área Cero y los secretos ocultos en el centro de la región.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://www.nintendo.com/us/store/products/pokemon-scarlet-switch/',
                'texto_enlace' => 'Comprar en Nintendo',
            ],

            'Dragon Ball Z: Budokai Tenkaichi 3' => [
                'descripcion' => 'Un juego de lucha tridimensional que recorre enfrentamientos de Dragon Ball, Dragon Ball Z, GT y varias películas. Su enorme plantel permite utilizar transformaciones, fusiones y ataques característicos dentro de escenarios destructibles. Los combates buscan recrear la velocidad, escala y espectacularidad de las batallas de la serie.',
                'estado_disponibilidad' => 'Sin venta oficial',
                'enlace_oficial' => null,
                'texto_enlace' => null,
            ],

            'Dragon Ball Z: Kakarot' => [
                'descripcion' => 'El jugador revive la historia completa de Goku durante las principales sagas de Dragon Ball Z. Además de combatir contra enemigos como Freezer, Cell y Majin Buu, puede explorar amplias zonas, entrenar, pescar y completar historias secundarias. Su sistema de progresión permite mejorar personajes y revivir numerosos momentos clásicos del anime.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/851850/DRAGON_BALL_Z_KAKAROT/',
                'texto_enlace' => 'Comprar en Steam',
            ],

            'Dragon Ball: Sparking! ZERO' => [
                'descripcion' => 'La serie Budokai Tenkaichi regresa con combates tridimensionales de alta velocidad y un enorme plantel de luchadores. Los personajes pueden transformarse, ejecutar ataques devastadores y destruir partes del escenario durante la batalla. Su modo historia permite revivir enfrentamientos conocidos y tomar decisiones capaces de crear situaciones alternativas.',
                'estado_disponibilidad' => 'Disponible oficialmente',
                'enlace_oficial' => 'https://store.steampowered.com/app/1790600/DRAGON_BALL_Sparking_ZERO/',
                'texto_enlace' => 'Comprar en Steam',
            ],

        ];

        foreach ($juegos as $nombre => $datos) {
            Juego::where('nombre', $nombre)->update($datos);
        }
    }
}