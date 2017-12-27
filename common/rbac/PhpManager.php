<?php
namespace common\rbac;

use yii\rbac\Assignment;
use common\auth\Identity;

class PhpManager extends \yii\rbac\PhpManager
{
    /** @var string */
    public $roleParam = 'role';

    /**
     * Переписан стандартный функционал RBAC
     * Путем переопределения класса PhpManager и добавлением поля role в таблице users
     * Теперь файл assignments не нужен, т.к. роли сразу пишутся в users при создании юзера
     */

     /**
     * @param int|string $userId
     * @return array|mixed|Assignment[]
     */
    public function getAssignments($userId)
    {
//        $user = Yii::$app->getUser();

        /** @var Identity|null $identity */
//        $identity = $user->getIdentity();
        $identity = Identity::findIdentity($userId);

        $assignments = parent::getAssignments($userId);

//        $model = $userId === $user->getId()
//            ? $identity
//            : $identity::findOne($userId);
        $model = $identity;

        if ($model) {
            $assignment = new Assignment;
            $assignment->userId = $userId;
//            $assignment->roleName = $model->{$this->roleParam};
            $assignment->roleName = $model->getRole($this->roleParam);
            $assignments[$assignment->roleName] = $assignment;
        }
        return $assignments;
    }
}