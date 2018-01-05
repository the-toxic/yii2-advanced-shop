<?php

use shop\entities\User\Network;
use shop\helpers\UserHelper;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model shop\entities\User\User */
/* @var $networks shop\entities\User\Network */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'username',
                    'email:email',
                    'phone',
                    [
                        'attribute' => 'status',
                        'value' => UserHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Role',
                        'value' => implode(', ', ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($model->id), 'description')),
                        'format' => 'raw',

                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $networks,
                'columns' => [
                    'id',
                    'network',
                    'identity',
                    [
                        'attribute' => 'attributes',
                        'value' => function (Network $network) {
                            $source = (string) $network->getAttribute('attributes');
                            $data = json_decode($source, true);
                            if(is_array($data)) foreach ($data as $key => &$value) {
                                $value = "<div>{$key}: {$value}</div>";
                            } else $data = $source;
                            return implode('', (array) $data);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
