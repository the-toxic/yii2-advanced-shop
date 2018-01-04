<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

    <div class="row">
        <div id="content" class="col-sm-9">
            <?= $content ?>
        </div>
        <aside id="column-right" class="col-sm-3 hidden-xs">
            <div class="list-group">
                <a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>" class="list-group-item">Кабинет</a>
                <a href="<?= Html::encode(Url::to(['/cabinet/profile/edit'])) ?>" class="list-group-item">Редактировать профиль</a>
                <a href="<?= Html::encode(Url::to(['/cabinet/order'])) ?>" class="list-group-item">Заказы</a>
                <a href="<?= Html::encode(Url::to(['/cabinet/wishlist/index'])) ?>" class="list-group-item">Список желаний</a>
            </div>
        </aside>
    </div>

<?php $this->endContent() ?>