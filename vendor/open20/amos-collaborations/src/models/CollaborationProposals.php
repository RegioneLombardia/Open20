<?php

namespace open20\amos\collaborations\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\collaborations\events\CollaborationProposalsWorkflowEvent;
use open20\amos\collaborations\events\CollaborationProposalsEvents;
use open20\amos\collaborations\i18n\grammar\CollaborationProposalsGrammar;
use open20\amos\collaborations\Module;
use open20\amos\comments\models\CommentInterface;
use open20\amos\core\helpers\StringHelper;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ContentPublicationInteraface;
use open20\amos\core\interfaces\CustomUrlModelInterface;
use open20\amos\core\interfaces\ModelImageInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\seo\interfaces\SeoModelInterface;
use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "collaboration_proposals".
 *
 * @property-read array $gridViewColumns
 * @property-read null|string $iconMarker
 * @property-read \open20\amos\collaborations\models\url|null $urlEvent
 * @property-read \open20\amos\collaborations\models\color|null $colorEvent
 * @property-read string[] $workflowLabel
 * @property-read null|\open20\amos\collaborations\models\title $titleEvent
 * @property-read null|array $events
 */
class CollaborationProposals
    extends \open20\amos\collaborations\models\base\CollaborationProposals
    implements ContentModelInterface, ViewModelInterface, ContentPublicationInteraface
{

    public $ipr_status_mm;
    public $partnership_type_mm;
    public $partner_type_and_size_mm;
    public $target_countries_mm;
    public $market_keywords_mm;
    public $technology_keywords_mm;
    public $start_date_from;
    public $start_date_to;
    public $end_date_from;
    public $end_date_to;

    /**
     *
     * @var CollaborationProposals $moduleCollaborationProposals
     */
    protected $moduleCollaborationProposals;

    /**
     *
     * @var bool $usePrettyUrl
     */
    protected $usePrettyUrl;

    // WORKFLOW NAME
    const WORKFLOW = 'CollaborationProposalsWorkflow';

    // WORKFLOW STATUSES
    const STATUS_DRAFT = 'CollaborationProposalsWorkflow/DRAFT';
    const STATUS_PUBLISHED = 'CollaborationProposalsWorkflow/PUBLISHED';
    const STATUS_CLOSED = 'CollaborationProposalsWorkflow/CLOSED';
    const STATUS_TOVALIDATE = 'CollaborationProposalsWorkflow/TOVALIDATE';
    const STATUS_REFUSED = 'CollaborationProposalsWorkflow/REFUSED';

    // CATEGORIES
    const CATEGORY_FROM_PLATFORM = 1;
    const CATEGORY_FROM_WORLD = 2;

    // VISIBILITY
    const VISIBLE_TO_GUEST = 1;
    const NOT_VISIBLE_TO_GUEST = 0;

    // EVIDENCE
    const IN_EVIDENCE = 1;
    const NOT_IN_EVIDENCE = 0;

    // ATTRIBUTES MAX LENGTH
    const SHORT_SUMMARY_MAX_LENGTH = 500;
    const FULL_DESCRIPTION_MAX_LENGTH = 4000;
    const ADVANTAGES_MAX_LENGTH = 1000;
    const TECHNICAL_SPECIFICATIONS_MAX_LENGTH = 1000;
    const PARTNER_ROLE_MAX_LENGTH = 4000;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::WORKFLOW)->getInitialStatusId();
        }

        //$this->on('afterEnterStatus{' . self::STATUS_PUBLISHED . '}', [new CollaborationProposalsWorkflowEvent(), 'notifyNewProposal'], $this);

        $this->on('afterChangeStatusFrom{'
            . self::STATUS_DRAFT
            . '}to{'
            . self::STATUS_TOVALIDATE
            .'}',
            [new CollaborationProposalsEvents(),
                'sendToValidateNotificationEmails'], $this
        );

        $this->on('afterChangeStatusFrom{'
            . self::STATUS_TOVALIDATE
            . '}to{'
            . self::STATUS_REFUSED
            .'}',
            [new CollaborationProposalsEvents(),
                'sendRefusedNotificationEmails'], $this
        );
        
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

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' =>
                    [
                        'class' => FileBehavior::className()
                    ],
                'NotifyBehavior' => [
                    'class' => NotifyBehavior::className(),
                    'conditions' => []
                ],
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::className(),
                    'titleAttribute' => 'title',
                    'descriptionAttribute' => 'full_description',
                    'imageAttribute' => 'image',
                    'defaultOgType' => 'article',
                    'schema' => 'NewsArticle'
                ]
            ]
        );
    }

    public function rules()
    {

        $rules = ArrayHelper::merge(
            parent::rules(),
            [
                [['partnership_type_mm',], 'required'],
                [['image'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 4],
                [['pdf'], 'file', 'maxFiles' => 1, 'extensions' => 'pdf'],
                [['ipr_status_mm', 'partnership_type_mm', 'partner_type_and_size_mm', 'market_keywords_mm', 'technology_keywords_mm','target_countries_mm',], 'safe'],
                ['image', 'imageValidator', 'skipOnEmpty' => false, 'skipOnError' => false]
            ]);
            
            $rules = ArrayHelper::merge(
                $rules,
                [
                    ['start_date', 'compare', 'compareAttribute' => 'end_date', 'operator' => '<=',
                        'when' => function($model) {
                            if(!empty($model->start_date) && !empty($model->end_date)){
                                return true;
                            }
                            return false;
                        },
                        'whenClient' => "function (attribute, value) {
                            return Boolean($('#collaborationProposals-start_date0-disp').val() && $('#collaborationProposals-end_date0-disp').val());

                        }"],
                    ['end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>=',
                        'when' => function($model) {
                            if(!empty($model->start_date) && !empty($model->end_date)){
                                return true;
                            }
                            return false;
                        },
                        'whenClient' => "function (attribute, value) {
                            return Boolean($('#collaborationProposals-start_date0-disp').val() && $('#collaborationProposals-end_date0-disp').val());

                        }"],
                ]
            );

            return $rules;
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(), [
                'image' => \Yii::t('amoscollaborations', 'Immagine'),
                'ipr_status_mm' => \Yii::t('amoscollaborations', 'Proprietà industriale'),
                'partnership_type_mm' => \Yii::t('amoscollaborations', 'Tipo di partnership'),
                'partner_type_and_size_mm' => \Yii::t('amoscollaborations', 'Tipo e dimensione del partner cercato'),
                'target_countries_mm' => \Yii::t('amoscollaborations', 'Paesi interessati'),
                //'market_keywords_mm' => \Yii::t('amoscollaborations', 'Mercati di sbocco'),
                //'technology_keywords_mm' => \Yii::t('amoscollaborations', 'Tecnologie impiegate')
            ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'category_id',
                'label' => $labels['category_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'collaboration_type_id',
                'label' => $labels['collaboration_type_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'short_summary',
                'label' => $labels['short_summary'],
                'type' => 'text'
            ],
            [
                'slug' => 'full_description',
                'label' => $labels['full_description'],
                'type' => 'text'
            ],
            [
                'slug' => 'advantages',
                'label' => $labels['advantages'],
                'type' => 'text'
            ],
            [
                'slug' => 'technical_specifications',
                'label' => $labels['technical_specifications'],
                'type' => 'text'
            ],
            [
                'slug' => 'stage_of_development_id',
                'label' => $labels['stage_of_development_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'partner_role',
                'label' => $labels['partner_role'],
                'type' => 'text'
            ],
            [
                'slug' => 'caption',
                'label' => $labels['caption'],
                'type' => 'string'
            ],
            [
                'slug' => 'short_abstract',
                'label' => $labels['short_abstract'],
                'type' => 'string'
            ],
            [
                'slug' => 'video_pitch',
                'label' => $labels['video_pitch'],
                'type' => 'string'
            ],
            [
                'slug' => 'video_title',
                'label' => $labels['video_title'],
                'type' => 'string'
            ],
            [
                'slug' => 'start_date',
                'label' => $labels['start_date'],
                'type' => 'date'
            ],
            [
                'slug' => 'end_date',
                'label' => $labels['end_date'],
                'type' => 'date'
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
     *
     */
    public function saveMmTables()
    {
        $this->saveIprStatusMm();
        $this->savePartnershipTypeMm();
        $this->savePartnerTypeAndSizeMm();
        $this->saveTargetCountriesMm();
        //$this->saveMarketKeywordsMm();
        //$this->saveTechnologyKeywordsMm();
    }

    /**
     *
     */
    public function saveIprStatusMm()
    {
        CollaborationIprStatusMm::deleteAll(['collaboration_proposals_id' => $this->id]);
        //pr(implode(',', $this->ipr_status_mm)); die;
        foreach ((array)$this->ipr_status_mm as $ipr_status_mm_id) {
            $iprStatusMm = new CollaborationIprStatusMm();
            $iprStatusMm->collaboration_ipr_status_id = $ipr_status_mm_id;
            $iprStatusMm->collaboration_proposals_id = $this->id;
            if (!empty($ipr_status_mm_id)) {
                $iprStatusMm->save(false);
            }
        }
    }

    /**
     *
     */
    public function savePartnershipTypeMm()
    {
        CollaborationPartnershipTypeMm::deleteAll(['collaboration_proposals_id' => $this->id]);
        foreach ((array)$this->partnership_type_mm as $partnership_type_mm_id) {
            $partnershipTypeMm = new CollaborationPartnershipTypeMm();
            $partnershipTypeMm->collaboration_partnership_type_id = $partnership_type_mm_id;
            $partnershipTypeMm->collaboration_proposals_id = $this->id;
            if (!empty($partnership_type_mm_id)) {
                $partnershipTypeMm->save(false);
            }
        }
    }

    /**
     *
     */
    public function savePartnerTypeAndSizeMm()
    {
        CollaborationPartnerTypeAndSizeMm::deleteAll(['collaboration_proposals_id' => $this->id]);
        foreach ((array)$this->partner_type_and_size_mm as $partner_type_and_size_mm_id) {
            $partnerTypeAndSizeMm = new CollaborationPartnerTypeAndSizeMm();
            $partnerTypeAndSizeMm->collaboration_partner_type_and_size_id = $partner_type_and_size_mm_id;
            $partnerTypeAndSizeMm->collaboration_proposals_id = $this->id;
            if (!empty($partner_type_and_size_mm_id)) {
                $partnerTypeAndSizeMm->save(false);
            }
        }
    }

    /**
     *
     */
    public function saveMarketKeywordsMm()
    {
        CollaborationMarketKeywordsMm::deleteAll(['collaboration_proposals_id' => $this->id]);
        foreach ((array)$this->market_keywords_mm as $market_keywords_mm_id) {
            $marketKeywordsMm = new CollaborationMarketKeywordsMm();
            $marketKeywordsMm->collaboration_market_keywords_id = $market_keywords_mm_id;
            $marketKeywordsMm->collaboration_proposals_id = $this->id;
            if (!empty($market_keywords_mm_id)) {
                $marketKeywordsMm->save(false);
            }
        }
    }

    /**
     *
     */
    public function saveTargetCountriesMm()
    {
        CollaborationTargetCountriesMm::deleteAll(['collaboration_proposals_id' => $this->id]);
        foreach ((array)$this->target_countries_mm as $target_countries_mm_id) {
            $targetCountriesMm = new CollaborationTargetCountriesMm();
            $targetCountriesMm->collaboration_target_countries_id = $target_countries_mm_id;
            $targetCountriesMm->collaboration_proposals_id = $this->id;
            if (!empty($target_countries_mm_id)) {
                $targetCountriesMm->save(false);
            }
        }
    }

    /**
     *
     */
    public function saveTechnologyKeywordsMm()
    {
        CollaborationTechnologyKeywordsMm::deleteAll(['collaboration_proposals_id' => $this->id]);
        foreach ((array)$this->technology_keywords_mm as $technology_keywords_mm_id) {
            $technologyKeywordsMm = new CollaborationTechnologyKeywordsMm();
            $technologyKeywordsMm->collaboration_technology_keywords_id = $technology_keywords_mm_id;
            $technologyKeywordsMm->collaboration_proposals_id = $this->id;
            if (!empty($technology_keywords_mm_id)) {
                $technologyKeywordsMm->save(false);
            }
        }
    }

    /**
     *
     */
    public function loadMmTablesValues()
    {
        foreach ($this->getCollaborationIprStatusMms()->asArray()->all() as $iprStatus) {
            $this->ipr_status_mm[] = $iprStatus['collaboration_ipr_status_id'];
        }
        foreach ($this->getCollaborationPartnershipTypeMms()->asArray()->all() as $partnershipType) {
            $this->partnership_type_mm[] = $partnershipType['collaboration_partnership_type_id'];
        }
        foreach ($this->getCollaborationPartnerTypeAndSizeMms()->asArray()->all() as $partnerTypeAndSize) {
            $this->partner_type_and_size_mm[] = $partnerTypeAndSize['collaboration_partner_type_and_size_id'];
        }
        foreach ($this->getCollaborationTargetCountriesMms()->asArray()->all() as $targetCountries) {
            $this->target_countries_mm[] = $targetCountries['collaboration_target_countries_id'];
        }

        /*foreach ($this->collaborationMarketKeywordsMms as $marketKeywords) {
            $this->market_keywords_mm[] = $marketKeywords->collaboration_market_keywords_id;
        }
        foreach ($this->collaborationTechnologyKeywordsMms as $techKeywords) {
            $this->technology_keywords_mm[] = $techKeywords->collaboration_technology_keywords_id;
        }*/
    }

    /**
     * @inheritdoc
     */
    public function updateRelatedExpressionsOfInterest($id, $title)
    {
        CollaborationExpressionsOfInterest::updateAll(
            [
                'proposal_title' => $title
            ],
            ['collaboration_proposal_id' => $id]);

    }


    public function getWorkflowStatusLabel()
    {
        return [
            self::STATUS_DRAFT => \Yii::t('amoscollaborations', 'Bozza'),
            self::STATUS_PUBLISHED => \Yii::t('amoscollaborations',
                'Pubblicata'),
            self::STATUS_CLOSED => \Yii::t('amoscollaborations', 'Chiusa'),
            self::STATUS_TOVALIDATE => \Yii::t('amoscollaborations',
                '#to_validate'),
            self::STATUS_REFUSED => \Yii::t('amoscollaborations', '#refused'),
        ];
    }

    public function getCategoryLabel()
    {
        $ret = [];
        $module = Module::getInstance();
        
        if(!is_null($module->categoriesCustom) && is_array($module->categoriesCustom))
        {
            $ret = $module->categoriesCustom;
        }
        else
        {
            $ret =  [
                self::CATEGORY_FROM_PLATFORM => \Yii::t('amoscollaborations', 'Dalla piattaforma'),
                self::CATEGORY_FROM_WORLD => \Yii::t('amoscollaborations', 'Dal mondo')
            ];
        }
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'title',
            [
                'label' => $this->getAttributeLabel('status'),
                'value' => function ($model) {
                    return $model->getWorkflowStatusLabel()[$model->status];
                }
            ],
            [
                'attribute' => 'short_summary',
                'format' => 'html',
                'label' => \Yii::t('amoscollaborations', 'Short summary')
            ],
            'start_date',
            'end_date',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn'
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        return $this->short_summary;
    }

    /**
     * @return CollaborationProposalsGrammar
     */
    public function getGrammar()
    {
        return new CollaborationProposalsGrammar();
    }

    /**
     *
     * @return bool
     */
    public function getUsePrettyUrl()
    {
        return $this->usePrettyUrl;
    }

    /**
     *
     * @param bool $usePrettyUrl
     */
    public function setUsePrettyUrl($usePrettyUrl)
    {
        $this->usePrettyUrl = $usePrettyUrl;
    }

    /**
     *
     * @return CollaborationProposals
     */
    public function getModuleCollaborationProposals()
    {
        return $this->moduleCollaborationProposals;
    }

    /**
     *
     * @param CollaborationProposals $moduleCollaborationProposals
     */
    public function setModuleCollaborationProposals($moduleCollaborationProposals)
    {
        $this->moduleCollaborationProposals = $moduleCollaborationProposals;
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        if (!empty($this->usePrettyUrl) && ($this->usePrettyUrl == true) && $this->hasMethod('getPrettyUrl')) {
            return Module::getModuleName() . '/collaboration-proposals';
        }

        //return Module::getModuleName() . '/' . Yii::$app->controller->id . '/view';
        return Module::getModuleName() . '/collaboration-proposals/view';
    }

    /**
     * @return string The url to view of this model
     */
    public function getFullViewUrl()
    {
        if (!empty($this->usePrettyUrl) && ($this->usePrettyUrl == true) && $this->hasMethod('getPrettyUrl')) {
            return Url::toRoute(["/" . $this->getViewUrl() . "/" . $this->id . "/" . $this->getPrettyUrl()]);
        } else if (!empty($this->useFrontendView) && ($this->useFrontendView == true) && $this->hasMethod('getBackendobjectsUrl')) {
            return $this->getBackendobjectsUrl();
        } else {
            return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
        }
    }

    public function imageValidator()
    {
        if (!empty(Yii::$app->request->post()['image_data'])) {
            if (empty($this->caption)) {
                if (strpos(Url::current(), 'create')) {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
                }
                $this->addError('caption', \Yii::t('amoscollaborations', 'Se viene inserita un\'immagine il campo non può essere vuoto'));
            }
        }
    }

    public function getExpressionsOfInterestStatesCounter()
    {
        CollaborationExpressionsOfInterest::find()
            ->andWhere(['collaboration_proposal_id' => $this->id])
            ->count();
    }

    /**
     * Method for ContentPublicationInteraface
     * @return bool
     */
    public function isContentPublic()
    {
        if ($this->status == self::STATUS_PUBLISHED && $this->visible_to_guest == self::VISIBLE_TO_GUEST) {
            return true;
        }
        return false;
    }
    
    /**
     * Verifiche 'comuni' riguardanti la possibilita' per l'utente loggato di
     * effettuare l'azione il cui 'tipo' e' passato al metodo
     * @param string $action Tipo dell'azione da verificare (update, delete,
     *  ...)
     * @return bool Restituisce true se l'utente puo' eseguire l'azione o false
     * altrimenti
     */
    protected function userCanCommonCheck($action)
    {
        if (empty($action)) {
            return false;
        }

        if (empty($this->id)) {
            return false;
        }

        if ((empty(Yii::$app->user)) || (empty(Yii::$app->user->id))
            || (Yii::$app->user->isGuest)) {
            return false;
        }

        $realModelClassName = self::class;
        $mappedActionId = $action;
        $options = ['model' => $this];
        $permission1 = $realModelClassName . '_' . strtoupper($mappedActionId);
        $permission2 = strtoupper(StringHelper::basename($realModelClassName))
            . '_' . strtoupper($mappedActionId);
        if ((!Yii::$app->getUser()->can($permission1, $options))
            && (!Yii::$app->getUser()->can($permission2, $options))) {
            return false;
        }
        
        if (Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR')) {
            return true;
        }

        if (!Yii::$app->user->can('COLLABORATIONS_STAFF')) {
            return false;
        }
        
        if (/*(Yii::$app->user->can('COLLABORATIONS_STAFF'))
            && */(Yii::$app->user->id != $this->created_by)) {
            return false;
        }
        return true;
    }

    /**
     * Verifica se l'utente loggato possa aggiornare la proposta di
     * collaborazione
     * @return bool Restituisce true se l'utente puo' eseguire l'azione o false
     * altrimenti
     */
    public function userCanUpdate()
    {
        if (!$this->userCanCommonCheck('update')) {
            return false;
        }
        return true;
    }

    /**
     * Verifica se l'utente loggato possa cancellare la proposta di
     * collaborazione
     * @return bool Restituisce true se l'utente puo' eseguire l'azione o false
     * altrimenti
     */
    public function userCanDelete()
    {
        //verifiche standard
        if (!$this->userCanCommonCheck('delete')) {
            return false;
        }
        return true;
    }
}
