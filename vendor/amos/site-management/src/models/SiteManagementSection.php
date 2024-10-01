<?php

namespace amos\sitemanagement\models;

use open20\amos\core\record\Record;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\seo\interfaces\SeoModelInterface;
use open20\amos\core\interfaces\ContentModelInterface;

/**
 * This is the model class for table "site_management_section".
 */
class SiteManagementSection extends \amos\sitemanagement\models\base\SiteManagementSection implements SeoModelInterface, ContentModelInterface 
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [ 
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::className(),
                    'titleAttribute' => 'name',
                    'imageAttribute' => 'image',
                    'defaultOgType' => 'article',
                    'schema' => 'NewsArticle'
                ]
            ]);
    }
    
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'string'
            ],
        ];
    }

    /**
     * @param Record $model
     * @return mixed
     */
    public static function getAvailableSections($model)
    {
        $tableName = $model->tableName();
        $siteManagementSectionTable = SiteManagementSection::tableName();
        $siteManagementContainerTable = SiteManagementContainer::tableName();
        $siteManagementSliderTable = SiteManagementSlider::tableName();
        $siteManagementPageContentTable = PageContent::tableName();
        $containerSectionId = ($tableName == $siteManagementContainerTable) ? $model->section_id : null;
        $sliderSectionId = ($tableName == $siteManagementSliderTable) ? $model->section_id : null;
        $pageContentSectionId = ($tableName == $siteManagementPageContentTable) ? $model->section_id : null;

        /** @var ActiveQuery $query */
        $query = \amos\sitemanagement\models\SiteManagementSection::find();

        $query->leftJoin($siteManagementContainerTable, $siteManagementContainerTable . '.section_id = ' . $siteManagementSectionTable . '.id')
            ->andWhere([
                'or',
                [$siteManagementContainerTable . '.section_id' => $containerSectionId],
                [$siteManagementContainerTable . '.section_id' => null]
            ]);
//            ->orWhere(['IS NOT', $siteManagementContainerTable . '.deleted_at', null]);

        $query->leftJoin($siteManagementSliderTable, $siteManagementSliderTable . '.section_id = ' . $siteManagementSectionTable . '.id')
            ->andWhere([
                'or',
                [$siteManagementSliderTable . '.section_id' => $sliderSectionId],
                [$siteManagementSliderTable . '.section_id' => null]
            ]);
//            ->orWhere(['IS NOT', $siteManagementSliderTable . '.deleted_at' , null]);

        $query->leftJoin($siteManagementPageContentTable, $siteManagementPageContentTable . '.section_id = ' . $siteManagementSectionTable . '.id')
            ->andWhere([
                'or',
                [$siteManagementPageContentTable . '.section_id' => $pageContentSectionId],
                [$siteManagementPageContentTable . '.section_id' => null]
            ]);
//            ->orWhere(['IS NOT', $siteManagementSliderTable . '.deleted_at', null]);

//        pr($query->createCommand()->getRawSql());
        return $query;
    }
    
        
     /**
     * @inheritdoc
     */
    public function getSchema() {
        
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedFrom() {
        return $this->created_at;
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedAt() {
        return $this->created_at;
    }

    /**
     * @inheritdoc
     */
    public function getCategory() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getCwhValidationStatuses() {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate) {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getGrammar() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns() {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname() {
        return WidgetIconPrintareaDashboard::className();
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription() {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getTitle() {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole() {
        return null;
    }
}
