<?php

namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use common\components\FormValidator;
use frontend\models\UpdateForm;

class UserController extends Controller
{
    public function actionEditSettings()
    {
        $user = Yii::$app->user;

        if (!$user->id) {
            return $this->goHome();
        }

        $model = new UpdateForm();
        $model->nickname = $user->identity->nickname;
        $model->username = $user->identity->username;
        $formData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && isset($formData['edit-button'])) {
            FormValidator::validateFormData($formData['UpdateForm'], $model->attributes);

            if ($model->load($formData) && $model->update($user->identity, $formData['UpdateForm'])) {
                Yii::$app->session->setFlash('success', 'Data has been updated successfully');
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);

    }
}