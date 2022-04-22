<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\Comment;
use frontend\models\CreateCommentForm;
use frontend\models\Publication;
use frontend\models\PublicationUpdateForm;
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
                'only' => ['create', 'delete', 'update'],
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

        $comments = $publication->getComments();
        $commentsWithAnswersSubArray = Comment::createAnswersSubArray($comments);

        $authUser = Yii::$app->user->identity;

        return $this->render('view', [
            'publication' => $publication,
            'publisher' => $publisher,
            'images' => $images,
            'modelUpload' => new UploadAvatarForm(),
            'isFollowing' => User::isFollowing($authUser, $publisher->id),
            'isMyProfile' => User::isMyProfile($publisher->nickname),
            'authUser' => $authUser,
            'commentsWithAnswersSubArray' => $commentsWithAnswersSubArray,
            'isM'
        ]);
    }

    public function actionDelete($id)
    {
        $publication = Publication::findOne($id);
        $authUserNickname = Yii::$app->user->identity->nickname;

        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['delete-publication-button'])) {
            if ($publication->deleteComments() && $publication->delete()) {
                Yii::$app->session->setFlash('success', 'You have been successfully deleted publication');
            }
        }

        return $this->redirect("/user/$authUserNickname");
    }

    public function actionEdit($id)
    {
        $publication = Publication::findOne($id);
        $model = new PublicationUpdateForm();
        $images = unserialize($publication->images_urls);

        $formData = Yii::$app->request->post();
        $model->caption = $publication->caption;

        if (Yii::$app->request->isPost && isset($formData['update-publication-button'])) {
            if ($model->load($formData['PublicationUpdateForm'], '') && $model->update($publication)) {
                Yii::$app->session->setFlash('success', 'Publication has been updated successfully');
                return $this->redirect("/publication/{$publication->id}");
            }
        }

        return $this->render('update', [
            'model' => $model,
            'publication' => $publication,
            'images' => $images
        ]);
    }
}