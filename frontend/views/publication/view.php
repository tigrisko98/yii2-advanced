<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \frontend\models\Publication $publication */
/** @var \common\models\User $publisher */
/** @var \frontend\models\UploadAvatarForm $modelUpload */
/** @var array $images */
/** @var boolean $isMyProfile */
/** @var boolean $isFollowing */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

if (!$publisher->avatar_url) {
    $publisher->avatar_url = $modelUpload->getFileUrl($publisher->avatar, $modelUpload->usersAvatarsFolder);
}

?>

<div class="publication-view">
    <div class="container">
        <div class="row">
            <div class="col-md-7" style="background-color: black">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="false">
                    <div class="carousel-inner">

                        <ol class="carousel-indicators">
                            <?php foreach ($images as $key => $image): ?>
                                <?php if ($key == 0): ?>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="<?= $key; ?>"
                                        class="active"></li>
                                <?php else: ?>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="<?= $key; ?>"></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ol>

                        <?php foreach ($images as $key => $image): ?>

                            <?php if ($key == 0): ?>
                                <div class="carousel-item active">
                            <?php else: ?>
                                <div class="carousel-item">
                            <?php endif; ?>
                                <img class="d-block w-100" src="<?= $image; ?>" alt="Publication image"
                                     style="width: 600px; height: 600px">
                                </div>

                            <?php endforeach; ?>
                    </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>

                    </div>
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

                                    <?php $form = ActiveForm::begin(['id' => 'Unfollow-form', 'action' => '']); ?>

                                    <?= $form->field($publisher, 'id')->textInput()->hiddenInput()->label(false) ?>

                                    <?= $form->field($publisher, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                    <div class="form-group">
                                        <?= Html::submitButton('Unfollow', [
                                            'class' => 'btn btn-danger',
                                            'name' => 'unfollow-button-modal',
                                            'style' => 'padding: 6px 30px'
                                        ]) ?>
                                    </div>

                                    <?php ActiveForm::end(); ?>
                                </div>

                            <?php else: ?>
                                <div class="col-md-2">

                                    <?php $form = ActiveForm::begin(['id' => 'Follow-form', 'action' => '']); ?>

                                    <?= $form->field($publisher, 'nickname')->textInput()->hiddenInput()->label(false) ?>

                                    <?= $form->field($publisher, 'id')->textInput()->hiddenInput()->label(false) ?>

                                    <?= $form->field($publisher, 'username')->textInput()->hiddenInput()->label(false) ?>

                                    <?= $form->field($publisher, 'avatar')->textInput()->hiddenInput()->label(false) ?>

                                    <?= $form->field($publisher, 'avatar_url')->textInput()->hiddenInput()->label(false) ?>

                                    <div class="form-group">
                                        <?= Html::submitButton('Follow', [
                                            'class' => 'btn btn-primary',
                                            'name' => 'follow-button-modal',
                                            'style' => 'padding: 6px 30px'
                                        ]) ?>
                                    </div>

                                    <?php ActiveForm::end(); ?>

                                </div>
                            <?php endif; ?>
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
                </div>
            </div>
        </div>
    </div>
</div>
