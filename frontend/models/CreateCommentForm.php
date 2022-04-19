<?php

namespace frontend\models;

use yii\base\Model;

class CreateCommentForm extends Model
{
    public $publication_id;
    public $user_id;
    public $text;
    public $is_main;
    public $is_answer;
    public $main_comment_id = null;

    public function rules()
    {
        return [
            ['text', 'trim'],
            ['text', 'required'],
            ['text', 'string', 'max' => 2000],
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return null;
        }

        $comment = new Comment();

        foreach ($this as $key => $value) {
            $comment->$key = $value;
        }

        return $comment->save();
    }
}