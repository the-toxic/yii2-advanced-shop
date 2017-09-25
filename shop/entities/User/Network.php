<?php

namespace shop\entities\User;

use Webmozart\Assert\Assert;
use yii\db\ActiveRecord;

/**
 * @property integer $user_id
 * @property string $identity
 * @property string $network
 */
class Network extends ActiveRecord
{
    public static function create($network, $identity, $attributes): self
    {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);
        Assert::notEmpty($attributes);

        $item = new static();
        $item->network = $network;
        $item->identity = $identity;
        $item->attributes = json_encode($attributes);

        return $item;
    }

    public function isFor($network, $identity): bool
    {
        return $this->network === $network && $this->identity === $identity;
    }

    public static function tableName()
    {
        return '{{%user_networks}}';
    }
}