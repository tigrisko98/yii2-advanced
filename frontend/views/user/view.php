<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\User $user */
/** @var boolean $isFollowing */
/** @var integer $followersCount */
/** @var integer $followingCount */
/** @var boolean $isMyProfile */

/** @var string $userAvatarUrl */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = $user->username;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img src="<?= $userAvatarUrl; ?>" alt="user-avatar" class="img-thumbnail"
                     style="width: 150px; height: 150px; border-radius: 50%">
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6" style="padding-top: 15px">
                        <h2><?= Html::encode($user->nickname) ?></h2>
                    </div>
                    <?php if (!$isMyProfile) : ?>
                        <?php if ($isFollowing) : ?>
                            <div class="col-md-3">

                                <?php $form = ActiveForm::begin(['id' => 'Unfollow-form', 'action' => '/user/unfollow']); ?>

                                <?= $form->field($user, 'id')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($user, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Unfollow', [
                                        'class' => 'btn btn-danger',
                                        'name' => 'unfollow-button',
                                        'style' => 'padding: 6px 30px'
                                    ]) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>

                        <?php else: ?>
                            <div class="col-md-2">

                                <?php $form = ActiveForm::begin(['id' => 'Follow-form', 'action' => '/user/follow']); ?>

                                <?= $form->field($user, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($user, 'id')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($user, 'username')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($user, 'avatar')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($user, 'avatar_url')->textInput()->hiddenInput()->label(false) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Follow', [
                                        'class' => 'btn btn-primary',
                                        'name' => 'follow-button',
                                        'style' => 'padding: 6px 30px'
                                    ]) ?>
                                </div>

                                <?php ActiveForm::end(); ?>

                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="row" style="margin-top: 15px">
                    <div class="col-md-3">
                        <a href="/user/<?= $user->nickname ?>/followers"
                           class="text-dark"><strong><?= $followersCount ?></strong>
                            followers</a>
                    </div>
                    <div class="col-md-3">
                        <a href="/user/<?= $user->nickname ?>/following"
                           class="text-dark"><strong><?= $followingCount ?></strong>
                            following</a>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px">
                    <div class="col-md-7">
                        <h5><?= Html::encode($user->username) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

