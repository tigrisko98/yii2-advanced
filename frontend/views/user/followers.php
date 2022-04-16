<?php
/** @var yii\web\View $this */
/** @var \common\models\User $user */
/** @var array $userFollowersList */
/** @var \frontend\models\UploadAvatarForm $modelUpload */
/** @var array $authUserFollowingList */

use yii\bootstrap4\Html;
use frontend\views\layouts\FollowAndUnfollowForm;

$this->title = $user->nickname . ' followers';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-followers">
    <h2><?= Html::encode($user->nickname . ' followers') ?></h2>
    <div class="container">
        <?= FollowAndUnfollowForm::renderFollowOrFollowingList($userFollowersList, $modelUpload, $authUserFollowingList); ?>
    </div>
</div>


