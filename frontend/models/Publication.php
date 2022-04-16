<?php

namespace frontend\models;

use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Publication model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $caption
 * @property string $images_urls
 * @property integer $created_at
 * @property integer $updated_at
 */
class Publication extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%publications}}';
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
            ['user_id', 'in', 'range' => User::find()->select('id')->asArray()->column()],
        ];
    }

    public static function findPublicationsByUserId($id)
    {
        return static::find()->where(['user_id' => $id])->all();
    }

    public static function findPublicationsFirstImageUrls($userId)
    {
        $publications = static::find()->where(['user_id' => $userId])->asArray()->all();
        $firstImageUrls = [];

        foreach ($publications as $publication) {
            $firstImageUrls[$publication['id']] = unserialize($publication['images_urls'])[0];
        }

        return$firstImageUrls;
    }
}