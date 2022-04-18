<?php

namespace frontend\models;

use yii\base\Model;

class PublicationUpdateForm extends Model
{
    public $caption;

    public function rules()
    {
        return [
            ['caption', 'string', 'max' => 2000, 'skipOnEmpty' => true],
        ];
    }

    public function update(Publication $publication)
    {
        if ($this->validate()) {
            $publication->caption = $this->caption;
            $publication->update();

            return true;
        }

        return false;
    }
}