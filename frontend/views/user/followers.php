<?php
/** @var yii\web\View $this */
/** @var \common\models\User $user */
/** @var array $userFollowersList */
/** @var \frontend\models\UploadAvatarForm $modelUpload */

/** @var array $authUserFollowingList */

use yii\bootstrap4\Html;

$this->title = $user->nickname . ' followers';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-followers">
    <h2><?= Html::encode($user->nickname . ' followers') ?></h2>
    <table class="table">
        <tbody>

        <?php foreach ($userFollowersList as $follower) : ?>
            <tr>
                <?php $avatarUrl = $follower['avatar_url']; ?>

                <?php if (is_null($avatarUrl)) : ?>
                    <?php $avatarUrl = $modelUpload->getFileUrl($follower['avatar'], $modelUpload->usersAvatarsFolder); ?>
                <?php endif; ?>

                <th><img src="<?= $avatarUrl; ?>" alt="user-avatar"
                         style="width: 40px; height: 40px; border-radius: 50%">
                    <?= $follower['nickname'] ?></th>

                <?php if ($follower['nickname'] == Yii::$app->user->identity->nickname) : ?>
                    <td></td>

                <?php elseif (!isset($authUserFollowingList[$follower['id']])) : ?>
                    <td style="text-align: right">
                        <div>
                            <form id="Follow-form" action="" method="post">
                                <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->csrfParam ?>">
                                <input type="hidden" class="form-group" name="User[id]" value="<?= $follower['id'] ?>">
                                <input type="hidden" class="form-group" name="User[nickname]"
                                       value="<?= $follower['nickname'] ?>">
                                <input type="hidden" class="form-group" name="User[username]"
                                       value="<?= $follower['username'] ?>">
                                <input type="hidden" class="form-group" name="User[avatar]"
                                       value="<?= $follower['avatar'] ?>">
                                <input type="hidden" class="form-group" name="User[avatar_url]"
                                       value="<?= $follower['avatar_url'] ?>">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="follow-button-modal">
                                        Follow
                                    </button>
                                </div>
                            </form>
                        </div>
                    </td>

                <?php else: ?>
                    <td style="text-align: right">
                        <div>
                            <form id="Unfollow-form" action="" method="post">
                                <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->csrfParam ?>">
                                <input type="hidden" class="form-group" name="User[nickname]"
                                       value="<?= $follower['nickname'] ?>">
                                <input type="hidden" class="form-group" name="User[id]" value="<?= $follower['id'] ?>">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger" name="unfollow-button-modal">
                                        Unfollow
                                    </button>
                                </div>
                            </form>
                        </div>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>


