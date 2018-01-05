<?php

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\User\UserEditForm */
/* @var $user shop\entities\User\User */

use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = 'Edit Profile';
$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = 'Profile';
?>
<div class="user-update">

    <div class="row">
        <div class="col-sm-6">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>
            <?php
            if ($user->phoneIsConfirmed()) {
                echo $form->field($model, 'phone', ['addon' => [
                    'prepend' => ['content' => '+'],
                    'append' => ['content' => 'Подтвержден <i class="glyphicon glyphicon-ok"></i>']
                ]])->textInput(['class' => 'phoneInput', 'maxLength' => true]);
            } else {
                echo $form->field($model, 'phone', ['addon' => [
                    'prepend' => ['content' => '+'],
                    'append' => [
                        'asButton' => true,
                        'content' => Html::button('Подтвердить', ['class'=>'btn btn-primary requestConfirmPhoneBtn']),
                    ]
                ]])->textInput(['class' => 'phoneInput', 'maxLength' => true]);

                $codeField = $form->field($model, 'code', ['addon' => [
                    'prepend' => ['content' => 'Код из СМС'],
                    'append' => [
                        'asButton' => true,
                        'content' => Html::button('Проверить', ['class'=>'btn btn-primary checkCodeBtn']),
                    ],
                ]])->textInput(['class' =>'codeInput'])->label(false);

                echo Html::tag('div', $codeField, ['class' => 'codeField hidden']);
            }
            ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php

$js = <<<JS
    $('.requestConfirmPhoneBtn').click(function() {
        var phone = $('.phoneInput').val().trim();
        
        $.ajax({
            url: '/cabinet/profile/request-confirm-phone',
            type: 'POST',
            data: {
                phone: phone
            },
            success: function(data){
                if (data.status === 'ok') {
                    $('.codeField').removeClass('hidden').find('.codeInput').val('').focus();
                } else {
                    $('.phoneInput').closest('.form-group').find('.help-block').html('<div class="alert alert-danger">'+data.message+'</div>').delay(5000).fadeOut();
                }
            },
            error: function(e){
                alert('Серверная ошибка!');
            }
        });
    });
    $('.checkCodeBtn').click(function() {
      var code = $('.codeInput').val().trim();
      $.ajax({
            url: '/cabinet/profile/confirm-phone',
            type: 'POST',
            data: {
                code: +code
            },
            success: function(data){
                if (data.status === 'ok') {
                    $('.codeField').remove();
                    $('.requestConfirmPhoneBtn').parent().remove();
                    $('.phoneInput').closest('.form-group').find('.help-block').html('<div class="alert alert-success">Телефон успешно подтвержден</div>').fadeIn();
                } else {
                    $('.codeInput').closest('.form-group').find('.help-block').html('<div class="alert alert-danger">'+data.message+'</div>').children().delay(5000).fadeOut();
                }
            },
            error: function(e){
                alert('Серверная ошибка!');
            }
        });
    });
JS;
            if (!$user->phoneIsConfirmed()) {
                $this->registerJs($js);
            }
            ?>

        </div>
    </div>

</div>