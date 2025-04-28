<?php

return [
    'id'        => 'alianza',
    'name'      => __('Alianzas'),
    'icon'      => '<i class="icon-user"></i>',
    'tab'       => 'Common',
    'fields'    => [
        [
            'id'                => 'alianzas_imagenes',
            'type'              => 'repeater',
            'label_title'       => __('Imágenes de alianzas'),
            'repeater_title'    => __('Agregar nueva alianza'),
            'multi'             => true,
            'fields'            => [
                [
                    'id'            => 'imagen',
                    'type'          => 'file',
                    'label_title'   => __('Imagen'),
                    'label_desc'    => __('Sube una imagen de la alianza'),
                    'max_size'      => 4,
                    'ext'           => ['jpg', 'jpeg', 'png', 'webp'],
                ],
                [
                    'id'            => 'titulo',
                    'type'          => 'text',
                    'label_title'   => __('Nombre de la alianza'),
                    'placeholder'   => __('Ej: Universidad X'),
                ],
                [
                    'id'            => 'enlace',
                    'type'          => 'text',
                    'label_title'   => __('Enlace del sitio'),
                    'placeholder'   => __('https://ejemplo.com'),
                ],
            ]
        ],
    ]
];
