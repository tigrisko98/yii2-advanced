<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\FollowForm;
use frontend\models\Publication;
use frontend\models\UnfollowForm;
use frontend\models\UploadAvatarForm;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\CreatePublicationForm;
use yii\web\UploadedFile;

class PublicationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
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

    public function actionCreate()
    {
        $user = Yii::$app->user->identity;

        $model = new CreatePublicationForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['create-publication-button'])) {
            $model->load($formData['CreatePublicationForm'], '');
            $model->images = UploadedFile::getInstances($model, 'images');

            if ($model->create($user->id)) {
                Yii::$app->session->setFlash('success', 'You have been successfully created publication');
                return $this->redirect("/user/$user->nickname");
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionView($id)
    {
        $publication = Publication::findOne($id);
        $publisher = User::findIdentity($publication->user_id);
        $images = unserialize($publication->images_urls);

        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['follow-button-modal'])) {
            $this->actionFollow();
        } elseif (Yii::$app->request->isPost && isset($formData['unfollow-button-modal'])) {
            $this->actionUnfollow();
        }

        return $this->render('view', [
            'publication' => $publication,
            'publisher' => $publisher,
            'images' => $images,
            'modelUpload' => new UploadAvatarForm(),
            'isFollowing' => User::isFollowing(Yii::$app->user->identity, $publisher->id),
            'isMyProfile' => User::isMyProfile($publisher->nickname),
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
}