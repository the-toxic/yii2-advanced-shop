<?php
return [
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
    ],
];
