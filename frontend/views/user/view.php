<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\User $user */
/** @var boolean $isFollowing */
/** @var integer $followersCount */
/** @var array $followersList */
/** @var integer $followingCount */
/** @var array $followingList */

/** @var boolean $isMyProfile */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = $user->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <h1><?= Html::encode($user->username) ?></h1>
    <button type="button" class="btn btn-primary" data-toggle="modal"
            data-target=".bd-example-modal-lg"><?= $followersCount ?> followers
    </button>

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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <p class="modal-header modal-title h3">Followers</p>
                <table class="table">
                    <tbody>
                    <?php foreach ($followersList as $follower) : ?>
                        <tr>
                            <th scope="row"><?= $follower ?></th>
                            <?php if (in_array($follower, $followingList)) : ?>
                                <div>
                                    <?php $form = ActiveForm::begin(['id' => 'Follow-form', 'action' => '/user/follow']); ?>

                                    <?= $form->field($user, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                    <?= $form->field($user, 'id')->textInput()->hiddenInput()->label(false) ?>
                                    <td>
                                        <div class="form-group">
                                            <?= Html::submitButton('Follow', ['class' => 'btn btn-primary', 'name' => 'follow-button']) ?>
                                        </div>
                                        <?php ActiveForm::end() ?>
                                    </td>
                                </div>
                            <?php elseif ($follower == Yii::$app->user->identity->nickname) : ?>
                                <td></td>

                            <?php else: ?>
                                <div>
                                    <?php $form = ActiveForm::begin(['id' => 'Unfollow-form', 'action' => '/user/unfollow']); ?>

                                    <?= $form->field($user, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                    <?= $form->field($user, 'id')->textInput()->hiddenInput()->label(false) ?>
                                    <td>
                                        <div class="form-group">
                                            <?= Html::submitButton('Unfollow', ['class' => 'btn btn-danger', 'name' => 'unfollow-button']) ?>
                                        </div>
                                        <?php ActiveForm::end() ?>
                                    </td>
                                </div>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
