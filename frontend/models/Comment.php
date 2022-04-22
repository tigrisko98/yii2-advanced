<?php

namespace frontend\models;

use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

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
 * @property string $user_nickname
 * @property string $user_avatar_url
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

    public static function getAnswers(int $mainCommentId): array
    {
        return static::find()->where(['main_comment_id' => $mainCommentId])->asArray()->all();
    }

    public static function createAnswersSubArray(array &$comments): array
    {
        foreach ($comments as $key => $comment) {
            if ($comment['is_main'] == 1) {
                $comments[$key]['answers'] = static::getAnswers($comment['id']);
            }

            if ($comment['is_answer'] == 1) {
                unset($comments[$key]);
            }
        }

        return $comments;
    }
}