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

<script>
    function Openform(formId) {
        document.getElementById(formId).style.display = '';
    }
</script>

<script>
    function Closeform(formId) {
        document.getElementById(formId).style.display = 'none';
    }
</script>

<div class="publication-view">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <?= PublicationImagesCarousel::renderCarousel($images); ?>
            </div>
            <div class="container col-md-5 border">
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
                             style="width: 50px; height: 50px; border-radius: 50%; margin-top: -15px">
                    </div>
                    <div class="col-md-10" style="margin-top: 3px; margin-left: -10px">
                        <strong><?= $publisher->nickname; ?></strong>
                        <?= $publication->caption; ?>
                    </div>
                </div>
                <div class="container pre-scrollable" style="max-height: 435px">
                    <?php foreach ($commentsWithAnswersSubArray as $comment): ?>
                        <div class="row" style="margin-top: 15px">
                            <div class="col-md-2">
                                <img src="<?= $comment['user_avatar_url']; ?>" alt="user-avatar"
                                     style="width: 50px; height: 50px; border-radius: 50%;">
                            </div>
                            <div class="col-md-10" style="margin-top: 13px; margin-left: -10px">
                                <strong><?= $comment['user_nickname']; ?></strong>
                                <?= $comment['text']; ?>
                                <button class="badge badge-secondary"
                                        onclick="Openform('Create-answer-form-<?= $comment['id']; ?>');">
                                    Answer
                                </button>

                                <?php if ($comment['user_id'] == $authUser['id']): ?>
                                    <button class="badge badge-secondary"
                                            onclick="Openform('Update-comment-form-<?= $comment['id']; ?>');">
                                        Edit
                                    </button>

                                    <form id="Delete-comment-form-<?= $comment['id']; ?>" method="post"
                                          action="/comment/<?= $comment['id']; ?>/delete">
                                        <input type="hidden" name="_csrf-frontend"
                                               value="<?= Yii::$app->request->csrfParam ?>">
                                        <button type="submit" class="badge badge-danger"
                                                name="delete-comment-button">
                                            Delete
                                        </button>
                                    </form>
                                <?php endif; ?>

                            </div>
                        </div>
                        <form id="Create-answer-form-<?= $comment['id']; ?>"
                              action="/publication/<?= $publication->id; ?>/comment"
                              method="post" style="display: none">
                            <input type="hidden" name="_csrf-frontend"
                                   value="<?= Yii::$app->request->csrfParam ?>">
                            <input type="hidden" class="form-group" name="CreateCommentForm[publication_id]"
                                   value="<?= $publication->id ?>">
                            <input type="hidden" class="form-group" name="CreateCommentForm[user_id]"
                                   value="<?= $authUser->id ?>">
                            <input type="hidden" class="form-group" name="CreateCommentForm[is_main]"
                                   value="0">
                            <input type="hidden" class="form-group" name="CreateCommentForm[is_answer]"
                                   value="1">
                            <input type="hidden" class="form-group" name="CreateCommentForm[main_comment_id]"
                                   value="<?= $comment['id']; ?>">
                            <input type="hidden" class="form-group" name="CreateCommentForm[user_nickname]"
                                   value="<?= $authUser->nickname; ?>">
                            <input type="hidden" class="form-group" name="CreateCommentForm[user_avatar_url]"
                                   value="<?= $authUser->avatar_url; ?>">
                            <div class="input-group" style="max-width: 462px">
                                <input type="text" class="form-control" name="CreateCommentForm[text]"
                                       placeholder="@<?= $comment['user_nickname']; ?>"
                                       aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"
                                            name="create-comment-button" style="padding: 0 20.5px;">
                                        Answer
                                    </button>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="Closeform('Create-answer-form-<?= $comment['id']; ?>')">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </form>

                        <form id="Update-comment-form-<?= $comment['id']; ?>" method="post"
                              action="/comment/<?= $comment['id']; ?>/edit" style="display: none">
                            <input type="hidden" name="_csrf-frontend"
                                   value="<?= Yii::$app->request->csrfParam ?>">
                            <div class="input-group" style="max-width: 462px">
                                <input type="text" class="form-control" name="UpdateCommentForm[text]"
                                       value="<?= $comment['text']; ?>" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"
                                            name="update-comment-button" style="padding: 0 20.5px;">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </form>

                        <?php foreach ($comment['answers'] as $answer): ?>
                            <div class="row" style="margin-left: 15px">
                                <div class="col-md-2">
                                    <img src="<?= $answer['user_avatar_url']; ?>" alt="user-avatar"
                                         style="width: 50px; height: 50px; border-radius: 50%;">
                                </div>
                                <div class="col-md-10" style="margin-top: 13px; margin-left: -10px">
                                    <strong><?= $answer['user_nickname']; ?></strong>
                                    <?= $answer['text']; ?>
                                    <button class="badge badge-secondary"
                                            onclick="Openform('Create-answer-form-<?= $answer['id']; ?>');">
                                        Answer
                                    </button>

                                    <?php if ($answer['user_id'] == $authUser['id']): ?>
                                        <button class="badge badge-secondary"
                                                onclick="Openform('Update-comment-form-<?= $answer['id']; ?>');">
                                            Edit
                                        </button>

                                        <form id="Delete-comment-form-<?= $answer['id']; ?>" method="post"
                                              action="/comment/<?= $answer['id']; ?>/delete">
                                            <input type="hidden" name="_csrf-frontend"
                                                   value="<?= Yii::$app->request->csrfParam ?>">
                                            <button type="submit" class="badge badge-danger"
                                                    name="delete-comment-button">
                                                Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <form id="Create-answer-form-<?= $answer['id']; ?>"
                                  action="/publication/<?= $publication->id; ?>/comment"
                                  method="post" style="display: none">
                                <input type="hidden" name="_csrf-frontend"
                                       value="<?= Yii::$app->request->csrfParam ?>">
                                <input type="hidden" class="form-group" name="CreateCommentForm[publication_id]"
                                       value="<?= $publication->id ?>">
                                <input type="hidden" class="form-group" name="CreateCommentForm[user_id]"
                                       value="<?= $authUser->id ?>">
                                <input type="hidden" class="form-group" name="CreateCommentForm[is_main]"
                                       value="0">
                                <input type="hidden" class="form-group" name="CreateCommentForm[is_answer]"
                                       value="1">
                                <input type="hidden" class="form-group" name="CreateCommentForm[main_comment_id]"
                                       value="<?= $answer['main_comment_id']; ?>">
                                <input type="hidden" class="form-group" name="CreateCommentForm[user_nickname]"
                                       value="<?= $authUser->nickname; ?>">
                                <input type="hidden" class="form-group" name="CreateCommentForm[user_avatar_url]"
                                       value="<?= $authUser->avatar_url; ?>">
                                <div class="input-group" style="max-width: 450px">
                                    <input type="text" class="form-control" name="CreateCommentForm[text]"
                                           placeholder="@<?= $comment['user_nickname']; ?>"
                                           aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary"
                                                name="create-comment-button" style="padding: 0 20.5px;">
                                            Answer
                                        </button>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                            onclick="Closeform('Create-answer-form-<?= $answer['id']; ?>')">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </form>

                            <form id="Update-comment-form-<?= $answer['id']; ?>" method="post"
                                  action="/comment/<?= $answer['id']; ?>/edit" style="display: none">
                                <input type="hidden" name="_csrf-frontend"
                                       value="<?= Yii::$app->request->csrfParam ?>">
                                <div class="input-group" style="max-width: 462px">
                                    <input type="text" class="form-control" name="UpdateCommentForm[text]"
                                           value="<?= $answer['text']; ?>" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary"
                                                name="update-comment-button" style="padding: 0 20.5px;">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row" style="padding-left: 663px">
                <form id="Create-comment-form" action="/publication/<?= $publication->id; ?>/comment" method="post">
                    <input type="hidden" name="_csrf-frontend"
                           value="<?= Yii::$app->request->csrfParam ?>">
                    <input type="hidden" class="form-group" name="CreateCommentForm[publication_id]"
                           value="<?= $publication->id ?>">
                    <input type="hidden" class="form-group" name="CreateCommentForm[user_id]"
                           value="<?= $authUser->id ?>">
                    <input type="hidden" class="form-group" name="CreateCommentForm[is_main]"
                           value="1">
                    <input type="hidden" class="form-group" name="CreateCommentForm[is_answer]"
                           value="0">
                    <input type="hidden" class="form-group" name="CreateCommentForm[user_nickname]"
                           value="<?= $authUser->nickname; ?>">
                    <input type="hidden" class="form-group" name="CreateCommentForm[user_avatar_url]"
                           value="<?= $authUser->avatar_url; ?>">
                    <div class="input-group" style="width: 141%">
                        <input type="text" class="form-control" name="CreateCommentForm[text]"
                               placeholder="Leave a comment"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"
                                    name="create-comment-button">
                                Comment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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