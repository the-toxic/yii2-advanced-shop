<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-6">
        <div class="well">
            <h2><?= Yii::t('app', 'Новый пользователь') ?></h2>
            <p><strong><?= Yii::t('app', 'Регистрация') ?></strong></p>
            <p>By creating an account you will be able to shop faster, be up to date on an order's status,
                and keep track of the orders you have previously made.</p>
            <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>" class="btn btn-primary">
                <?= Yii::t('app', 'Продолжить') ?>
            </a>
        </div>
        <div class="well">
            <h2><?= Yii::t('app', 'Войти через соц. сети') ?></h2>
            <?= yii\authclient\widgets\AuthChoice::widget([
                'baseAuthUrl' => ['auth/network/auth']
            ]); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="well">
            <h2><?= Yii::t('app', 'Вход') ?></h2>
            <p><strong><?= Yii::t('app', 'Я вернувшийся пользователь') ?></strong></p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                <?= Yii::t('app', 'Если вы забыли пароль, можно его') ?> <?= Html::a(Yii::t('app', 'восстановить'), ['auth/reset/request']) ?>.
            </div>

            <div>
                <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>