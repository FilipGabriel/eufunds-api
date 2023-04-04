<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Define which assets will be available through the asset manager
    |--------------------------------------------------------------------------
    | These assets are registered on the asset manager
    */
    'all_assets' => [
        'admin.program.css' => ['module' => 'program:admin/css/program.css'],
        'admin.program.js' => ['module' => 'program:admin/js/program.js'],
        'admin.jstree.js' => ['module' => 'program:admin/js/jstree.js'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Define which default assets will always be included in your pages
    | through the asset pipeline
    |--------------------------------------------------------------------------
    */
    'required_assets' => [],
];
