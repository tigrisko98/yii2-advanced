<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\CreatePublicationForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create publication';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="create-publication">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'Create-publication-form']]); ?>

            <?= $form->field($model, 'caption')->textarea(['autofocus' => true]) ?>

            <?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])
                ->label('Attach photos to publication:') ?>

            <div class="form-group">
                <?= Html::submitButton('Create publication', ['class' => 'btn btn-primary', 'name' => 'create-publication-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
