<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\UserUpdateForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Edit settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Edit your profile data:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'Edit-form']); ?>

            <?= $form->field($model, 'nickname')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'username') ?>

            <div class="form-group">
                <?= Html::submitButton('Edit', ['class' => 'btn btn-primary', 'name' => 'edit-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
