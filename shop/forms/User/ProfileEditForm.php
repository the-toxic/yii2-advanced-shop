<?php

namespace shop\forms\User;

use shop\entities\User\User;
use yii\base\Model;

class ProfileEditForm  extends Model
{
    public $phone;
    public $email;
    public $code; // не ActiveRecord, нужно для отображения формы

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->phone = $user->phone;
        $this->email = $user->email;

        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'filter', 'filter' => function ($value) { // не проверяет, только обрабатывает
                return preg_replace("/[^0-9]/", '', $value);
            }],
            [['phone'], 'string', 'length' => 11],
            [['phone', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }
}