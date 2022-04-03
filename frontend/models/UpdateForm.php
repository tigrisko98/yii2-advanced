<?php

namespace frontend\models;

use phpDocumentor\Reflection\Element;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Update form
 */
class UpdateForm extends Model
{
    public $nickname;
    public $username;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['nickname', 'trim'],
            ['nickname', 'required'],
            ['nickname', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This nickname has already been taken.'],
            ['nickname', 'string', 'min' => 2, 'max' => 255],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * Updates user's data.
     *1
     * @return bool whether the updating data was successful
     */
    public function update($user, $formData)
    {
        if (!$this->validate(array_keys($formData))) {
            return null;
        }

        foreach ($formData as $key => $value) {
            $user->$key = $value;
        }

        return $user->update();
    }

}
