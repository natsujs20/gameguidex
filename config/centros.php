<?php

/*
|--------------------------------------------------------------------------
| Centros de Información
|--------------------------------------------------------------------------
|
| Registro único de los Centros de Información de GameGuideX. Antes cada
| Centro estaba escrito a mano en tres sitios distintos (portada, página
| de guías y sidebar), con maquetados diferentes. Ahora se declara aquí
| una sola vez y todas las vistas lo leen desde App\Services\CentrosInformacion.
|
| PARA AÑADIR UN CENTRO NUEVO basta con añadir una entrada a este archivo.
| No hay que tocar controladores ni vistas.
|
| Cada Centro puede exponer CATEGORÍAS DISTINTAS: Monster Hunter tiene
| monstruos y materiales, mientras que Dragon Ball tiene personajes. No
| se obliga a todos los Centros a compartir el mismo esquema.
|
| Campos de un Centro:
|   slug        Identificador interno.
|   nombre      Nombre visible.
|   franquicia  Debe coincidir con juegos.franquicia. Se usa para contar
|               el contenido real y para elegir la imagen de portada.
|   descripcion Texto corto para la tarjeta.
|   ruta        Nombre de ruta Laravel del Centro (null si aún no existe).
|   disponible  false = se muestra como "Próximamente", sin enlace.
|   categorias  Secciones del Centro. Ver abajo.
|
| Campos de una categoría:
|   recurso  Tipo de contenido a contar. Los valores admitidos están en
|            App\Services\CentrosInformacion::RECURSOS. Si es null, la
|            categoría se muestra sin contador (aún sin datos).
|   nombre   Nombre visible.
|   ruta     Ruta Laravel de la categoría (null si aún no existe).
|
*/

return [

    'centros' => [

        [
            'slug' => 'monster-hunter',
            'nombre' => 'Monster Hunter',
            'franquicia' => 'Monster Hunter',
            'descripcion' => 'Monstruos, debilidades, materiales y estrategias de caza.',
            'ruta' => 'guias.monster-hunter',
            'disponible' => true,
            'categorias' => [
                [
                    'recurso' => 'monstruos',
                    'nombre' => 'Monstruos',
                    'ruta' => 'monstruos.index',
                ],
                [
                    'recurso' => 'materiales',
                    'nombre' => 'Materiales',
                    'ruta' => null,
                ],
                [
                    'recurso' => 'guias',
                    'nombre' => 'Guías',
                    'ruta' => 'guias.index',
                ],
            ],
        ],

        [
            'slug' => 'dragon-ball',
            'nombre' => 'Dragon Ball',
            'franquicia' => 'Dragon Ball',
            'descripcion' => 'Personajes, transformaciones y técnicas de Budokai Tenkaichi 3.',
            'ruta' => 'guias.dragon-ball',
            'disponible' => true,
            'categorias' => [
                [
                    'recurso' => 'personajes',
                    'nombre' => 'Personajes',
                    'ruta' => 'dragon-ball.personajes.index',
                ],
                [
                    'recurso' => 'tecnicas',
                    'nombre' => 'Técnicas',
                    'ruta' => null,
                ],
            ],
        ],

        /*
         * Centros anunciados pero todavía sin contenido. Se muestran
         * atenuados y sin enlace. No declaran categorías porque no
         * tienen datos que contar.
         */
        [
            'slug' => 'pokemon',
            'nombre' => 'Pokémon',
            'franquicia' => 'Pokémon',
            'descripcion' => 'Pokémon disponibles, evoluciones, movimientos, objetos y estrategias.',
            'ruta' => null,
            'disponible' => false,
            'categorias' => [],
        ],

        [
            'slug' => 'resident-evil',
            'nombre' => 'Resident Evil',
            'franquicia' => 'Resident Evil',
            'descripcion' => 'Soluciones de puzles, armas, tesoros, coleccionables y jefes.',
            'ruta' => null,
            'disponible' => false,
            'categorias' => [],
        ],

    ],

];
