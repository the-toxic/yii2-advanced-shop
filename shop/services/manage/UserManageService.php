<?php

namespace shop\services\manage;

use shop\entities\User\User;
use shop\forms\manage\User\UserCreateForm;
use shop\forms\manage\User\UserEditForm;
use shop\repositories\UserRepository;
use shop\services\newsletter\Newsletter;

class UserManageService
{
    private $repository;
    private $newsletter;

    public function __construct(UserRepository $repository, Newsletter $newsletter)
    {
        $this->repository = $repository;
        $this->newsletter = $newsletter;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password,
            $form->role
        );

        $this->repository->save($user);

        $this->newsletter->subscribe($user->email);

        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);

        $user->edit(
            $form->username,
            $form->email,
            $form->role
        );

        $this->repository->save($user);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);
        $this->newsletter->unsubscribe($user->email);
    }
}