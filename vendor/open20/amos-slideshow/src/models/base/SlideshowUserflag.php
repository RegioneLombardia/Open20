<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

namespace open20\amos\slideshow\models\base;

use open20\amos\slideshow\AmosSlideshow;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "slideshow_userflag".
 *
 * @property integer $id
 * @property integer $slideshow_route_id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\slideshow\models\SlideshowRoute $slideshowRoute
 * @property \open20\amos\core\user\User $user
 */
class SlideshowUserflag extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slideshow_userflag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slideshow_route_id', 'user_id'], 'required'],
            [['slideshow_route_id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['slideshow_route_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\slideshow\models\SlideshowRoute::className(), 'targetAttribute' => ['slideshow_route_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosSlideshow::t('amosslideshow', 'ID'),
            'slideshow_route_id' => AmosSlideshow::t('amosslideshow', 'Slideshow Route ID'),
            'user_id' => AmosSlideshow::t('amosslideshow', 'User ID'),
            'created_at' => AmosSlideshow::t('amosslideshow', 'Created At'),
            'updated_at' => AmosSlideshow::t('amosslideshow', 'Updated At'),
            'deleted_at' => AmosSlideshow::t('amosslideshow', 'Deleted At'),
            'created_by' => AmosSlideshow::t('amosslideshow', 'Created By'),
            'updated_by' => AmosSlideshow::t('amosslideshow', 'Updated By'),
            'deleted_by' => AmosSlideshow::t('amosslideshow', 'Deleted By'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlideshowRoute()
    {
        return $this->hasOne(\open20\amos\slideshow\models\SlideshowRoute::className(), ['id' => 'slideshow_route_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }
}
