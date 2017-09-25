<?php

$config = [
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vk' => [
                    // https://vk.com/apps?act=manage callbackUrl=''
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'google' => [
                    // https://console.developers.google.com/project callbackUrl=/auth/network/auth?authclient=google
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'facebook' => [
                    // https://developers.facebook.com/ callbackUrl=/auth/network/auth
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'yandex' => [
                    // https://oauth.yandex.ru/client/new callbackUrl=/auth/network/auth?authclient=yandex
                    'class' => 'yii\authclient\clients\Yandex',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                // https://apps.twitter.com/ (https://dev.twitter.com/)  callbackUrl=/auth/network/auth
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'attributeParams' => [
                        'include_email' => 'true'
                    ],
                    'consumerKey' => '',
                    'consumerSecret' => '',
                ],
            ],
        ]
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
