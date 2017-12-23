<?php

namespace shop\forms\Shop\Order;

use yii\base\Model;

class CustomerForm extends Model
{
    public $phone;
    public $name;

    public function rules(): array
    {
        return [
            [['phone', 'name'], 'required'],
            [['phone'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 128],
        ];
    }
}