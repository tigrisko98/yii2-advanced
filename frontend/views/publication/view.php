<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\Publication $publication */
/** @var \common\models\User $publisher */
/** @var \frontend\models\UploadAvatarForm $modelUpload */
/** @var array $images */
/** @var boolean $isMyProfile */
/** @var boolean $isFollowing */
/** @var \common\models\User $authUser */

/** @var array $commentsWithAnswersSubArray */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use frontend\views\layouts\PublicationImagesCarousel;

if (!$publisher->avatar_url) {
    $publisher->avatar_url = $modelUpload->getFileUrl($publisher->avatar, $modelUpload->usersAvatarsFolder);
}

?>

<div class="publication-view">
    <div class="container">
        <div class="row">
            <div class="col-md-7" style="background-color: black">
                <?= PublicationImagesCarousel::renderCarousel($images); ?>
            </div>
            <div class="col-md-5 border">
                <div class="row">
                    <div class="col-md-2">
                        <img src="<?= $publisher->avatar_url ?>" alt="user-avatar"
                             style="width: 50px; height: 50px; border-radius: 50%; margin-top: 10px">
                    </div>
                    <div class="col-md-4" style="margin-top: 25px; margin-left: -10px">
                        <strong><?= $publisher->nickname; ?></strong>
                    </div>

                    <?php if (!$isMyProfile) : ?>
                        <?php if ($isFollowing) : ?>
                            <div class="col-md-3">

                                <?php $form = ActiveForm::begin(['id' => 'Unfollow-form', 'action' => '/user/unfollow']); ?>

                                <?= $form->field($publisher, 'id')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($publisher, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($publication, 'id')->textInput()->hiddenInput()->label(false) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Unfollow', [
                                        'class' => 'btn btn-danger',
                                        'name' => 'unfollow-button-publication',
                                        'style' => 'padding: 6px 30px'
                                    ]) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>

                        <?php else: ?>
                            <div class="col-md-2">

                                <?php $form = ActiveForm::begin(['id' => 'Follow-form', 'action' => '/user/follow']); ?>

                                <?= $form->field($publisher, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($publisher, 'id')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($publisher, 'username')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($publisher, 'avatar')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($publisher, 'avatar_url')->textInput()->hiddenInput()->label(false) ?>

                                <?= $form->field($publication, 'id')->textInput()->hiddenInput()->label(false) ?>


                                <div class="form-group">
                                    <?= Html::submitButton('Follow', [
                                        'class' => 'btn btn-primary',
                                        'name' => 'follow-button-publication',
                                        'style' => 'padding: 6px 30px'
                                    ]) ?>
                                </div>

                                <?php ActiveForm::end(); ?>

                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="col-6" style="text-align: right; margin-top: 17px">
                            <button type="button" class="btn btn-outline-secondary" data-toggle="modal"
                                    data-target="#optionsModal">Options
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-2">
                        <img src="<?= $publisher->avatar_url ?>" alt="user-avatar"
                             style="width: 50px; height: 50px; border-radius: 50%;">
                    </div>
                    <div class="col-md-10" style="margin-top: 13px; margin-left: -10px">
                        <strong><?= $publisher->nickname; ?></strong>
                        <?= $publication->caption; ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <div class="col-md-12">
<!--                        --><?php //foreach ($commentsWithAnswersSubArray as $comment): ?>
<!--                            <div class="col-md-2">-->
<!--                                <img src="--><?//= $comment['avatar_url']; ?><!--" alt="user-avatar"-->
<!--                                     style="width: 50px; height: 50px; border-radius: 50%;">-->
<!--                            </div>-->
<!--                        --><?php //endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="Create-comment-form" action="/publication/<?= $publication->id; ?>/comment" method="post">
    <input type="hidden" name="_csrf-frontend"
           value="<?= Yii::$app->request->csrfParam ?>">
    <input type="hidden" class="form-group" name="CreateCommentForm[publication_id]"
           value="<?= $publication->id ?>">
    <input type="hidden" class="form-group" name="CreateCommentForm[user_id]"
           value="<?= $authUser->id ?>">
    <input type="text" class="form-group text-comment" name="CreateCommentForm[text]" placeholder="Leave a comment">
    <input type="hidden" class="form-group" name="CreateCommentForm[is_main]"
           value="1">
    <input type="hidden" class="form-group" name="CreateCommentForm[is_answer]"
           value="0">
    <input type="hidden" class="form-group" name="CreateCommentForm[user_nickname]"
           value="<?= $authUser->nickname; ?>">
    <input type="hidden" class="form-group" name="CreateCommentForm[user_avatar_url]"
           value="<?= $authUser->avatar_url; ?>">
    <div class="form-group">
        <button type="submit" class="btn btn-primary"
                name="create-comment-button">
            Comment
        </button>
    </div>
</form>

<div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="optionsModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="optionsModal">Publication options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'Options-form',
                    'action' => "/publication/$publication->id/delete"
                ]); ?>

                <div class="col-md-12" style="text-align: center">
                    <div class="form-group">
                        <?= Html::submitButton('Delete', [
                            'class' => 'btn btn-danger',
                            'name' => 'delete-publication-button',
                            'style' => 'padding: 6px 30px'
                        ]) ?>
                    </div>
                    <a href="/publication/<?= $publication->id; ?>/edit" class="btn btn-info" style="padding: 7px 39px">Edit</a>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>