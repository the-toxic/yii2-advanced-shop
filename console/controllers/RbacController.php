<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Permissions
        $accessBackend = $auth->createPermission('accessBackend');
        $accessBackend->description = 'Can access backend';
        $auth->add($accessBackend);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);

        // Assignments
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $accessBackend);

        // После изменения ролей и прав необходимо в консоли выполнить
        // ./yii rbac/init и файлик /common/rbac/items.php пересоберется
        // Так-же при добавлении ролей не забыть добавить их в /common/rbac/Roles.php

    }

    // Переписан стандартный функционал RBAC
    // Путем переопределения класса PhpManager и добавлением поля role в таблице users
    // Теперь файл assignments не нужен, т.к. роли сразу пишутся в users при создании юзера

    // Проверку прав доступа реализуем либо через behaviors в нужном контроллере либо через Yii::$app->user->can('pageUpdate');
    //public function behaviors(): array
    //{
    //    return [
    //        'access' => [
    //            'class' => yii\filters\AccessControl::className(),
    //            'rules' => [[
    //                'allow' => true,
    //                'actions' => ['update'],
    //                'roles' => ['pageUpdate'],
    //            ]],
    //        ],
    //    ];
    //}
}