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

    public function unfollow(User $user, array $userFollowers): bool|null
    {
        if (!$this->validate()) {
            return null;
        }

        $unfollowing = User::findByNickname($this->unfollowingNickname);

        unset($userFollowers[$unfollowing->id]);
        $user->following = serialize($userFollowers);

        $followingFollowers = unserialize($unfollowing->followers);
        unset($followingFollowers[$user->id]);
        $unfollowing->followers = serialize($followingFollowers);

        return ($user->save() && $unfollowing->update());
    }
}