<?php
namespace frontend\views\layouts;

use Yii;

class FollowAndUnfollowForm
{
    public static function renderFollowOrFollowingList(array $userFollowersOrFollowingList, $modelUpload, $authUserFollowingList)
    {
        ob_start(); ?>

        <?php foreach ($userFollowersOrFollowingList as $follower) : ?>

        <?php $avatarUrl = $follower['avatar_url']; ?>

        <div class="row" style="padding-top: 20px">
            <div class="col-md-4" style="text-align: right;">

                <?php if (!$avatarUrl) : ?>
                    <?php $avatarUrl = $modelUpload->getFileUrl($follower['avatar'], $modelUpload->usersAvatarsFolder); ?>
                <?php endif; ?>

                <a href="/user/<?= $follower['nickname']; ?>" class="text-dark">
                    <?= $follower['nickname'] ?>
                    <img src="<?= $avatarUrl; ?>" alt="user-avatar"
                         style="width: 50px; height: 50px; border-radius: 50%">
                </a>

            </div>

            <?php if ($follower['nickname'] == Yii::$app->user->identity->nickname) : ?>

            <?php elseif (!isset($authUserFollowingList[$follower['id']])) : ?>
                <div class="col-md-4" style="text-align: right;">

                    <form id="Follow-form" action="" method="post">
                        <input type="hidden" name="_csrf-frontend"
                               value="<?= Yii::$app->request->csrfParam ?>">
                        <input type="hidden" class="form-group" name="User[id]"
                               value="<?= $follower['id'] ?>">
                        <input type="hidden" class="form-group" name="User[nickname]"
                               value="<?= $follower['nickname'] ?>">
                        <input type="hidden" class="form-group" name="User[username]"
                               value="<?= $follower['username'] ?>">
                        <input type="hidden" class="form-group" name="User[avatar]"
                               value="<?= $follower['avatar'] ?>">
                        <input type="hidden" class="form-group" name="User[avatar_url]"
                               value="<?= $follower['avatar_url'] ?>">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"
                                    name="follow-button-modal">
                                Follow
                            </button>
                        </div>
                    </form>
                </div>

            <?php else: ?>
                <div class="col-md-4" style="text-align: right">
                    <form id="Unfollow-form" action="" method="post">
                        <input type="hidden" name="_csrf-frontend"
                               value="<?= Yii::$app->request->csrfParam ?>">
                        <input type="hidden" class="form-group" name="User[nickname]"
                               value="<?= $follower['nickname'] ?>">
                        <input type="hidden" class="form-group" name="User[id]"
                               value="<?= $follower['id'] ?>">
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger"
                                    name="unfollow-button-modal">
                                Unfollow
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

        <?php return ob_get_clean();

    }

}