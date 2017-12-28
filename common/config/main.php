<?php
return [
    'language' => 'ru-RU',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'common\bootstrap\SetUp'
    ],
//    'container' => [
//        // Глобальная настройка пагинатора
//        'yii\data\Pagination' => [
//            'pageSize' => 20 // 20 шт. на странице
//        ],
//    ],
    'components' => [
        'cache' => [
//            'class' => 'yii\caching\FileCache',
//            'cachePath' => '@common/runtime/cache',
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true
        ],
        'authManager' => [
            'class' => 'common\rbac\PhpManager', // переопределенный класс
            'roleParam' => 'role', // название колонки в табл. users
            'itemFile' => '@common/rbac/items.php',
            'assignmentFile' => '@common/rbac/assignments.php', // не использ.
            'ruleFile' => '@common/rbac/rules.php',
        ],
        'assetManager' => [
            // ссылки из vendor на ассеты в /backend[frontend]/web/assets/
            // заместо перекачивания файлов из vendor-a
            'linkAssets' => true,
        ],
        'formatter' => [
            'timeZone' => 'Europe/Astrakhan',
//            'dateFormat' => 'php:d.m.Y',
//            'datetimeFormat' => 'php:d.m.Y H:i:s',
//            'decimalSeparator' => ',',
//            'thousandSeparator' => ' ',
            'currencyCode' => 'RUR',
        ],
    ],
];
