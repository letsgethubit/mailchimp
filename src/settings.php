<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'host'  => 'localhost',
            'port'  => '3306',
            'user'  => 'mailchimp',
            'pass'  => '123',
            'dbname' => 'mailchimp'
        ],
        'email' => [
            'fromName' => 'Registro de usuarios',
            'fromEmail' => 'mail@mail.com',
            'urlTemplates' => __DIR__ . '/../templates/emails/confirm.plain',
            'subject' => 'Confirmación de email en Lista MailChimp'
        ],
        'mailchimp' => [
            'apiKey' => '',
            'idList' => ''
        ]
    ],
];
