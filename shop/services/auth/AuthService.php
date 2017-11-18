<?php

namespace shop\services\auth;

use shop\entities\User\User;
use shop\forms\auth\LoginForm;
use shop\repositories\UserRepository;
use Yii;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);

        if (!$user || !$user->validatePassword($form->password)) {
            throw new \DomainException(Yii::t('app', 'Не правильно введен логин или пароль'));
        } elseif ($user && $user->isBlocked()) {
            throw new \DomainException(Yii::t('app', 'Аккаунт заблокирован'));
        } elseif ($user && $user->isWait()) {
            throw new \DomainException(Yii::t('app', 'Аккаунт не подтвержден через email'));
        }
        return $user;
    }
}