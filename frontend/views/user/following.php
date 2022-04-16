<?php
/** @var yii\web\View $this */
/** @var \common\models\User $user */
/** @var array $userFollowingList */
/** @var \frontend\models\UploadAvatarForm $modelUpload */
/** @var array $authUserFollowingList */

use yii\bootstrap4\Html;
use frontend\views\layouts\FollowAndUnfollowForm;

$this->title = $user->nickname . ' following';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-following">
    <h2><?= Html::encode($user->nickname . ' following') ?></h2>
    <div class="container">
        <?= FollowAndUnfollowForm::renderFollowOrFollowingList($userFollowingList, $modelUpload, $authUserFollowingList); ?>
    </div>
</div>
