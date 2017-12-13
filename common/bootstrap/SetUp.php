<?php

namespace common\bootstrap;

use Yii;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\caching\Cache;
use yii\di\Instance;
use yii\mail\MailerInterface;
use shop\services\ContactService;

class SetUp implements  BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        // Контейнер внедрения зависимостей
        $container = Yii::$container;
        // Позволяет внедрить в объект (только в момент его вызова откуда-либо) нужные параметры

        // Единоразовое создание сервиса с помощью контейнеров
//        $container->setSingleton(PasswordResetService::class);
        // или анонимной функцией, на случай если нужно передать сложные большие данные
        // $container->setSingleton(PasswordResetService::class, function () use ($app) {
        //    return new PasswordResetService([$app->params['supportEmail'] => $app->name . ' robot']);
        // });

        $container->setSingleton(Client::class, function () {
            return ClientBuilder::create()->build();
        });

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        // pageUrlRule требует это
        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail']
        ]);

    }
}