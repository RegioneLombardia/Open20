<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\models
 * @category   CategoryName
 */

namespace open20\agid\service\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use open20\agid\service\Module;
use open20\amos\attachments\models\File;
use simialbi\yii2\schemaorg\helpers\JsonLDHelper;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\agid\service\models\AgidServiceDocumentiMm;
use simialbi\yii2\schemaorg\models\ProfessionalService;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\agid\service\i18n\grammar\AgidServiceGrammar;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\agid\service\models\AgidServiceRelatedServiceMm;
use cornernote\workflow\manager\components\WorkflowDbSource;
use open20\agid\service\models\AgidServiceOrganizationalUnitMm;
use open20\agid\service\models\base\AgidService as BaseAgidService;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;


/**
 * Class AgidService
 * This is the model class for table "agid_service".
 *
 * @method WorkflowDbSource getWorkflowSource()
 *
 * @package open20\agid\service\models
 */
class AgidService extends BaseAgidService
{
    // Workflow ID
    const AGID_SERVICE_WORKFLOW = 'AgidServiceWorkflow';
    // Workflow states IDS
    const AGID_SERVICE_STATUS_DRAFT = "AgidServiceWorkflow/DRAFT";
    const AGID_SERVICE_STATUS_VALIDATED = "AgidServiceWorkflow/VALIDATED";
    
    /**
     * @var $agidServiceImage
     */
    private $agidServiceImage;
    
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::AGID_SERVICE_WORKFLOW)->getInitialStatusId();
        }
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['agidServiceImage'], 'file', 'extensions' => 'jpeg, jpg, png, gif'],
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'workflow' => [
                'class' => SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => self:: AGID_SERVICE_WORKFLOW,
                'propagateErrorsToModel' => true,
            ],
            'workflowLog' => [
                'class' => WorkflowLogFunctionsBehavior::className(),
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
            'SeoContentBehavior' => [
                'class' => SeoContentBehavior::className(),
                'imageAttribute' => null,
                'titleAttribute' => 'name',
                'descriptionAttribute' => 'description',
                'defaultOgType' => 'professionalservice',
                'schema' => 'ProfessionalService'
            ]
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'agidServiceImage' => Module::t('amosservice', '#agid_service_image'),
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }
    
    public static function getEditFields()
    {
        $labels = self::attributeLabels();
        
        return [
            [
                'slug' => 'agid_content_type_service_id',
                'label' => $labels['agid_content_type_service_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_service_type_id',
                'label' => $labels['agid_service_type_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_service_status_id',
                'label' => $labels['agid_service_status_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_uo_manager_id',
                'label' => $labels['agid_uo_manager_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_uo_area_id',
                'label' => $labels['agid_uo_area_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'service_status_motivation',
                'label' => $labels['service_status_motivation'],
                'type' => 'text'
            ],
            [
                'slug' => 'subtitle',
                'label' => $labels['subtitle'],
                'type' => 'text'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'long_description',
                'label' => $labels['long_description'],
                'type' => 'text'
            ],
            [
                'slug' => 'recipients_description',
                'label' => $labels['recipients_description'],
                'type' => 'text'
            ],
            [
                'slug' => 'persons_apply',
                'label' => $labels['persons_apply'],
                'type' => 'text'
            ],
            [
                'slug' => 'geographical_apply',
                'label' => $labels['geographical_apply'],
                'type' => 'text'
            ],
            [
                'slug' => 'procedure_apply',
                'label' => $labels['procedure_apply'],
                'type' => 'text'
            ],
            [
                'slug' => 'output',
                'label' => $labels['output'],
                'type' => 'text'
            ],
            [
                'slug' => 'outcome_procedure_apply',
                'label' => $labels['outcome_procedure_apply'],
                'type' => 'text'
            ],
            [
                'slug' => 'digital_channel_url',
                'label' => $labels['digital_channel_url'],
                'type' => 'text'
            ],
            [
                'slug' => 'authentication_way',
                'label' => $labels['authentication_way'],
                'type' => 'text'
            ],
            [
                'slug' => 'physical_channel',
                'label' => $labels['physical_channel'],
                'type' => 'text'
            ],
            [
                'slug' => 'physical_channel_reservation',
                'label' => $labels['physical_channel_reservation'],
                'type' => 'text'
            ],
            [
                'slug' => 'instructions',
                'label' => $labels['instructions'],
                'type' => 'text'
            ],
            [
                'slug' => 'costs',
                'label' => $labels['costs'],
                'type' => 'text'
            ],
            [
                'slug' => 'constrains',
                'label' => $labels['constrains'],
                'type' => 'text'
            ],
            [
                'slug' => 'phases_deadline',
                'label' => $labels['phases_deadline'],
                'type' => 'text'
            ],
            [
                'slug' => 'special_case',
                'label' => $labels['special_case'],
                'type' => 'text'
            ],
            [
                'slug' => 'external_links',
                'label' => $labels['external_links'],
                'type' => 'text'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
        ];
    }
    
    /**
     * Getter for $this->agidServiceImage;
     * @return ActiveQuery
     */
    public function getAgidServiceImage()
    {
        if (empty($this->agidServiceImage)) {
            $this->agidServiceImage = $this->hasOneFile('agidServiceImage')->one();
        }
        return $this->agidServiceImage;
    }
    
    /**
     * @param $image
     */
    public function setAgidServiceImage($image)
    {
        $this->agidServiceImage = $image;
    }
    
    /**
     * @param $model MktVersioneApi
     */
    public function createRelations()
    {
        $model = $this;
        foreach ((array)$model->documenti as $documenti_id) {
            
            $documentinn = new AgidServiceDocumentiMm();
            $documentinn->agid_service_id = $model->id;
            $documentinn->documenti_id = $documenti_id;
            $documentinn->save();
        }

        // create new AGID SERVICE RELATED SERVICE MM
        $agid_service_related_service_mm = Yii::$app->request->post('AgidService')['agid_service_related_service_mm'];

        foreach ($agid_service_related_service_mm as $key => $value) {

            $agid_service_related_service = new AgidServiceRelatedServiceMm();
            $agid_service_related_service->agid_service_id = $model->id;
            $agid_service_related_service->agid_related_service_id = $value;

            $agid_service_related_service->save(false);
        }

        // create new agid_service_organizational_unit_mm
        $agid_service_organizational_unit_mms = Yii::$app->request->post('AgidService')['agid_service_organizational_unit_mm'];

        foreach ($agid_service_organizational_unit_mms as $key => $value) {
            
            $agid_service_organizational_unit_mm = new AgidServiceOrganizationalUnitMm();
            $agid_service_organizational_unit_mm->agid_service_id = $model->id;
            $agid_service_organizational_unit_mm->agid_organizational_unit_id = $value;

            $agid_service_organizational_unit_mm->save(false);
        }
    }
    
    /**
     * @param $model MktVersioneApi
     * @return mixed
     */
    public function loadRelations()
    {
        $model = $this;
        foreach ((array)$model->getAgidServiceDocumentiMms()->all() as $DocumentiNnId) {
            $model->documenti [] = $DocumentiNnId->documenti_id;
        }
        
        return $model;
    }
    
    /**
     * @param $model MktVersioneApi
     */
    public function updateRelations()
    {
        $model = $this;
        AgidServiceDocumentiMm::deleteAll(['agid_service_id' => $model->id]);

        // remove all agid_service_related_service_mm
        AgidServiceRelatedServiceMm::deleteAll(['agid_service_id' => $model->id]);

        // remove all agid_service_organizational_unit_mm
        AgidServiceOrganizationalUnitMm::deleteAll(['agid_service_id' => $model->id]);

        $model->createRelations();
    }
    
    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "service/agid-service/view";
    }
    
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->name;
    }
    
    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->description;
    }
    
    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->long_description;
        if ($truncate) {
            $ret = $this->__shortText($this->long_description, 200);
        }
        return $ret;
    }
    
    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::AGID_SERVICE_STATUS_DRAFT;
    }
    
    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return '';
    }
    
    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::AGID_SERVICE_STATUS_VALIDATED;
    }
    
    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'ADMIN';
    }
    
    /**
     * @return AgidServiceGrammar
     */
    public function getGrammar()
    {
        return new AgidServiceGrammar();
    }
    
    /**
     * @inheritdoc
     */
    public function getCwhValidationStatuses()
    {
        return [self::AGID_SERVICE_STATUS_VALIDATED];
    }
    
    /**
     * @inheritdoc
     */
    public function getWorkflowStatusLabel()
    {
        return Module::t('amosservice', parent::getWorkflowStatusLabel());
    }
    
    /**
     * @inheritdoc
     */
    public function getModelImage()
    {
        return $this->getAgidServiceImage();
    }
    
    /**
     * @inheritdoc
     */
    public function getModelImageUrl($size = 'original', $protected = true, $url = '/img/img_default.jpg', $absolute = false, $canCache = false)
    {
        /** @var File $modelImage */
        $modelImage = $this->getModelImage();
        if (!is_null($modelImage)) {
            if ($protected) {
                $url = $modelImage->getUrl($size, $absolute, $canCache);
            } else {
                $url = $modelImage->getWebUrl($size, $absolute, $canCache);
            }
        }
        return $url;
    }
    
    /**
     * @return string
     */
    public function getModelImageUrlForSummaries()
    {
        return $this->getModelImageUrl('square_large', true, '/img/img_default.jpg', true, true);
    }

    /**
     * 
     * @return string
     */
    public function getSchema() 
    {
        $professionalService      = new ProfessionalService;
        $professionalService->legalName    = $this->name;
        JsonLDHelper::add($professionalService);
        return JsonLDHelper::render();
        
    }

}
