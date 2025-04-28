<?php

return [
    'id'        => 'guide-steps',
    'name'      => __('Guide Steps'),
    'icon'      => '<i class="icon-layers"></i>',
    'tab'       => "Common",
    'fields'    => [
        [
    'id'                => 'alianzas',
    'type'              => 'repeater',
    'label_title'       => __('Alianzas'),
    'repeater_title'    => __('Agregar Alianza'),
    'multi'             => true,
    'fields'            => [
        [
            'id'            => 'imagen',
            'type'          => 'file',
            'label_title'   => __('Imagen de la alianza'),
            'label_desc'    => __('Sube una imagen representativa'),
            'max_size'      => 4,
            'ext'           => ['jpg', 'jpeg', 'png', 'webp'],
        ],
        [
            'id'            => 'titulo',
            'type'          => 'text',
            'label_title'   => __('Nombre de la alianza'),
            'placeholder'   => __('Ej: Universidad ABC'),
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
