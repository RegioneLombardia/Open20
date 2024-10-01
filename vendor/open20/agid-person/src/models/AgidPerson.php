<?php

namespace open20\agid\person\models;

use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\amos\attachments\behaviors\FileBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\amos\core\interfaces\ViewModelInterface;
use yii\helpers\Url;


/**
 * This is the model class for table "agid_person".
 *
 * @property-read string $nameSurname
 * @property-read string $surnameName
 */
class AgidPerson extends \open20\agid\person\models\base\AgidPerson
{
    public $useFrontendView = false;

	    
    // Workflow ID
    const AGID_PERSON_WORKFLOW = 'AgidPersonWorkflow';
    // Workflow states IDS
    const AGID_PERSON_STATUS_DRAFT = "AgidPersonWorkflow/DRAFT";
    const AGID_PERSON_STATUS_VALIDATED = "AgidPersonWorkflow/VALIDATED";

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'agid_person_content_type_id',
                'label' => $labels['agid_person_content_type_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_person_type_id',
                'label' => $labels['agid_person_type_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_cv_id',
                'label' => $labels['agid_document_cv_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_import_id',
                'label' => $labels['agid_document_import_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_other_posts_id',
                'label' => $labels['agid_document_other_posts_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_nomination_id',
                'label' => $labels['agid_document_nomination_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_balance_sheet_id',
                'label' => $labels['agid_document_balance_sheet_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_tax_return_id',
                'label' => $labels['agid_document_tax_return_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_election_expenses_id',
                'label' => $labels['agid_document_election_expenses_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_document_changes_balance_sheet_id',
                'label' => $labels['agid_document_changes_balance_sheet_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'surname',
                'label' => $labels['surname'],
                'type' => 'string'
            ],
            [
                'slug' => 'role',
                'label' => $labels['role'],
                'type' => 'string'
            ],
            [
                'slug' => 'role_description',
                'label' => $labels['role_description'],
                'type' => 'text'
            ],
            [
                'slug' => 'date_end_assignment',
                'label' => $labels['date_end_assignment'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'skills',
                'label' => $labels['skills'],
                'type' => 'text'
            ],
            [
                'slug' => 'delegation',
                'label' => $labels['delegation'],
                'type' => 'text'
            ],
            [
                'slug' => 'date_start_settlement',
                'label' => $labels['date_start_settlement'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'bio',
                'label' => $labels['bio'],
                'type' => 'text'
            ],
            [
                'slug' => 'telephone',
                'label' => $labels['telephone'],
                'type' => 'string'
            ],
            [
                'slug' => 'email',
                'label' => $labels['email'],
                'type' => 'string'
            ],
            [
                'slug' => 'other_info',
                'label' => $labels['other_info'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self:: AGID_PERSON_WORKFLOW)->getInitialStatusId();
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
                'defaultWorkflowId' => self:: AGID_PERSON_WORKFLOW,
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
                'imageAttribute' => 'photo',
                'titleAttribute' => 'name_surname',
                'descriptionAttribute' => 'bio',
                'defaultOgType' => 'person',
                'schema' => 'Person'
            ]
        ]);
    }
    
    /**
     * Metodo beforeSave for set column name_surname
     *
     * @param boolean $insert
     * @return void
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // set name_surname attribute
        $this->name_surname = $this->getNomecognome();

        return true;
    }


    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'surname',
            'name'
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
    
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
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
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }
    
    /**
     * @param $model MktVersioneApi
     */
    public function createRelations()
    {
        $model = $this;
        foreach ((Array)$model->manager_org as $manager_id) {

            $referenceorgnn = new AgidPersonOrganizationalUnitMm();
            $referenceorgnn->agid_person_id = $model->id;
            $referenceorgnn->agid_organizational_unit_id = $manager_id;
            $referenceorgnn->role = AgidPerson::ROLE_MANAGER;
            //$referenceorgnn->function='';
            $referenceorgnn->save();
        }
    }

    /**
     * @param $model MktVersioneApi
     * @return mixed
     */
    public function loadRelations()
    {
        $model = $this;
        foreach ((Array)$model->getAgidPersonOrganizationalUnitMmsManager()->all() as $ManagerNnId) {
            $model->manager_org [] = $ManagerNnId->agid_organizational_unit_id;
        }
        
        return $model;
    }

    /**
     * @param $model MktVersioneApi
     */
    public function updateRelations()
    {
        $model = $this;
        AgidPersonOrganizationalUnitMm::deleteAll(['agid_person_id' => $model->id]);
        $model->createRelations();
    }
    
    /**
     * Returns the name and surname
     * @return string
     */
    public function getNameSurname()
    {
        return $this->name . ' ' . $this->surname;
    }
    
    /**
     * Returns the surname and name
     * @return string
     */
    public function getSurnameName()
    {
        return $this->surname . ' ' . $this->name;
    }
    
    
    /**
     * @return AgidPersonGrammar|mixed
     */
    public function getGrammar()
    {
        
        return new \open20\agid\person\i18n\grammar\AgidPersonGrammar();
        
    }

    /**
     *
     * @return type
     */
    public function getSchema()
    {
        $author      = new \simialbi\yii2\schemaorg\models\Person();
        $author->name    = $this->nameSurname;
        \simialbi\yii2\schemaorg\helpers\JsonLDHelper::add($author);
        return \simialbi\yii2\schemaorg\helpers\JsonLDHelper::render();
    }

    /**
     * Returns the name and surname
     * @return string
     */
    public function getNomecognome()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::AGID_PERSON_STATUS_VALIDATED;
    }

    /**
     * get model file for photo
     *
     * @return void
     */
    public function getPhoto(){

        $photo = $this->hasOneFile('photo')->one();
        return $photo;
    }



     /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        if (!empty($this->usePrettyUrl) && ($this->usePrettyUrl == true)) {
            return Url::toRoute(["/" . $this->getViewUrl() . "/" . $this->id . "/" . $this->getPrettyUrl()]);
        } else if (
                !empty($this->useFrontendView)
                && ($this->useFrontendView == true)
                && method_exists($this, 'getBackendobjectsUrl')
            ) {
            return \Yii::$app->params['platform']['frontendUrl'] . $this->getBackendobjectsUrl();
        } else {
            return $this->getBasicFullUrl($this->getViewUrl());
        }
    }

    /**
     *
     * @return type
     */
    public function getFullFrontendViewUrl()
    {
        $url = $this->getFrontendViewUrl();
        if (strpos($url, '{Id}')) {
            $url = str_replace("{Id}", $this->id, $url);
        }

        if (strpos($url, '{Slug}')) {
            $url = str_replace("{Slug}", $this->slug, $url);
        }

        return Url::toRoute(['/'.$url]);
    }



}
