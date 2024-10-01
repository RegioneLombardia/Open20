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
 * Class Slideshow
 * @package open20\amos\slideshow\models\base
 *
 * This is the base-model class for table "slideshow".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\slideshow\models\SlideshowRoute[] $slideshowRoutes
 * @property \open20\amos\slideshow\models\SlideshowPage[] $slideshowPages
 */
class Slideshow extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slideshow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'label', 'eval_contoller_method'], 'string'],
            [['default_not_show_again'], 'boolean'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosSlideshow::t('amosslideshow', 'ID'),
            'name' => AmosSlideshow::t('amosslideshow', 'Titolo'),
            'label' => AmosSlideshow::t('amosslideshow', 'Label'),
            'description' => AmosSlideshow::t('amosslideshow', 'Descrizione'),
            'eval_contoller_method' => AmosSlideshow::t('amosslideshow', 'Metodo da valutare nel controller'),
            'default_not_show_again' => AmosSlideshow::t('amosslideshow', 'Non visualizzare alla prossima visita (default)'),
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
    public function getSlideshowRoutes()
    {
        return $this->hasOne(\open20\amos\slideshow\models\SlideshowRoute::className(), ['slideshow_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlideshowPages()
    {
        return $this->hasMany(\open20\amos\slideshow\models\SlideshowPage::className(), ['slideshow_id' => 'id']);
    }
}
