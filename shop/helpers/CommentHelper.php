<?php

namespace shop\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CommentHelper
{
    public static function activeList(): array
    {
        return [
            0 => 'No',
            1 => 'Yes',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::activeList(), $status);
    }

    public static function activeLabel(bool $active): string
    {
        switch ($active) {
            case false:
                $class = 'label label-default';
                break;
            case true:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::activeList(), $active), [
            'class' => $class,
        ]);
    }
}