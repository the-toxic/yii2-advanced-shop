<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@staticRoot' => $params['staticPath'],
        '@static'   => $params['staticHostInfo'],
    ],
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
        [
            // возврат данных в формате, запрашиваемом клиентом через заголовки request
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => 'json',
                'application/xml' => 'xml',
            ],
        ],
    ],
    'modules' => [
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24,
            'storageMap' => [
                'user_credentials' => 'common\auth\Identity',
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ]
            ],
            // блок устраняет исключение при попытке запроса
            'components' => [
                'request' => function () { return \filsh\yii2\oauth2server\Request::createFromGlobals(); },
                'response' => ['class' => \filsh\yii2\oauth2server\Response::class],
            ],
        ]
    ],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'parsers' => [
                // парсинг json запросов в нормальный вид массивов (для корректной работы $_POST)
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'formatters' => [
                'json' => [
                    // отключить перекодирование юникод символов (например русских) в \x250\x543
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\auth\Identity',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',
                'profile' => 'user/profile/index',

                'GET shop/products/<id:\d+>' => 'shop/product/view',
                'GET shop/products/category/<id:\d+>' => 'shop/product/category',
                'GET shop/products/brand/<id:\d+>' => 'shop/product/brand',
                'GET shop/products/tag/<id:\d+>' => 'shop/product/tag',
                'GET shop/products' => 'shop/product/index',
                'POST shop/products/<id:\d+>/wish' => 'shop/wishlist/add',
                'POST shop/products/<id:\d+>/cart' => 'shop/cart/add',

                'GET shop/cart' => 'shop/cart/index',
                'DELETE shop/cart' => 'shop/cart/clear',
                'PUT shop/cart/<id:\w+>/quantity' => 'shop/cart/quantity',
                'DELETE shop/cart/<id:\w+>' => 'shop/cart/delete',
                'POST shop/cart/checkout' => 'shop/checkout/index',

                'GET shop/wishlist' => 'shop/wishlist/index',
                'DELETE shop/wishlist/<id:\d+>' => 'shop/wishlist/delete',
            ],
        ],
    ],
    'as authenticator' => [
        'class' => 'filsh\yii2\oauth2server\filters\auth\CompositeAuth',
        'except' => ['site/index', 'oauth2/rest/token'],
        'authMethods' => [
            ['class' => 'yii\filters\auth\HttpBearerAuth'],
            ['class' => 'yii\filters\auth\QueryParamAuth', 'tokenParam' => 'accessToken'],
        ]
    ],
    'as access' => [
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/index', 'oauth2/rest/token'],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'as exceptionFilter' => [
        'class' => 'filsh\yii2\oauth2server\filters\ErrorToExceptionFilter',
    ],
    'params' => $params,
];
