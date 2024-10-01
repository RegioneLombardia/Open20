<?php

namespace open20\agid\dataset\models\base;

use Yii;
use open20\agid\dataset\models\AgidDatasetType;
use open20\agid\dataset\models\AgidDatasetContentType;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "agid_dataset".
 *
 * @property integer $id
 * @property integer $agid_dataset_content_type_id
 * @property integer $agid_dataset_type_id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \app\models\AgidDatasetContentType $agidDatasetContentType
 * @property \app\models\AgidDatasetType $agidDatasetType
 */
abstract class AgidDataset extends \open20\amos\core\record\ContentModel implements \open20\amos\seo\interfaces\SeoModelInterface, \open20\amos\core\interfaces\ContentModelInterface
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_dataset';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['agid_dataset_content_type_id', 'agid_dataset_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'status'], 'string', 'max' => 255],
            [['agid_dataset_content_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidDatasetContentType::className(), 'targetAttribute' => ['agid_dataset_content_type_id' => 'id']],
            [['agid_dataset_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidDatasetType::className(), 'targetAttribute' => ['agid_dataset_type_id' => 'id']],
            [['agid_dataset_content_type_id', 'agid_dataset_type_id'], 'required']
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'agid_dataset_content_type_id' => Yii::t('amosdataset', 'Agid Content Type'),
            'agid_dataset_type_id' => Yii::t('amosdataset', 'Agid Type Organizational'),
            'title' => Yii::t('amosdataset', 'Title'),
            'description' => Yii::t('amosdataset', 'Description'),
            'status' => Yii::t('amosdataset', 'Stato'),
            'created_at' => Yii::t('amosdataset', 'Created at'),
            'updated_at' => Yii::t('amosdataset', 'Updated at'),
            'deleted_at' => Yii::t('amosdataset', 'Deleted at'),
            'created_by' => Yii::t('amosdataset', 'Created by'),
            'updated_by' => Yii::t('amosdataset', 'Updated by'),
            'deleted_by' => Yii::t('amosdataset', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidDatasetContentType()
    {
        return $this->hasOne(\open20\agid\dataset\models\AgidDatasetContentType::className(), ['id' => 'agid_dataset_content_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidDatasetType()
    {
        return $this->hasOne(\open20\agid\dataset\models\AgidDatasetType::className(), ['id' => 'agid_dataset_type_id']);
    }


    
    /**
     * 
     * @param bool $truncate
     * @return string
     */
    public function getDescription($truncate) 
    {
        $ret = $this->description;
        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
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
        return $this->title;
    }


    /**
     * Method to get all workflow status for model
     *
     * @return array
     */
    public function getAllWorkflowStatus(){

        return ArrayHelper::map(
                ArrayHelper::getColumn(
                    (new \yii\db\Query())->from('sw_status')
                    ->where(['workflow_id' => \open20\agid\dataset\models\AgidDataset::AGID_DATASET_WORKFLOW])
                    ->orderBy(['sort_order' => SORT_ASC])
                    ->all(),

                    function ($element) {
                        $array['status'] = $element['workflow_id'] . "/" . $element['id'];
                        $array['label'] = $element['label'];
                        return $array;
                    }
                ),
            'status', 'label');
    }

}
