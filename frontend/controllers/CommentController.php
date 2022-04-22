<?php

namespace frontend\controllers;

use frontend\models\Comment;
use frontend\models\CreateCommentForm;
use frontend\models\UpdateCommentForm;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class CommentController extends Controller
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
        $model = new CreateCommentForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['create-comment-button'])) {

            foreach ($formData['CreateCommentForm'] as $key => $value) {
                $model->$key = $value;
            }

            if ($model->create()) {
                return $this->redirect("/publication/{$formData['CreateCommentForm']['publication_id']}");
            }
        }

        return $this->redirect("/publication/{$formData['CreateCommentForm']['publication_id']}");
    }

    public function actionEdit($id)
    {
        $comment = Comment::findOne($id);
        $model = new UpdateCommentForm();
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['update-comment-button'])) {
            $model->text = $formData['UpdateCommentForm']['text'];

            if ($model->update($comment)) {
                return $this->redirect("/publication/$comment->publication_id");
            }
        }

        return $this->redirect("/publication/$comment->publication_id");
    }

}