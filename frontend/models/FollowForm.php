<?php

namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Follow form
 */
class FollowForm extends Model
{
    public $followingDataArray;
    public $followingNickname;
    public $followingId;
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

        if (is_null($authUserFollowing)) {
            $authUserFollowing = [];
        } else {
            $authUserFollowing = unserialize($authUserFollowing);
        }

        if (isset($authUserFollowing[$this->followingId])) {
            $this->addError($this->followingNickname, 'You have already been followed this user');
        }
    }

    public function follow($user, array $userFollowers): bool|null
    {
        if (!$this->validate()) {
            return null;
        }

        $following = User::findByNickname($this->followingNickname);

        $userFollowers[$following->id] = $this->followingDataArray;
        $user->following = serialize($userFollowers);

        $followingFollowers = $following->followers;

        if (is_null($followingFollowers)) {
            $followingFollowers = [];
        } else {
            $followingFollowers = unserialize($following->followers);
        }

        $followingFollowers[$user->id]['id'] = $user->id;
        $followingFollowers[$user->id]['nickname'] = $user->nickname;
        $followingFollowers[$user->id]['username'] = $user->username;

        $following->followers = serialize($followingFollowers);

        return ($user->save() && $following->update());
    }
}