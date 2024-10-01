<?php

namespace open20\socialwall\models\base;

use open20\amos\attachments\behaviors\FileBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the base-model class for table "socialwall_posts_profiles".
 *
 * @property integer $id
 * @property string $type
 * @property string $profile_name
 * @property string $profile_url
 * @property string $profile_picture
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  SocialwallPostsProfiles extends \open20\amos\core\record\ContentModel
{
    public $isSearch = false;
//    private $profile_picture;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socialwall_posts_profiles';
    }

    /**
     * Adding the file behavior
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['type', 'profile_name'], 'string', 'max' => 255],
            [['profile_url'], 'string', 'max' => 600],
            [['profile_picture'], 'file', 'extensions' => 'jpeg, jpg, png, gif', 'maxFiles' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'type' => Yii::t('socialwall', 'Social'),
            'profile_name' => Yii::t('socialwall', 'Nome profilo'),
            'profile_url' => Yii::t('socialwall', 'Url Profilo'),
            'profile_picture' => Yii::t('socialwall', 'Immagine Profilo'),
            'created_at' => Yii::t('socialwall', 'Created at'),
            'updated_at' => Yii::t('socialwall', 'Updated at'),
            'deleted_at' => Yii::t('socialwall', 'Deleted at'),
            'created_by' => Yii::t('socialwall', 'Created by'),
            'updated_by' => Yii::t('socialwall', 'Updated at'),
            'deleted_by' => Yii::t('socialwall', 'Deleted at'),
        ];
    }

    /**
     * Getter for $this->profile_picture;
     */
//    public function getProfile_picture()
//    {
//        if (empty($this->profile_picture)) {
//            $this->profile_picture = $this->hasOneFile('profile_picture')->one();
//        }
//
//        return $this->profile_picture;
//    }

    /**
     * @param $image
     */
//    public function setProfile_picture($image)
//    {
//        $this->profile_picture = $image;
//    }

    public function getProfilePicture() {
        return $this->hasOneFile('profile_picture')->one();
    }

    public function getTitle()
    {
        return "";
    }

    public function getGridViewColumns()
    {
        return [];
    }

    public function getDescription($truncate)
    {
        return "";
    }
}
