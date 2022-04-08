<?php

namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\web\Controller;
use common\components\FormValidator;
use frontend\models\UserUpdateForm;
use yii\filters\AccessControl;
use frontend\models\FollowForm;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['edit-settings', 'follow'],
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

    public function actionFollow()
    {
        $user = Yii::$app->user;

        $model = new FollowForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['follow-button'])) {
            $model->followingNickname = $formData['User']['nickname'];
            $model->userId = $user->id;
            if ($model->follow($user->identity, $this->actionGetFollowersList())) {
                Yii::$app->session->setFlash('success', 'You have been successfully followed this user');
            }
        }

        if (!$formData) {
            return $this->goHome();
        }
        return $this->redirect("/user/{$formData['User']['nickname']}");
    }

    public function actionGetFollowersList()
    {
        $followersList = unserialize(Yii::$app->user->identity->followers);
        return $followersList ? $followersList : [];
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
        ]);
    }

    private function isFollowing($authUser, $following)
    {
        $authUserFollowing = $authUser->following;
        if ($authUserFollowing === null) {
            $authUserFollowing = [];
        } else {
            $authUserFollowing = unserialize($authUserFollowing);
        }

        return in_array($following->nickname, $authUserFollowing);
    }

    private function isMyProfile($nickname)
    {
        return Yii::$app->user->identity->nickname == $nickname;
    }
}