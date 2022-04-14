<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var string $avatarUrl */

/** @var \frontend\models\UserUpdateForm $model */
/** @var \frontend\models\UploadAvatarForm $modelUpload */

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
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'Edit-form']]); ?>

            <img class="img-thumbnail" src="<?= $avatarUrl; ?>" alt="user_avatar">

            <?= $form->field($modelUpload, 'avatar')->fileInput()->label('Update your profile photo:') ?>

            <?= $form->field($model, 'nickname')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'username') ?>

            <div class="form-group">
                <?= Html::submitButton('Edit', ['class' => 'btn btn-primary', 'name' => 'edit-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
