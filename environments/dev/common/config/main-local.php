<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            // You have to set 'useFileTransport' to false and configure a transport for the mailer to send real emails.
            'useFileTransport' => true,
            'messageConfig' => [
                'from' => ['support@example.com' => 'App Name robot']
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'username',
                'password' => 'password',
                'port' => '587', // 465
                'encryption' => 'tls', // ssl
            ],
        ],
    ],
];
