<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Unfollow form
 */
class UnfollowForm extends Model
{
    public $unfollowingNickname;
    public $unfollowingId;
    public $userId;
    public $authUserFollowing;

    public function rules()
    {
        return [
            ['unfollowingNickname', 'in', 'range' => User::find()->select('nickname')->asArray()->column()],
            ['unfollowingNickname', 'validateUnfollowingNickname'],
            ['userId', 'in', 'range' => User::find()->select('id')->asArray()->column()]
        ];
    }

    public function validateUnfollowingNickname()
    {
        if (!isset($this->authUserFollowing[$this->unfollowingId])) {
            $this->addError($this->unfollowingNickname, 'You have not yet been followed this user');
        }
    }

    public function unfollow(User $authUser): bool|null
    {
        if (!$this->validate()) {
            return null;
        }

        $unfollowing = User::findByNickname($this->unfollowingNickname);

        unset($this->authUserFollowing[$unfollowing->id]);
        $authUser->following = serialize($this->authUserFollowing);

        $followingFollowers = unserialize($unfollowing->followers);
        unset($followingFollowers[$authUser->id]);
        $unfollowing->followers = serialize($followingFollowers);

        return ($authUser->save() && $unfollowing->update());
    }
}