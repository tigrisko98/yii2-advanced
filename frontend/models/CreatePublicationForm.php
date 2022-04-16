<?php

namespace frontend\models;

use frontend\models\Publication;
use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

/**
 * UploadAvatarForm model
 *
 * @property UploadedFile $images[]
 */
class CreatePublicationForm extends Model
{
    use \bpsys\yii2\aws\s3\traits\S3MediaTrait;

    public $caption;
    public $images;
    public $s3;
    public $usersPublicationsFolder;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->s3 = Yii::$app->get('s3');
        $this->usersPublicationsFolder = Yii::getAlias('@usersPublicationsFolder') . '/';
    }

    public function rules()
    {
        return [
            ['caption', 'string', 'max' => 2000, 'skipOnEmpty' => true],
            ['images', 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10]
        ];
    }

    public function create($userId)
    {
        if ($this->validate()) {
            $imagesUrls = [];
            $publication = new Publication();

            foreach ($this->images as $image) {
                $this->saveUploadedFile($image, '', $this->usersPublicationsFolder . $image->baseName);
                $imagesUrls[] = $this->getFileUrl($image->name, $this->usersPublicationsFolder);
            }

            $publication->user_id = $userId;
            $publication->caption = $this->caption;
            $publication->images_urls = serialize($imagesUrls);
            $publication->save();

            return true;
        }

        return false;
    }
}