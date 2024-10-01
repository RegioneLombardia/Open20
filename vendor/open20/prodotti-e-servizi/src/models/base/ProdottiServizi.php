<?php

namespace open20\prodottiservizi\models\base;

use open20\prodottiservizi\Module;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "prodotti_servizi".
 *
 * @property integer $id
 * @property integer $id_product_root
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property string $financial_description
 * @property string $facilitation_features
 * @property string $projects_features
 * @property string $expenses_eligible
 * @property string $status
 * @property integer $scope
 * @property integer $solution
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class ProdottiServizi extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prodotti_servizi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_product_root', 'scope', 'solution', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'subtitle', 'description', 'status', 'scope', 'solution'], 'required'],
            [['description', 'financial_description', 'facilitation_features', 'projects_features', 'expenses_eligible'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'subtitle', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('prodottiservizi', 'ID'),
            'id_product_root' => Module::t('prodottiservizi', 'Id Product Root'),
            'title' => Module::t('prodottiservizi', 'Title'),
            'subtitle' => Module::t('prodottiservizi', 'Subtitle'),
            'description' => Module::t('prodottiservizi', 'Description'),
            'financial_description' => Module::t('prodottiservizi', 'Financial Description'),
            'facilitation_features' => Module::t('prodottiservizi', 'Facilitation Features'),
            'projects_features' => Module::t('prodottiservizi', 'Projects Features'),
            'expenses_eligible' => Module::t('prodottiservizi', 'Eligible Expenses'),
            'status' => Module::t('prodottiservizi', 'Status'),
            'scope' => Module::t('prodottiservizi', 'Scope'),
            'solution' => Module::t('prodottiservizi', 'Solution'),
            'created_at' => Module::t('prodottiservizi', 'Created At'),
            'updated_at' => Module::t('prodottiservizi', 'Updated At'),
            'deleted_at' => Module::t('prodottiservizi', 'Deleted At'),
            'created_by' => Module::t('prodottiservizi', 'Created By'),
            'updated_by' => Module::t('prodottiservizi', 'Updated By'),
            'deleted_by' => Module::t('prodottiservizi', 'Deleted By'),
        ];
    }
}