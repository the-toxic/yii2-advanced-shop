<?php

namespace shop\services\auth;

use shop\entities\User\User;
use shop\repositories\UserRepository;

class NetworkService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth($network, $identity, $attributes): User
    {
        // Если уже входил через соцсеть
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            return $user;
        }
        // или
        // Регистрируем юзера и соцсеть
        $user = User::signupByNetwork($network, $identity, $attributes);

        $this->users->save($user);

        return $user;
    }

    public function attach($id, $network, $identity, $attributes): void
    {
        if ($this->users->findByNetworkIdentity($network, $identity)) {
            throw new \DomainException('Network is already signed up.');
        }
        $user = $this->users->get($id);

        $user->attachNetwork($network, $identity, $attributes);

        $this->users->save($user);
    }

}