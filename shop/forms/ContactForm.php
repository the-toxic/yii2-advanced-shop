<?php

namespace shop\forms;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha', 'captchaAction' => 'contact/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('app', 'Код с капчи'),
            'name' => Yii::t('app', 'Имя'),
            'subject' => Yii::t('app', 'Тема'),
            'body' => Yii::t('app', 'Сообщение'),
        ];
    }
}
