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

    public function rules()
    {
        return [
            ['followingNickname', 'in', 'range' => User::find()->select('nickname')->asArray()->column()],
        ];
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