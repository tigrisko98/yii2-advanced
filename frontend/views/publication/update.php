<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\PublicationUpdateForm $model */
/** @var \frontend\models\Publication $publication */

/** @var array $images */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use frontend\views\layouts\PublicationImagesCarousel;

$this->title = 'Update publication';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-publication">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <?= PublicationImagesCarousel::renderCarousel($images); ?>
            </div>
            <div class="col-md-5">
                <?php $form = ActiveForm::begin(['options' => ['id' => 'Update-publication-form']]); ?>

                <?= $form->field($model, 'caption')->textarea(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Update publication', ['class' => 'btn btn-primary', 'name' => 'update-publication-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

