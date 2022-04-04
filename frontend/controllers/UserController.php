<?php

namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use common\components\FormValidator;
use frontend\models\UserUpdateForm;

class UserController extends Controller
{
    public function actionEditSettings()
    {
        $user = Yii::$app->user;

        if (!$user->id) {
            return $this->goHome();
        }

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
}