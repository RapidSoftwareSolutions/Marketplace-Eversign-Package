<?php
return [
    'settings' => [
        'displayErrorDetails' => false,
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'apiUrl' => 'https://api.eversign.com/api/',
        'uploadServiceUrl' => 'http://104.198.149.144:8080'
    ],
];

