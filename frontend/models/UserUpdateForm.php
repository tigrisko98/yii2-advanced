<?php

namespace frontend\models;

use yii\base\Model;

/**
 * Update form
 */
class UserUpdateForm extends Model
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
            ['nickname', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This nickname has already been taken.'],
            ['nickname', 'string', 'min' => 2, 'max' => 255, 'skipOnEmpty' => false],

            ['username', 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 255, 'skipOnEmpty' => false],
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
