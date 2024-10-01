<?php

namespace open20\amos\partnershipprofiles\models\base;

use Yii;

/**
 * This is the base-model class for table "partnership_profiles_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $subtitle
 * @property string $short_description
 * @property string $description
 * @property string $color_text
 * @property string $color_background
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  PartnershipProfilesCategory extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnership_profiles_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['title'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'subtitle', 'short_description', 'color_text', 'color_background'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amospartnershipprofiles', 'ID'),
            'title' => Yii::t('amospartnershipprofiles', 'Titolo'),
            'subtitle' => Yii::t('amospartnershipprofiles', 'Sottotitolo'),
            'short_description' => Yii::t('amospartnershipprofiles', 'Descrizione breve'),
            'description' => Yii::t('amospartnershipprofiles', 'Descrizione'),
            'color_text' => Yii::t('amospartnershipprofiles', 'Color text'),
            'color_background' => Yii::t('amospartnershipprofiles', 'Color background'),
            'created_at' => Yii::t('amospartnershipprofiles', 'Created at'),
            'updated_at' => Yii::t('amospartnershipprofiles', 'Updated at'),
            'deleted_at' => Yii::t('amospartnershipprofiles', 'Deleted at'),
            'created_by' => Yii::t('amospartnershipprofiles', 'Created by'),
            'updated_by' => Yii::t('amospartnershipprofiles', 'Updated by'),
            'deleted_by' => Yii::t('amospartnershipprofiles', 'Deleted by'),
        ];
    }

    /**
     * Relation between category and category-roles mm table.
     * Returns an ActiveQuery related to model NewsCategoryCommunityMm.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnershipProfilesCategoryRoles()
    {
        return $this->hasMany(PartnershipProfilesCategoryRoles::class, ['partnership_profiles_category_id' => 'id']);
    }
}
