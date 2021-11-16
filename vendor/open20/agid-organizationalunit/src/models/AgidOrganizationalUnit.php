<?php

namespace open20\agid\organizationalunit\models;

use yii\helpers\ArrayHelper;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitServiceMm;
use open20\agid\organizationalunit\i18n\grammar\AgidOrganizationalUnitGrammar;
use open20\agid\organizationalunit\models\base\AgidOrganizationalUnit as BaseAgidOrganizationalUnit;
use open20\amos\admin\models\base\UserProfile;

/**
 * This is the model class for table "agid_organizational_unit".
 */
class AgidOrganizationalUnit extends BaseAgidOrganizationalUnit
{

    // Workflow ID
    const AGID_ORGANIZATIONAL_UNIT_WORKFLOW = 'AgidOrganizationalUnitWorkflow';
    // Workflow states IDS
    const AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_DRAFT = "AgidOrganizationalUnitWorkflow/DRAFT";
	const AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED = "AgidOrganizationalUnitWorkflow/VALIDATED";
	

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self:: AGID_ORGANIZATIONAL_UNIT_WORKFLOW)->getInitialStatusId();
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
				'defaultWorkflowId' => self:: AGID_ORGANIZATIONAL_UNIT_WORKFLOW,
				'propagateErrorsToModel' => true,
			],
			'workflowLog' => [
				'class' => WorkflowLogFunctionsBehavior::className(),
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className(),
            ],
            'SeoContentBehavior' => [
                'class' => SeoContentBehavior::className(),
                'imageAttribute' => null,
                'titleAttribute' => 'name',
                'descriptionAttribute' => 'short_description',
                'defaultOgType' => 'organization',
                'schema' => 'Organization'
            ]
        ]);
    }
    

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
            'name'
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

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'agid_organizational_unit_content_type_id',
                'label' => $labels['agid_organizational_unit_content_type_id'],
                'type' => 'integer',
            ],
            [
                'slug' => 'agid_organizational_unit_type_id',
                'label' => $labels['agid_organizational_unit_type_id'],
                'type' => 'integer',
            ],
            [
                'slug' => 'user_profile_president_id',
                'label' => $labels['user_profile_president_id'],
                'type' => 'integer',
            ],
            [
                'slug' => 'user_profile_vice_president_id',
                'label' => $labels['user_profile_vice_president_id'],
                'type' => 'integer',
            ],
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string',
            ],
            [
                'slug' => 'short_description',
                'label' => $labels['short_description'],
                'type' => 'text',
            ],
            [
                'slug' => 'skills',
                'label' => $labels['skills'],
                'type' => 'text',
            ],
            [
                'slug' => 'headquarters_name',
                'label' => $labels['headquarters_name'],
                'type' => 'text',
            ],
            [
                'slug' => 'address',
                'label' => $labels['address'],
                'type' => 'text',
            ],
            [
                'slug' => 'public_hours',
                'label' => $labels['public_hours'],
                'type' => 'text',
            ],
            [
                'slug' => 'cap',
                'label' => $labels['cap'],
                'type' => 'string',
            ],
            [
                'slug' => 'telephone_reference',
                'label' => $labels['telephone_reference'],
                'type' => 'text',
            ],
            [
                'slug' => 'mail_reference',
                'label' => $labels['mail_reference'],
                'type' => 'text',
            ],
            [
                'slug' => 'pec_reference',
                'label' => $labels['pec_reference'],
                'type' => 'text',
            ],
            [
                'slug' => 'further_information',
                'label' => $labels['further_information'],
                'type' => 'text',
            ],
            [
                'slug' => 'help_box',
                'label' => $labels['help_box'],
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
     * @return AgidOrganizationalUnitGrammar|mixed
     */
    public function getGrammar()
    {
        
        return new AgidOrganizationalUnitGrammar();
        
    }
    
    /**
     *
     * @return type
     */
    public function getSchema()
    {
        $publisher      = new \simialbi\yii2\schemaorg\models\Organization();
        $publisher->name    = $this->nameSurname;
        \simialbi\yii2\schemaorg\helpers\JsonLDHelper::add($author);
        return \simialbi\yii2\schemaorg\helpers\JsonLDHelper::render();
    }

    
    /**
     * Method to set AgidOrganizationalUnitServiceMm
     *
     * @return void
     */
    public function createAgidOrganizationalUnitServicesMm(){

        $post_request = \Yii::$app->request->post();

        if( isset($post_request['AgidOrganizationalUnit']['agid_organizational_unit_service_mm']) ){

            foreach ($post_request['AgidOrganizationalUnit']['agid_organizational_unit_service_mm'] as $key => $value) {
                
                // $this->deleteAgidOrganizationalUnitServiceMm();

                $agid_organizational_unit_service_mm = new AgidOrganizationalUnitServiceMm;

                $agid_organizational_unit_service_mm->agid_organizational_unit_id = $this->id;
                $agid_organizational_unit_service_mm->agid_service_id = $value;

                $agid_organizational_unit_service_mm->save();
            }
        }
    }

    /**
     * Method to update AgidOrganizationalUnitServiceMm
     *
     * @return void
     */
    public function updateAgidOrganizationalUnitServiceMm(){

        $this->deleteAgidOrganizationalUnitServiceMm();

        $this->createAgidOrganizationalUnitServicesMm();
    }

    /**
     * Method to delete all AgidOrganizationalUnitService
     *
     * @return void
     */
    protected function deleteAgidOrganizationalUnitServiceMm(){

        // delete all AgidOrganizationalUnitServiceMm
        foreach ($this->agidOrganizationalUnitServicesMm as $key => $agid_organizational_unit_service_mm) {

            $agid_organizational_unit_service_mm->delete();
        }
    }

    /**
     * Method to return UserProfile by user_id
     *
     * @param int $id
     * @return void
     */
    public function getUserProfileByUserId($id = null){

        return UserProfile::find()->andWhere(['user_id' => $id])->one();
    }
}
