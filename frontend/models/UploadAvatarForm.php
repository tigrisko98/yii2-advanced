<?php

namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\web\UploadedFile;

/**
 * UploadAvatarForm model
 *
 * @property UploadedFile $avatar
 */
class UploadAvatarForm extends Model
{
    use \bpsys\yii2\aws\s3\traits\S3MediaTrait;

    public $avatar;
    public $s3;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->s3 = Yii::$app->get('s3');
    }

    public function rules()
    {
        return [
            ['avatar', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function upload(User $user)
    {
        if ($this->validate()) {
            $this->saveUploadedFile($this->avatar, '', 'images/users-avatars/' . $this->avatar->baseName);

            $user->avatar = $this->avatar->baseName . '.' . $this->avatar->extension;
            $user->avatar_url = $this->getFileUrl($this->avatar->name, 'images/users-avatars/');
            $user->update();

            return true;
        }

        return false;
    }
}