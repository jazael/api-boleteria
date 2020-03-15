<?php
return [
    'settings' => [
        // Show Errors in Production (false)
        'displayErrorDetails' => true,
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'sis-control-boleteria',
            'path' => __DIR__ . '/../logs/app.log',
        ],
        // Secret-Key APP
        'app_token_name'   => 'APP-TOKEN',
        'connectionString' => [
            'dns'  => 'mysql:host=localhost;dbname=control_boleteria',
            'user' => 'root',
            'pass' => ''
        ],
    ],
];