<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\Publication;
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

        return $this->render('view', [
           'publication' => $publication,
           'publisher' => $publisher,
           'images' => $images
        ]);
    }
}