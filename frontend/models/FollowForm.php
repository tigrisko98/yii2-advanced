<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Follow form
 */
class FollowForm extends Model
{
    public $followingNickname;
    public $userId;

    public function rules()
    {
        return [
            ['followingNickname', 'in', 'range' => User::find()->select('nickname')->asArray()->column()],
            ['followingNickname', 'validateFollowingNickname'],
            ['userId', 'in', 'range' => User::find()->select('id')->asArray()->column()]
        ];
    }

    public function validateFollowingNickname()
    {
        $authUserFollowing = User::find()->select('following')->where(['id' => $this->userId])->one()['following'];
        if ($authUserFollowing === null) {
            $authUserFollowing = [];
        } else {
            $authUserFollowing = unserialize($authUserFollowing);
        }

        if (in_array($this->followingNickname, $authUserFollowing)) {
            $this->addError($this->followingNickname, 'You have already been followed this user');
        }
    }

    public function follow($user, array $userFollowers)
    {
        if (!$this->validate()) {
            return null;
        }

        $userFollowers[] = $this->followingNickname;
        $user->following = serialize($userFollowers);

        $following = User::findByNickname($this->followingNickname);

        if (!$following->followers) {
            $followingFollowers = $following->followers = [];
        } else {
            $followingFollowers = unserialize($following->followers);
        }

        $followingFollowers[] = $user->nickname;

        $following->followers = serialize($followingFollowers);

        if ($user->save() && $following->update()) {
            return true;
        }
        return false;
    }
}