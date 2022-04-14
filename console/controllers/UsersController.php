<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class UsersController extends Controller
{
    public function actionGenerate($quantity = 10)
    {
        for ($i = 11; $i <= $quantity; $i++) {
            $user = new User();
            $user->nickname = 'asd' . $i;
            $user->username = 'Kostiantyn' . $i;
            $user->email = 'asd' . $i . '@mailik.com';
            $user->setPassword(11111111);
            $user->status = 10;
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->save();
        }

        echo "Success";
    }

}