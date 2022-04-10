<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\web\Controller;
use common\components\FormValidator;
use frontend\models\UserUpdateForm;
use yii\filters\AccessControl;
use frontend\models\FollowForm;
use frontend\models\UnfollowForm;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['edit-settings', 'follow', 'unfollow'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionEditSettings()
    {
        $user = Yii::$app->user;

        $model = new UserUpdateForm();
        $model->nickname = $user->identity->nickname;
        $model->username = $user->identity->username;
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['edit-button'])) {
            FormValidator::validateFormData($formData['UserUpdateForm'], $model->attributes);

            if ($model->load($formData) && $model->update($user->identity, $formData['UserUpdateForm'])) {
                Yii::$app->session->setFlash('success', 'Data has been updated successfully');
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);

    }

    public function actionGetFollowersList($nickname)
    {
        $followersList = unserialize(User::find()->select('followers')->where(['nickname' => $nickname])->one()['followers']);
        return $followersList ? $followersList : [];
    }

    public function actionGetFollowingList($nickname)
    {
        $followingList = unserialize(User::find()->select('following')->where(['nickname' => $nickname])->one()['following']);
        return $followingList ? $followingList : [];
    }

    public function actionFollow()
    {
        $user = Yii::$app->user;

        $model = new FollowForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['follow-button'])) {
            $model->followingNickname = $formData['User']['nickname'];
            $model->followingId = $formData['User']['id'];
            $model->userId = $user->id;

            if ($model->follow($user->identity, $this->actionGetFollowersList($user->identity->nickname))) {
                Yii::$app->session->setFlash('success', 'You have been successfully followed this user');
            }
        }

        if (!$formData) {
            return $this->goHome();
        }
        return $this->redirect("/user/{$formData['User']['nickname']}");
    }

    public function actionUnfollow()
    {
        $user = Yii::$app->user;

        $model = new UnfollowForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['unfollow-button'])) {
            $model->unfollowingNickname = $formData['User']['nickname'];
            $model->unfollowingId = $formData['User']['id'];
            $model->userId = $user->id;

            if ($model->unfollow($user->identity, $this->actionGetFollowersList($user->identity->nickname))) {
                Yii::$app->session->setFlash('success', 'You have been successfully unfollowed this user');
            }
        }

        if (!$formData) {
            return $this->goHome();
        }
        return $this->redirect("/user/{$formData['User']['nickname']}");
    }

    public function actionView($nickname)
    {
        $user = User::findByNickname($nickname);
        $authUser = Yii::$app->user->identity;

        if (!$user) {
            return $this->goHome();
        }

        return $this->render('view', [
            'user' => $user,
            'isFollowing' => $this->isFollowing($authUser, $user),
            'isMyProfile' => $this->isMyProfile($nickname),
            'followersList' => $this->actionGetFollowersList($nickname),
            'followersCount' => count($this->actionGetFollowersList($nickname)),
            'followingList' => $this->actionGetFollowingList($nickname),
            'followingCount' => count($this->actionGetFollowingList($nickname)),
        ]);
    }

    private function isFollowing($authUser, $following)
    {
        $authUserFollowing = $authUser->following;

        if (is_null($authUserFollowing)) {
            $authUserFollowing = [];
        } else {
            $authUserFollowing = unserialize($authUserFollowing);
        }

        return isset($authUserFollowing[$following->id]);
    }

    private function isMyProfile($nickname)
    {
        return Yii::$app->user->identity->nickname == $nickname;
    }
}