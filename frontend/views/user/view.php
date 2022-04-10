<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\User $user */
/** @var boolean $isFollowing */

/** @var boolean $isMyProfile */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = $user->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <h1><?= Html::encode($user->username) ?></h1>
    <?php if (!$isMyProfile) : ?>
        <?php if ($isFollowing) : ?>
            <div class="row">
                <div class="col-lg-5">
                    <p>Unfollow:</p>
                    <?php $form = ActiveForm::begin(['id' => 'Unfollow-form', 'action' => '/user/unfollow']); ?>

                    <?= $form->field($user, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                    <?= $form->field($user, 'id')->textInput()->hiddenInput()->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Unfollow', ['class' => 'btn btn-danger', 'name' => 'unfollow-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-5">
                    <p>Follow:</p>
                    <?php $form = ActiveForm::begin(['id' => 'Follow-form', 'action' => '/user/follow']); ?>

                    <?= $form->field($user, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                    <?= $form->field($user, 'id')->textInput()->hiddenInput()->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Follow', ['class' => 'btn btn-primary', 'name' => 'follow-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
