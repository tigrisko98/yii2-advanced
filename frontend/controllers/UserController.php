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
use frontend\models\UploadAvatarForm;
use yii\web\UploadedFile;

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
        $user = Yii::$app->user->identity;

        $model = new UserUpdateForm();
        $modelUpload = new UploadAvatarForm();
        $model->nickname = $user->nickname;
        $model->username = $user->username;

        $avatarUrl = $user->avatar_url;

        if (is_null($avatarUrl)) {
            $avatarUrl = $modelUpload->getFileUrl($user->avatar, $modelUpload->usersAvatarsFolder);
        }

        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['edit-button'])) {
            FormValidator::validateFormData($formData['UserUpdateForm'], $model->attributes);

            $modelUpload->avatar = UploadedFile::getInstance($modelUpload, 'avatar');

            if ($model->load($formData) && $model->update($user, $formData['UserUpdateForm'])) {
                if (isset($modelUpload->avatar)) {
                    $modelUpload->upload($user);
                }

                Yii::$app->session->setFlash('success', 'Data has been updated successfully');
                return $this->refresh();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelUpload' => $modelUpload,
            'avatarUrl' => $avatarUrl
        ]);

    }

    public function actionFollowers(string $nickname)
    {
        $user = User::findByNickname($nickname);

        $userFollowersList = $user->followers;

        if (is_null($userFollowersList)) {
            $userFollowersList = [];
        } else {
            $userFollowersList = unserialize($user->followers);
        }

        $authUserFollowingList = $this->getFollowingList(Yii::$app->user->identity->nickname);

        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['follow-button-modal'])) {
            $this->actionFollow();
        } elseif (Yii::$app->request->isPost && isset($formData['unfollow-button-modal'])) {
            $this->actionUnfollow();
        }

        return $this->render('followers', [
            'user' => $user,
            'userFollowersList' => $userFollowersList,
            'authUserFollowingList' => $authUserFollowingList,
            'modelUpload' => new UploadAvatarForm()
        ]);
    }

    public function actionFollowing(string $nickname)
    {
        $user = User::findByNickname($nickname);

        $userFollowingList = $user->following;

        if (is_null($userFollowingList)) {
            $userFollowingList = [];
        } else {
            $userFollowingList = unserialize($user->following);
        }

        $authUserFollowingList = $this->getFollowingList(Yii::$app->user->identity->nickname);

        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['follow-button-modal'])) {
            $this->actionFollow();
        } elseif (Yii::$app->request->isPost && isset($formData['unfollow-button-modal'])) {
            $this->actionUnfollow();
        }

        return $this->render('following', [
            'user' => $user,
            'userFollowingList' => $userFollowingList,
            'authUserFollowingList' => $authUserFollowingList,
            'modelUpload' => new UploadAvatarForm()
        ]);
    }

    public function actionFollow()
    {
        $user = Yii::$app->user;

        $model = new FollowForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && (isset($formData['follow-button']) || isset($formData['follow-button-modal']))) {
            $model->followingDataArray = $formData['User'];
            $model->followingNickname = $formData['User']['nickname'];;
            $model->followingId = $formData['User']['id'];;
            $model->userId = $user->id;
            $model->authUserFollowing = $user->identity->following;

            if (is_null($model->authUserFollowing)) {
                $model->authUserFollowing = [];
            } else {
                $model->authUserFollowing = unserialize($user->identity->following);
            }

            if ($model->follow($user->identity)) {
                Yii::$app->session->setFlash('success', 'You have been successfully followed this user');
            }
        }

        if (!$formData) {
            return $this->goHome();
        }

        if (isset($formData['follow-button-modal'])) {
            return $this->refresh();
        }
        return $this->redirect("/user/{$formData['User']['nickname']}");
    }

    public function actionUnfollow()
    {
        $user = Yii::$app->user;

        $model = new UnfollowForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && (isset($formData['unfollow-button']) || isset($formData['unfollow-button-modal']))) {
            $model->unfollowingNickname = $formData['User']['nickname'];
            $model->unfollowingId = $formData['User']['id'];
            $model->userId = $user->id;
            $model->authUserFollowing = $user->identity->following;

            if (is_null($model->authUserFollowing)) {
                $model->authUserFollowing = [];
            } else {
                $model->authUserFollowing = unserialize($user->identity->following);
            }

            if ($model->unfollow($user->identity)) {
                Yii::$app->session->setFlash('success', 'You have been successfully unfollowed this user');
            }
        }

        if (!$formData) {
            return $this->goHome();
        }

        if (isset($formData['unfollow-button-modal'])) {
            return $this->refresh();
        }

        return $this->redirect("/user/{$formData['User']['nickname']}");
    }

    public function actionView($nickname)
    {
        $user = User::findByNickname($nickname);
        $modelUpload = new UploadAvatarForm();
        $userAvatarUrl = $user->avatar_url;

        if (is_null($userAvatarUrl)) {
            $userAvatarUrl = $modelUpload->getFileUrl($user->avatar, $modelUpload->usersAvatarsFolder);
        }

        $authUser = Yii::$app->user->identity;

        if (!$user) {
            return $this->goHome();
        }

        return $this->render('view', [
            'user' => $user,
            'isFollowing' => $this->isFollowing($authUser, $user),
            'isMyProfile' => $this->isMyProfile($nickname),
            'followersCount' => count($this->getFollowersList($nickname)),
            'followingCount' => count($this->getFollowingList($nickname)),
            'userAvatarUrl' => $userAvatarUrl
        ]);
    }

    private function isFollowing(User $authUser, User $following): bool
    {
        $authUserFollowing = $authUser->following;

        if (is_null($authUserFollowing)) {
            $authUserFollowing = [];
        } else {
            $authUserFollowing = unserialize($authUserFollowing);
        }

        return isset($authUserFollowing[$following->id]);
    }

    private function isMyProfile(string $nickname): bool
    {
        return Yii::$app->user->identity->nickname == $nickname;
    }

    private function getFollowersList($nickname)
    {
        $followingList = unserialize(User::find()->select('followers')->where(['nickname' => $nickname])->one()['followers']);
        return $followingList ? $followingList : [];
    }

    private function getFollowingList($nickname)
    {
        $followingList = unserialize(User::find()->select('following')->where(['nickname' => $nickname])->one()['following']);
        return $followingList ? $followingList : [];
    }
}