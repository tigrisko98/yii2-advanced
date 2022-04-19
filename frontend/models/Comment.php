<?php

namespace frontend\models;

use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Publication model
 *
 * @property integer $id
 * @property integer $publication_id
 * @property integer $user_id
 * @property string $text
 * @property boolean $is_main
 * @property boolean $is_answer
 * @property integer $main_comment_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['publication_id', 'in', 'range' => Publication::find()->select('id')->asArray()->column()],
            ['user_id', 'in', 'range' => User::find()->select('id')->asArray()->column()],
            ['main_comment_id', 'in', 'range' => static::find()->select('id')->where(['is_main' => 1])->asArray()->column()]
        ];
    }
}