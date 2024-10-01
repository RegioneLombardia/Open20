<?php

namespace open20\agid\dataset\models;

use yii\helpers\ArrayHelper;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use open20\amos\seo\behaviors\SeoContentBehavior;

/**
 * This is the model class for table "agid_dataset".
 */
class AgidDataset extends \open20\agid\dataset\models\base\AgidDataset
{
    // Workflow ID
    const AGID_DATASET_WORKFLOW = 'AgidDatasetWorkflow';
    // Workflow states IDS
    const AGID_DATASET_WORKFLOW_STATUS_DRAFT = "AgidDatasetWorkflow/DRAFT";
    const AGID_DATASET_WORKFLOW_STATUS_VALIDATED = "AgidDatasetWorkflow/VALIDATED";


    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self:: AGID_DATASET_WORKFLOW)->getInitialStatusId();
        }
    }
    

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self:: AGID_DATASET_WORKFLOW,
                    'propagateErrorsToModel' => true,
            ],
            'workflowLog' => [
                    'class' => WorkflowLogFunctionsBehavior::className(),
            ],
            'SeoContentBehavior' => [
                'class' => SeoContentBehavior::className(),
                'imageAttribute' => null,
                'titleAttribute' => 'title',
                'descriptionAttribute' => 'description',
                'defaultOgType' => 'dataset',
                'schema' => 'Dataset'
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
        return [];
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
        return ArrayHelper::merge(parent::rules(), []);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), []);
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'agid_dataset_content_type_id',
                'label' => $labels['agid_dataset_content_type_id'],
                'type' => 'integer',
            ],
            [
                'slug' => 'agid_dataset_type_id',
                'label' => $labels['agid_dataset_type_id'],
                'type' => 'integer',
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string',
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text',
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string',
            ],
        ];
    }

	/**
	 * @return string marker path
	 */
    public function getIconMarker()
    {
        return null; //TODO
    }

	/**
	 * If events are more than one, set 'array' => true in the calendarView in the index.
	 * @return array events
	 */
    public function getEvents()
    {
        return null; //TODO
    }

	/**
	 * @return url event (calendar of activities)
	 */
    public function getUrlEvent()
    {
        return null; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

	/**
	 * @return color event
	 */
    public function getColorEvent()
    {
        return null; //TODO
    }

	/**
	 * @return title event
	 */
    public function getTitleEvent()
    {
        return null; //TODO
    }

    /**
     * 
     */
    public function getSchema() 
    {
        $dataset      = new \simialbi\yii2\schemaorg\models\Dataset();
        $dataset->description    = $this->description;
        \simialbi\yii2\schemaorg\helpers\JsonLDHelper::add($dataset);
        return \simialbi\yii2\schemaorg\helpers\JsonLDHelper::render();
        
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::AGID_DATASET_WORKFLOW_STATUS_VALIDATED;
    }
    
}
