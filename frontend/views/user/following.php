<?php
/** @var yii\web\View $this */
/** @var \common\models\User $user */
/** @var array $userFollowingList */

/** @var array $authUserFollowingList */

use yii\bootstrap4\Html;

$this->title = $user->nickname . ' following';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-following">
    <h1><?= Html::encode($user->nickname . ' followers') ?></h1>
    <table class="table">
        <tbody>
        <?php foreach ($userFollowingList as $follower) : ?>
            <tr>
                <th><?= $follower['nickname'] ?></th>

                <?php if ($follower['nickname'] == Yii::$app->user->identity->nickname) : ?>
                    <td></td>
                <?php elseif (!isset($authUserFollowingList[$follower['id']])) : ?>
                    <td style="text-align: right">
                        <div>
                            <form id="Follow-form" action="" method="post">
                                <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->csrfParam ?>">
                                <input type="hidden" class="form-group" name="User[nickname]"
                                       value="<?= $follower['nickname'] ?>">
                                <input type="hidden" class="form-group" name="User[id]" value="<?= $follower['id'] ?>">
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
                            <form id="Follow-form" action="" method="post">
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
