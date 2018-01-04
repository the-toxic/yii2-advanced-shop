<?php

namespace shop\services\cabinet;

use shop\forms\User\ProfileEditForm;
use shop\repositories\UserRepository;

class ProfileService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function edit($id, ProfileEditForm $form): void
    {
        $user = $this->users->get($id);
        $user->editProfile($form->email, $form->phone);
        $this->users->save($user);
    }

    public function requestConfirmPhone($id, $phone): void
    {
        $user = $this->users->get($id);
        $user->requestConfirmPhone($phone);
    }

    public function confirmPhone($id, $code): void
    {
        $user = $this->users->get($id);
        if ($user->confirmPhone($code)) {
            $this->users->save($user);
        }
    }
}