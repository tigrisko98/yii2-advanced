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
    public $authUserFollowing;

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
        if (isset($this->authUserFollowing[$this->followingId])) {
            $this->addError($this->followingNickname, 'You have already been followed this user');
        }
    }

    public function follow(User $authUser): bool|null
    {
        if (!$this->validate()) {
            return null;
        }

        $following = User::findByNickname($this->followingNickname);

        $this->authUserFollowing[$following->id] = $this->followingDataArray;
        $authUser->following = serialize($this->authUserFollowing);

        $followingFollowers = $following->followers;

        if (is_null($followingFollowers)) {
            $followingFollowers = [];
        } else {
            $followingFollowers = unserialize($following->followers);
        }

        $followingFollowers[$authUser->id]['id'] = $authUser->id;
        $followingFollowers[$authUser->id]['nickname'] = $authUser->nickname;
        $followingFollowers[$authUser->id]['username'] = $authUser->username;
        $followingFollowers[$authUser->id]['avatar'] = $authUser->avatar;
        $followingFollowers[$authUser->id]['avatar_url'] = $authUser->avatar_url;

        $following->followers = serialize($followingFollowers);

        return ($authUser->save() && $following->update());
    }
}