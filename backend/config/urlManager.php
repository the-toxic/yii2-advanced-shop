<?php

/** @var array $params */

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['backendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:login|logout>' => 'auth/<_a>',
        '<_c:[\w\-]+>' => '<_c>/index',

        'shop/<_c:[\w\-]+>/<id:\d+>' => 'shop/<_c>/view',
        'shop/<_c:[\w\-]+>/<_a:[\w-]+>/<id:\d+>' => 'shop/<_c>/<_a>',
        'blog/<_c:[\w\-]+>/<id:\d+>' => 'blog/<_c>/view',
        'blog/<_c:[\w\-]+>/<_a:[\w-]+>/<id:\d+>' => 'blog/<_c>/<_a>',

        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>/<id:\d+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
//        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];