<?php

namespace shop\services\cabinet;

use shop\forms\User\ProfileEditForm;
use shop\repositories\UserRepository;
use shop\services\sms\SmsSender;
use Yii;

class ProfileService
{
    private $users;
    private $smsSender;

    public function __construct(UserRepository $users, SmsSender $smsSender)
    {
        $this->users = $users;
        $this->smsSender = $smsSender;
    }

    public function edit($userId, ProfileEditForm $form): void
    {
        $user = $this->users->get($userId);
        $user->editProfile($form->email, $form->phone);
        $this->users->save($user);
    }

    public function requestConfirmPhone($post): void
    {
        $phone = preg_replace("/[^0-9]/", '', $post['phone']);
        if (strlen($phone) !== 11) throw new \DomainException('Некорректный телефон');

        if (Yii::$app->session->has('new_phone_confirm_expire')
            && Yii::$app->session->get('new_phone_confirm_expire') > time()) {
            throw new \DomainException('Код уже выслан');
        }
        Yii::$app->session->set('new_phone', $phone);
        Yii::$app->session->set('new_phone_confirm_code', random_int(10000, 99999));
        Yii::$app->session->set('new_phone_confirm_expire', time() + 180);
        Yii::$app->session->set('new_phone_confirm_limit', 3);

//        $this->smsSender->send($phone, Yii::$app->session->get('new_phone_confirm_code'));

        $msgLog = 'Запрос на отправку СМС на номер '.$phone.' с текстом: '.Yii::$app->session->get('new_phone_confirm_code');
        Yii::info($msgLog, 'sms');
    }

    public function confirmPhone($userId, $code): void
    {
        if (!Yii::$app->session->has('new_phone'))
            throw new \DomainException('Подтверждение номера не запрошено');
        if (Yii::$app->session->get('new_phone_confirm_expire') < time())
            throw new \DomainException('Истек срок действия кода, запросите новый');
        if (Yii::$app->session->get('new_phone_confirm_limit') <= 0)
            throw new \DomainException('Превышено количество попыток введения кода');

        if ($code != Yii::$app->session->get('new_phone_confirm_code')) {
            Yii::$app->session->set('new_phone_confirm_limit', Yii::$app->session->get('new_phone_confirm_limit') - 1);
            throw new \DomainException('Некорректный код');
        }

        $user = $this->users->get($userId);
        $user->confirmPhone(Yii::$app->session->get('new_phone'));
        $this->users->save($user);

        Yii::$app->session->remove('new_phone');
        Yii::$app->session->remove('new_phone_confirm_code');
        Yii::$app->session->remove('new_phone_confirm_expire');
        Yii::$app->session->remove('new_phone_confirm_limit');
    }
}