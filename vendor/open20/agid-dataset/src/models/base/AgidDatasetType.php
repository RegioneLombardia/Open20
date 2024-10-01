<?php

namespace open20\agid\dataset\models\base;

use Yii;

/**
 * This is the base-model class for table "agid_dataset_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\agid\dataset\models\AgidDataset[] $agidDatasets
 */
class AgidDatasetType extends \open20\amos\core\record\ContentModel
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_dataset_type';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['description'], 'string'],
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
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'deleted_at' => Yii::t('app', 'Deleted at'),
            'created_by' => Yii::t('app', 'Created at'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'deleted_by' => Yii::t('app', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidDatasets()
    {
        return $this->hasMany(\open20\agid\dataset\models\AgidDataset::className(), ['agid_dataset_type_id' => 'id']);
    }

       
    /**
     * 
     * @param bool $truncate
     * @return string
     */
    public function getDescription($truncate) 
    {
        $ret = $this->name;
        if ($truncate) {
            $ret = $this->__shortText($this->name, 200);
        }
        return $ret;
    }

    /**
     * 
     * @return array
     */
    public function getGridViewColumns() 
    {
        return [];
    }

    /**
     * 
     * @return string
     */
    public function getTitle() 
    {
        return $this->name;
    }
}
