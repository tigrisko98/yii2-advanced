<?php

namespace frontend\models;

use yii\base\Model;

class UpdateCommentForm extends Model
{
    public $text;

    public function rules()
    {
        return [
            ['text', 'trim'],
            ['text', 'string', 'max' => 2000, 'skipOnEmpty' => true],
        ];
    }

    public function update(Comment $comment)
    {
        if (!$this->validate()) {
            return null;
        }

        $comment->text = $this->text;

        return $comment->update();
    }
}