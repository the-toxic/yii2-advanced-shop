<?php

namespace common\bootstrap;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use frontend\services\auth\PasswordResetService;

class SetUp implements  BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;

        // Единоразовое создание сервиса с помощью контейнеров
        $container->setSingleton(PasswordResetService::class, [], [
            [$app->params['supportEmail'] => $app->name . ' robot']
        ]);
        // или так, на случай если нужно передать сложные большие данные
        // $container->setSingleton(PasswordResetService::class, function () use ($app) {
        //    return new PasswordResetService([$app->params['supportEmail'] => $app->name . ' robot']);
        // });

    }
}