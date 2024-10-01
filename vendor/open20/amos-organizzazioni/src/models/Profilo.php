<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityContextInterface;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\exceptions\AmosException;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\interfaces\OrganizationsModelInterface;
use open20\amos\core\user\AmosUser;
use open20\amos\core\user\User;
use open20\amos\core\validators\CfPivaValidator;
use open20\amos\core\validators\PIVAValidator;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\organizzazioni\components\OrganizationsPlacesComponents;
use open20\amos\organizzazioni\controllers\ProfiloController;
use open20\amos\organizzazioni\i18n\grammar\ProfiloGrammar;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo;
use open20\amos\organizzazioni\widgets\ProfiloCardWidget;
use open20\amos\organizzazioni\widgets\UserNetworkWidget;
use open20\amos\organizzazioni\widgets\UserNetworkWidgetOrganizzazioni;
use raoul2000\workflow\base\Status;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\amos\community\utilities\CommunityUtil;
use yii\base\Event;


/**
 * Class Profilo
 * This is the model class for table "profilo".
 *
 * @property \open20\amos\organizzazioni\models\ProfiloSediOperative $operativeHeadquarter
 * @property \open20\amos\organizzazioni\models\ProfiloSediLegal $legalHeadquarter
 * @property \open20\amos\organizzazioni\models\OrganizationsPlaces $sedeIndirizzo
 * @property \open20\amos\organizzazioni\models\OrganizationsPlaces $sedeLegaleIndirizzo
 *
 * @method \cornernote\workflow\manager\components\WorkflowDbSource getWorkflowSource()
 * @method bool sendToStatus(Status|string $status)
 *
 * @package open20\amos\organizzazioni\models
 */
class Profilo extends \open20\amos\organizzazioni\models\base\Profilo implements OrganizationsModelInterface, CommunityContextInterface
{
    const ORGANIZZAZIONI_MANAGER = 'ORGANIZZAZIONI_MANAGER';
    const ORGANIZZAZIONI_PARTICIPANT = 'ORGANIZZAZIONI_PARTICIPANT';

    // Workflow ID
    const PROFILO_WORKFLOW = 'ProfiloWorkflow';

    // Workflow states IDS
    const PROFILO_WORKFLOW_STATUS_DRAFT = 'ProfiloWorkflow/DRAFT';
    const PROFILO_WORKFLOW_STATUS_TOVALIDATE = 'ProfiloWorkflow/TOVALIDATE';
    const PROFILO_WORKFLOW_STATUS_VALIDATED = 'ProfiloWorkflow/VALIDATED';

    const UNIQUE_SECRET_CODE_LEN = 16;

    private $allegati;

    /**
     * @var array $places_fields
     */
    protected $places_fields = [
        'mainLegalHeadquarterAddress',
        'mainOperativeHeadquarterAddress'
    ];

    /**
     * @var string $mainOperativeHeadquarterAddress
     */
    public $mainOperativeHeadquarterAddress;

    /**
     * @var string $mainLegalHeadquarterAddress
     */
    public $mainLegalHeadquarterAddress;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
            'name',
//            'rappresentante_legale',
//            'referente_operativo',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        /* return [
          'denominazione' => Module::t('amosorganizzazioni', ''),
          'partita_iva' => Module::t('amosorganizzazioni', 'Deve essere obbligatorio ma alternativi a cf'),
          'codice_fiscale' => Module::t('amosorganizzazioni', 'Deve essere obbligatorio ma alternativi a p.iva'),
          'presentazione_della_organizzaz' => Module::t('amosorganizzazioni', ''),
          'principali_ambiti_di_attivita_organizzazione' => Module::t('amosorganizzazioni', 'deve essere di tipo albero identico a quello del plugin della POI'),
          'ambiti_tecnologici_su_cui_siet' => Module::t('amosorganizzazioni', 'deve essere di tipo albero identico a quello del plugin della POI'),
          'tipologia_di_organizzazione' => Module::t('amosorganizzazioni', 'deve essere una tendina le cui opzioni mi verranno confermate ma tendenzialmente uguali a POI'),
          'forma_legale' => Module::t('amosorganizzazioni', 'deve essere una tendina le cui opzioni mi verranno confermate ma tendenzialmente uguali a POI'),
          'sito_web' => Module::t('amosorganizzazioni', ''),
          'facebook' => Module::t('amosorganizzazioni', ''),
          'twitter' => Module::t('amosorganizzazioni', ''),
          'linkedin' => Module::t('amosorganizzazioni', ''),
          'google' => Module::t('amosorganizzazioni', ''),
          'logo' => Module::t('amosorganizzazioni', 'upload di immagine'),
          'allegati' => Module::t('amosorganizzazioni', 'upload di file'),
          'indirizzo' => Module::t('amosorganizzazioni', 'deve essere come quello di POI cioè con la mappa'),
          'telefono' => Module::t('amosorganizzazioni', ''),
          'fax' => Module::t('amosorganizzazioni', ''),
          'email' => Module::t('amosorganizzazioni', ''),
          'pec' => Module::t('amosorganizzazioni', ''),
          'la_sede_legale_e_la_stessa_del' => Module::t('amosorganizzazioni', 'si/no'),
          'sede_legale_indirizzo' => Module::t('amosorganizzazioni', 'deve essere come quello di POI cioè con la mappa e deve comparire solo se alla domanda precedente è stato risposto no'),
          'sede_legale_telefono' => Module::t('amosorganizzazioni', ' deve comparire solo se alla domanda precedente è stato risposto no'),
          'sede_legale_fax' => Module::t('amosorganizzazioni', ' deve comparire solo se alla domanda precedente è stato risposto no'),
          'sede_legale_email' => Module::t('amosorganizzazioni', ' deve comparire solo se alla domanda precedente è stato risposto no'),
          'sede_legale_pec' => Module::t('amosorganizzazioni', ' deve comparire solo se alla domanda precedente è stato risposto no'),
          'responsabile' => Module::t('amosorganizzazioni', 'da selezionare tra gli utenti registrati'),
          'rappresentante_legale' => Module::t('amosorganizzazioni', 'da selezionare tra gli utenti registrati'),
          'referente_operativo' => Module::t('amosorganizzazioni', 'da selezionare tra gli utenti registrati'),
          ]; */
        return [];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'fileBehavior' => [
                'class' => FileBehavior::class
            ],
        ];

        if ($this->organizzazioniModule->enableWorkflow) {
            $behaviors['workflow'] = [
                'class' => SimpleWorkflowBehavior::class,
                'defaultWorkflowId' => self::PROFILO_WORKFLOW,
                'propagateErrorsToModel' => true
            ];
            $behaviors['workflowLog'] = [
                'class' => WorkflowLogFunctionsBehavior::class,
            ];
        }

        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }

    /**
     * @return boolean
     */
    public function sendNotification()
    {
        if (empty($this->organizzazioniModule)) {
            return false;
        }

        return $this->organizzazioniModule->sendNotificationOnValidate;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            $this->on(self::EVENT_BEFORE_VALIDATE, [$this, 'organizationsBeforeValidate']);
        }

        if (!$this->isNewRecord && !$this->organizzazioniModule->oldStyleAddressEnabled) {
            $mainOperativeHeadquarter = $this->operativeHeadquarter;
            if (!is_null($mainOperativeHeadquarter)) {
                $this->mainOperativeHeadquarterAddress = $mainOperativeHeadquarter->address;
            }
            $mainLegalHeadquarter = $this->legalHeadquarter;
            if (!is_null($mainLegalHeadquarter)) {
                $this->mainLegalHeadquarterAddress = $mainLegalHeadquarter->address;
            }
        }

        if ($this->isNewRecord) {
            if ($this->organizzazioniModule->enableWorkflow) {
                $this->setInitialStatus();
            }
        }

        /** @var Module $organizzazioniModule */
        if ($this->organizzazioniModule->forceSameSede) {
            // Not check if is new record because it must be always the same.
            $this->la_sede_legale_e_la_stessa_del = 1;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(parent::rules(),
            [
                [['partita_iva'], PIVAValidator::class],
                [['codice_fiscale'], CfPivaValidator::class],
                [['email'], 'email'],
                [['pec'], 'email'],
                [['sede_legale_email'], 'email'],
                [['sede_legale_pec'], 'email'],
                [[
                    'mainLegalHeadquarterAddress',
                    'mainOperativeHeadquarterAddress'
                ], 'string'],

            ]);

        if ($this->organizzazioniModule->enableSediRequired && !$this->organizzazioniModule->oldStyleAddressEnabled) {
            $rules = ArrayHelper::merge($rules, [
                [['mainOperativeHeadquarterAddress', 'la_sede_legale_e_la_stessa_del'], 'required'],
                [['mainLegalHeadquarterAddress'], 'required', 'when' => function ($model) {
                    /** @var Profilo $model */
                    return ($model->la_sede_legale_e_la_stessa_del == 0);
                }, 'whenClient' => "function (attribute, value) {
                    return $('#" . Html::getInputId($this, 'la_sede_legale_e_la_stessa_del') . "').val() == 0;
                }"],
            ]);
        }

        return $rules;
    }

    /**
     * This method set the initial workflow status in the model.
     * @throws \raoul2000\workflow\base\WorkflowException
     */
    public function setInitialStatus()
    {
        if ($this->organizzazioniModule->enableWorkflow) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::PROFILO_WORKFLOW)->getInitialStatusId();
        }
    }

    public function organizationsBeforeValidate()
    {
        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            foreach ($this->places_fields as $place_field) {
                $place_id = $this->{$place_field};
                OrganizationsPlacesComponents::checkPlace($place_id);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->unique_secret_code = $this->generateUniqueSecretCode();
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            foreach ($this->places_fields as $place_field) {
                $place_id = $this->{$place_field};
                OrganizationsPlacesComponents::checkPlace($place_id);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Returns the address in string format
     * @return string
     */
    public function getAddressString($field_name)
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            return false;
        }

        //check if the input $field_name is in the class array containg the address's fields
        if (!isset($this->{$field_name}) && !in_array($field_name, $this->places_fields)) {
            return false;
        }
        //gets the record by the input field
        $placeObj = OrganizationsPlacesComponents::getPlace($this->{$field_name});
        //return the address's string
        return OrganizationsPlacesComponents::getGeocodeString($placeObj);
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
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'mainOperativeHeadquarterAddress' => Module::t('amosorganizzazioni', '#mainOperativeHeadquarterAddress'),
            'mainLegalHeadquarterAddress' => Module::t('amosorganizzazioni', '#mainLegalHeadquarterAddress'),
        ]);
    }

    /**
     * Verifica la validità del codice fiscale (numerico) o della partita iva
     * @param string $attribute
     * @param array $params
     */
    public function checkPartitaIva($attribute, $params)
    {
        $partitaIva = $this->{$attribute};
        $isValid = false;
        if (!$partitaIva) {
            $isValid = true;
        } else if (strlen($partitaIva) != 11) {
            $isValid = false;
        } else if (strlen($partitaIva) == 11) {
            //la p.iva deve avere solo cifre
            if (!preg_match("/^[0-9]+$/i", $partitaIva)) {
                $isValid = false;
            } else {
                $primo = 0;
                for ($i = 0; $i <= 9; $i += 2) {
                    $primo += ord($partitaIva[$i]) - ord('0');
                }

                for ($i = 1; $i <= 9; $i += 2) {
                    $secondo = 2 * (ord($partitaIva[$i]) - ord('0'));

                    if ($secondo > 9) $secondo = $secondo - 9;
                    $primo += $secondo;
                }
                if ((10 - $primo % 10) % 10 != ord($partitaIva[10]) - ord('0')) {
                    $isValid = false;
                } else {
                    $isValid = true;
                }
            }
        }
        if (!$isValid) {
            $this->addError($attribute, Module::t('amoscore', 'The TAX code/VAT code is not in a permitted format'));
        }
    }

    /**
     * @return array
     */
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
                'slug' => 'partita_iva',
                'label' => $labels['partita_iva'],
                'type' => 'string'
            ],
            [
                'slug' => 'codice_fiscale',
                'label' => $labels['codice_fiscale'],
                'type' => 'string'
            ],
            [
                'slug' => 'presentazione_della_organizzaz',
                'label' => $labels['presentazione_della_organizzaz'],
                'type' => 'text'
            ],
            [
                'slug' => 'tipologia_di_organizzazione',
                'label' => $labels['tipologia_di_organizzazione'],
                'type' => 'string'
            ],
            [
                'slug' => 'tipologia_struttura_id',
                'label' => $labels['tipologia_struttura_id'],
                'type' => 'string'
            ],
            [
                'slug' => 'forma_legale',
                'label' => $labels['forma_legale'],
                'type' => 'string'
            ],
            [
                'slug' => 'sito_web',
                'label' => $labels['sito_web'],
                'type' => 'string'
            ],
            [
                'slug' => 'facebook',
                'label' => $labels['facebook'],
                'type' => 'string'
            ],
            [
                'slug' => 'twitter',
                'label' => $labels['twitter'],
                'type' => 'string'
            ],
            [
                'slug' => 'linkedin',
                'label' => $labels['linkedin'],
                'type' => 'string'
            ],
            [
                'slug' => 'google',
                'label' => $labels['google'],
                'type' => 'string'
            ],
            [
                'slug' => 'indirizzo',
                'label' => $labels['indirizzo'],
                'type' => 'string'
            ],
            [
                'slug' => 'telefono',
                'label' => $labels['telefono'],
                'type' => 'decimal'
            ],
            [
                'slug' => 'fax',
                'label' => $labels['fax'],
                'type' => 'decimal'
            ],
            [
                'slug' => 'email',
                'label' => $labels['email'],
                'type' => 'string'
            ],
            [
                'slug' => 'pec',
                'label' => $labels['pec'],
                'type' => 'string'
            ],
            [
                'slug' => 'la_sede_legale_e_la_stessa_del',
                'label' => $labels['la_sede_legale_e_la_stessa_del'],
                'type' => 'string'
            ],
            [
                'slug' => 'sede_legale_indirizzo',
                'label' => $labels['sede_legale_indirizzo'],
                'type' => 'string'
            ],
            [
                'slug' => 'sede_legale_telefono',
                'label' => $labels['sede_legale_telefono'],
                'type' => 'decimal'
            ],
            [
                'slug' => 'sede_legale_fax',
                'label' => $labels['sede_legale_fax'],
                'type' => 'decimal'
            ],
            [
                'slug' => 'sede_legale_email',
                'label' => $labels['sede_legale_email'],
                'type' => 'string'
            ],
            [
                'slug' => 'sede_legale_pec',
                'label' => $labels['sede_legale_pec'],
                'type' => 'string'
            ],
            [
                'slug' => 'responsabile',
                'label' => $labels['responsabile'],
                'type' => 'string'
            ],
            [
                'slug' => 'rappresentante_legale',
                'label' => $labels['rappresentante_legale'],
                'type' => 'string'
            ],
            [
                'slug' => 'referente_operativo',
                'label' => $labels['referente_operativo'],
                'type' => 'string'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getModelImage()
    {
        return $this->modelImage = $this->hasOneFile('logoOrganization')->one();
    }

    public function getAllegati()
    {
        if (empty($this->allegati)) {
            $this->allegati = $this->hasMultipleFiles('allegati')->one();
        }
        return $this->allegati;
    }

    /**
     * @param $allegati
     */
    public function setAllegati($allegati)
    {
        $this->allegati = $allegati;
    }

    /**
     * @return string The model title field value
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
        return $this->presentazione_della_organizzaz;
    }

    /**
     * @return string The model description field value
     */
    public function getDescription($truncate=null)
    {
        $ret = $this->name;

        if ($truncate) {
            $ret = $this->__shortText($this->name, 200);
        }
        return $ret;
    }

    /**
     * @return array The columns ti show as default in GridViewWidget
     */
    public function getGridViewColumns()
    {
        $loggedUserId = \Yii::$app->user->id;

        $template = '{view}{update}{delete}';
        if (
            ($this->organizzazioniModule->enableCommunityCreation == true)
            && (!empty($this->organizzazioniModule->communityModule))) {
            $template = '{community}' . $template;
        }

        $columns = [];
        if ($this->organizzazioniModule->enableProfiloEntiType === true) {
            $columns['profilo_enti_type_id'] = [
                'attribute' => 'profilo_enti_type_id',
                'value' => 'profiloEntiType.name'
            ];
        }
        $columns[] = 'name';
        if ($this->organizzazioniModule->enableFormaLegale === true) {
            $columns[] = 'formaLegale.name';
        }
        if ($this->organizzazioniModule->enableProfiloTipologiaStruttura === true) {
            $columns[] = 'tipologiaStruttura.name';
        }
//        $columns[] = 'rappresentanteLegale';
//        $columns[] = 'referenteOperativo';
        $columns[] = 'addressField:raw';
        $columns[] = 'operativeHeadquarter.email';
        if ($this->organizzazioniModule->enableWorkflow && Yii::$app->user->can('AMMINISTRATORE_ORGANIZZAZIONI')) {
            $columns['status'] = [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\Profilo $model */
                    return $model->getWorkflowBaseStatusLabel();
                }
            ];
        }
        $columns[] = [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => $template,
            'buttons' => [
                'community' => function ($url, $model) use ($loggedUserId) {
                    /** @var Profilo $model */
                    $url = '';
                    if (is_null($model->community_id)) {
                        if (in_array($loggedUserId, [$model->rappresentante_legale, $model->referente_operativo])) {
                            $url = Html::a(
                                AmosIcons::show('globe-lock'),
                                ['/organizzazioni/profilo/create-community/', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => Module::t('amosorganizzazioni', 'Crea una community associata a questa organizzazione'),
                                    'data-confirm' => Module::t('amosorganizzazioni', 'Sicuro di voler creare la Community?')
                                ]
                            );
                        }
                    } else {
                        $userInList = false;
                        foreach ($model->communityUserMm as $userCommunity) { // User not yet subscribed to the event
                            if ($userCommunity->user_id == $loggedUserId) {
                                $userInList = true;
                                $userStatus = $userCommunity->status;
                                break;
                            }
                        }
                        if (Yii::$app->user->can('ADMIN', ['model' => $model])) {
                            $userInList = true;
                            $userStatus = CommunityUserMm::STATUS_ACTIVE;
                        }

                        if ($userInList === true) {
                            $showButton = true;
                            switch ($userStatus) {
                                case CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER:
                                    $button['title'] = Module::t('amosorganizzazioni', 'Request sent');
                                    $button['options']['class'] .= ' disabled';
                                    break;
                                case CommunityUserMm::STATUS_WAITING_OK_USER:
                                    $button['title'] = Module::t('amosorganizzazioni', 'Accept invitation');
                                    $button['url'] = [
                                        '/community/community/accept-user',
                                        'communityId' => $model->community_id,
                                        'userId' => Yii::$app->user->id
                                    ];
                                    $button['options']['data']['confirm'] = Module::t('amosorganizzazioni', 'Do you really want to accept invitation?');
                                    break;
                                case CommunityUserMm::STATUS_ACTIVE:
                                    $url = Html::a(
                                        AmosIcons::show('globe'),
                                        ['/community/join', 'id' => $model->community_id],
                                        [
                                            'class' => 'btn btn-tools-secondary',
                                            'title' => Module::t('amosorganizzazioni', 'Accedi alla community associata a questa organizzazione'),
                                        ]
                                    );
                                    break;
                            }
                        }
                    }

                    return $url;
                }
            ]
        ];

        return $columns;
    }

    /**
     * @return array
     */
    public function getUserNetworkWidgetColumns()
    {
        $columns = [];

        $columns['logo_id'] = [
            'headerOptions' => [
                'id' => Module::t('amosorganizzazioni', '#logo'),
            ],
            'contentOptions' => [
                'headers' => Module::t('amosorganizzazioni', '#logo'),
            ],
            'label' => Module::t('amosorganizzazioni', '#logo'),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var ProfiloUserMm $model */
                return ProfiloCardWidget::widget(['model' => $model->profilo]);
            }
        ];

        if ($this->organizzazioniModule->enableProfiloEntiType === true) {
            $columns['profilo.profilo_enti_type_id'] = [
                'attribute' => 'profilo.profilo_enti_type_id',
                'value' => 'profilo.profiloEntiType.name'
            ];
        }

        $columns[] = 'profilo.name';
        $columns[] = [
            'attribute' => 'profilo.createdUserProfile.created_by',
            'value' => 'profilo.createdUserProfile.nomeCognome'
        ];

        return $columns;
    }

    /**
     * @return string
     */
    public function getUserNetworkWidgetActionColumnsTemplate()
    {
        return  '';
    }

    /**
     * @param UserProfile $model The user profile in update
     * @param int $widgetUserId
     * @param AmosUser $loggedUser
     * @param int $loggedUserId
     * @return array
     */
    public function getUserNetworkWidgetActionColumns($model, $widgetUserId, $loggedUser, $loggedUserId)
    {
        return  [
            'joinOrganizzation' => function ($url, $model) {
                return Html::a(
                    Module::t('amosorganizzazioni', '#view_details')
                        . AmosIcons::show('sign-in'),
                    Yii::$app->urlManager->createUrl([
                        '/' . Module::getModuleName() . '/profilo/view',
                        'id' => $model->profilo_id,
                    ]),
                    [
                        'title' => Module::t('amosorganizzazioni', '#view_details'),
                        'class' => 'btn btn-navigation-primary btn-join-community font08'
                    ]
                );
            },
            'deleteRelation' => function ($url, $model_mm) use ($loggedUser, $loggedUserId, $model, $widgetUserId) {
                /** @var ProfiloUserMm $model_mm */
                $organizationId = $model_mm->profilo_id;
                $targetId = $widgetUserId;
                $urlDelete = Yii::$app->urlManager->createUrl([
                    '/' . Module::getModuleName() . '/profilo/elimina-m2m',
                    'id' => $organizationId,
                    'targetId' => $targetId,
                    'redirectAction' => \yii\helpers\Url::current()
                ]);

                if (
                    (($loggedUserId == $widgetUserId) && \Yii::$app->user->can('REMOVE_ORGANIZZAZIONI_FROM_USER', ['model' => $model]) && (($model_mm->profilo->created_by != $loggedUserId) || $loggedUser->can('AMMINISTRATORE_ORGANIZZAZIONI'))) ||
                    $loggedUser->can('AMMINISTRATORE_ORGANIZZAZIONI')
                ) {
                    return Html::a(AmosIcons::show('close', ['class' => 'btn-delete-relation']),
                        $urlDelete,
                        [
                            'title' => Module::t('amosorganizzazioni', '#delete'),
                            'data-url-confirm' => Module::t('amosorganizzazioni', '#are_you_sure_cancel'),
                        ]
                    );
                }
            }
        ];
    }

    /**
     * @return \yii\db\ActiveQuery category of content
     */
    public function getCategory()
    {
        return null;
    }

    /**
     * @return string The classname of the generic dashboard widget to access the plugin
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconProfilo::class;
    }

    /**
     * @return ProfiloGrammar
     */
    public function getGrammar()
    {
        return new ProfiloGrammar();
    }

    /**
     * @return string The name that correspond to 'to validate' status for the content model
     */
    public function getToValidateStatus()
    {
        return self::PROFILO_WORKFLOW_STATUS_TOVALIDATE;
    }

    /**
     * @return string The name that correspond to 'published' status for the content model
     */
    public function getValidatedStatus()
    {
        return self::PROFILO_WORKFLOW_STATUS_VALIDATED;
    }

    /**
     * @return string The name that correspond to 'draft' status for the content model
     */
    public function getDraftStatus()
    {
       return self::PROFILO_WORKFLOW_STATUS_DRAFT;
    }

    /**
     * Get the user id used in network-users association table
     * @return int
     */
    public function getUserId()
    {
        return Yii::$app->getUser()->id;
    }

    /**
     * Get the name of the table storing network-users associations
     * @return string
     */
    public function getMmTableName()
    {
        return ProfiloUserMm::tableName();
    }

    /**
     * Get the name of field that contains user id in network-users association table
     * @return string
     */
    public function getMmNetworkIdFieldName()
    {
        return 'profilo_id';
    }

    /**
     * Get the name of field that contains network id in network-users association table
     * @return string
     */
    public function getMmUserIdFieldName()
    {
        return 'user_id';
    }

    /**
     * Return true if the user with id $userId belong to the network with id $networkId; if $userId is null the logged User id is considered
     * @param int $networkId
     * @param int $userId
     * @return bool
     */
    public function isNetworkUser($networkId, $userId = null)
    {
        if (!isset($userId)) {
            $userId = $this->getUserId();
        }
        /** @var ProfiloUserMm $mmModel */
        $mmModel = $this->organizzazioniModule->createModel('ProfiloUserMm');
        $mmRow = $mmModel::findOne([
            $this->getMmNetworkIdFieldName() => $networkId,
            $this->getMmUserIdFieldName() => $userId,
        ]);
        if (!is_null($mmRow)) {
            return true;
        }
        return false;
    }

    /**
     * Return true if the network is validated or no validation process is implemented for the network.
     * if $networkId is null, current network (this) is condidered
     * @param int $networkId
     * @return bool
     */
    public function isValidated($networkId = null)
    {
        return true;
    }

    /**
     * Return classname of the MM table connecting user and network
     * @return string
     */
    public function getMmClassName()
    {
        return $this->organizzazioniModule->model('ProfiloUserMm');
    }

    /**
     * @param null $userId
     * @param bool $isUpdate
     * @return mixed
     */
    public function getUserNetworkWidget($userId = null, $isUpdate = false)
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        $organizationsModuleName = $adminModule->getOrganizationModuleName();
        $organizationsModule = Yii::$app->getModule($organizationsModuleName);

        if (is_null($organizationsModule) || $organizationsModule->hideUserNetworkWidget) {
            return '';
        }

        return UserNetworkWidget::widget(['userId' => $userId, 'isUpdate' => $isUpdate]);
    }

    /**
     * @return string
     */
    public static function getUserNetworkWidgetOrganizzazioniClassName()
    {
        return UserNetworkWidgetOrganizzazioni::class;
    }

    /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [];
    }

    /**
     * Get Id of configuration record for network model Profilo
     * @return int $cwhConfigId
     */
    public static function getCwhConfigId()
    {
        // Default network configuration id = 7 for organizzazioni
        $cwhConfigId = 7;
        $cwhConfig = CwhConfig::findOne(['tablename' => self::tableName()]);
        if (!is_null($cwhConfig)) {
            $cwhConfigId = $cwhConfig->id;
        }
        return $cwhConfigId;
    }

    /**
     * Add CWH permissions based on the role for which a permissions array has been specified,
     * Remove CWH permissions on profilo domain in case of role degradation
     * or delete all permission in case of user-profilo association deletion
     *
     * @param ProfiloUserMm $profiloUserMmRow
     * @param bool|false $delete - if true remove all permission (case deletion user-community association)
     */
    public function setCwhAuthAssignments($profiloUserMmRow, $delete = false)
    {
        /** @var AmosCwh $cwhModule */
        $cwhModule = Yii::$app->getModule("cwh");
        $cwhNodeId = self::tableName() . '-' . $this->id;
        $userId = $profiloUserMmRow->user_id;
        $cwhConfigId = self::getCwhConfigId();

        $cwhPermissions = CwhAuthAssignment::find()->andWhere([
            'user_id' => $userId,
            'cwh_config_id' => $cwhConfigId,
            'cwh_network_id' => $this->id
        ])->all();

        if ($delete) {
            if (!empty($cwhPermissions)) {
                /** @var CwhAuthAssignment $cwhPermission */
                foreach ($cwhPermissions as $cwhPermission) {
                    $cwhPermission->delete();
                }
            } else {
                $existingPermissions = [];
                foreach ($cwhPermissions as $item) {
                    $existingPermissions[$item->item_name] = $item;
                }

                /** @var Profilo $callingModel */
                $callingModel = $this->organizzazioniModule->createModel('Profilo');
                /** @var array $rolePermissions */
                $rolePermissions = $callingModel->getRolePermissions($profiloUserMmRow->role);
                $permissionsToAdd = [];
                if (!is_null($rolePermissions) && count($rolePermissions)) {
                    // For each enabled Content model in CWH...
                    foreach ($cwhModule->modelsEnabled as $modelClassname) {
                        // ...and each role permission...
                        foreach ($rolePermissions as $permission) {
                            // ...
                            $cwhAuthAssignment = new CwhAuthAssignment();
                            $cwhAuthAssignment->user_id = $userId;
                            $cwhAuthAssignment->item_name = $permission . '_' . $modelClassname;
                            $cwhAuthAssignment->cwh_nodi_id = $cwhNodeId;
                            $cwhAuthAssignment->cwh_config_id = $cwhConfigId;
                            $cwhAuthAssignment->cwh_network_id = $this->id;
                            $permissionsToAdd[$cwhAuthAssignment->item_name] = $cwhAuthAssignment;
                        }
                    }
                }
                if (!empty($permissionsToAdd)) {
                    /** @var CwhAuthAssignment $permissionToAdd */
                    foreach ($permissionsToAdd as $key => $permissionToAdd) {
                        //if user has not already the permission for the community , add it to cwh auth assignment
                        if (!array_key_exists($key, $existingPermissions)) {
                            $permissionToAdd->save(false);
                        }
                    }
                }
                // check if there are permissions to remove
                if (!empty($existingPermissions)) {
                    /** @var CwhAuthAssignment $cwhPermission */
                    foreach ($existingPermissions as $key => $cwhPermission) {
                        if (!array_key_exists($key, $permissionsToAdd)) {
                            $cwhPermission->delete();
                        }
                    }
                }
            }
        }
    }

    // Block x Community

    /**
     * @return string The name of model validator role
     */
    public function getValidatorRole()
    {
        return strtoupper('PROFILO_VALIDATOR');
    }

    /**
     * Array containing the possible roles of a community Member
     * @return array
     */
    public function getContextRoles()
    {
        $context_roles = [
            self::ORGANIZZAZIONI_MANAGER,
            self::ORGANIZZAZIONI_PARTICIPANT
        ];

        return $context_roles;
    }

    /**
     * The name of the basic member role
     * @return string
     */
    public function getBaseRole()
    {
        return self::ORGANIZZAZIONI_PARTICIPANT;
    }


    /**
     * The name of the greatest role a member can have
     * @return string
     */
    public function getManagerRole()
    {
        return self::ORGANIZZAZIONI_MANAGER;
    }

    /**
     * Array containing user permission for a given role
     * @param string $role
     * @return array
     */
    public function getRolePermissions($role)
    {
        switch ($role) {
            case self::ORGANIZZAZIONI_MANAGER:
                return ['CWH_PERMISSION_CREATE', 'CWH_PERMISSION_VALIDATE'];
                break;
            case self::ORGANIZZAZIONI_PARTICIPANT:
                return ['CWH_PERMISSION_CREATE'];
                break;
            default:
                return ['CWH_PERMISSION_CREATE'];
                break;
        }
    }

    /**
     * Array containing the next level for a given initial role
     * @param string $role
     * @return string
     */
    public function getNextRole($role)
    {
        switch ($role) {
            case self::ORGANIZZAZIONI_MANAGER :
                return self::ORGANIZZAZIONI_PARTICIPANT;
                break;
            case self::ORGANIZZAZIONI_PARTICIPANT :
                return self::ORGANIZZAZIONI_MANAGER;
                break;
            default :
                return self::ORGANIZZAZIONI_PARTICIPANT;
                break;
        }
    }

    /**
     * The community created by the context model (community related to project-management, events or a community itself)
     * @return Community
     */
    public function getCommunityModel()
    {
        return $this->community;
    }

    /**
     * For m2m widget actions: return the plugin module name to construct redirect URL
     * @return string
     */
    public function getPluginModule()
    {
        return 'organizzazioni';
    }

    /**
     * For m2m widget actions: return the plugin controller name to construct redirect URL
     * @return string
     */
    public function getPluginController()
    {
        return 'organizzazioni';
    }

    /**
     * For m2m widget actions: return the controller action name to construct redirect URL
     * @return string
     */
    public function getRedirectAction()
    {
        return 'view';
    }

    /**
     * Active query to search the users to associate in the additional association page
     *
     * @param integer $communityId Id of the community created by the context model
     * @return ActiveQuery
     */
    public function getAdditionalAssociationTargetQuery($communityId)
    {
        /** @var ActiveQuery $communityUserMms */
        $communityUserMms = CommunityUserMm::find()->andWhere(['community_id' => $communityId]);
        return User::find()->andFilterWhere(['not in', 'id', $communityUserMms->select('user_id')]);
    }

    // End Bloc x Community


    public function getNameField()
    {
        return $this->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperativeHeadquarter()
    {
        return $this->hasOne($this->organizzazioniModule->createModel('ProfiloSediOperative')->className(), ['profilo_id' => 'id'])
            ->andWhere(['profilo_sedi_type_id' => ProfiloSediTypes::TYPE_OPERATIVE_HEADQUARTER])
            ->andWhere(['active' => 1])
            ->andWhere(['is_main' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeIndirizzo()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            return null;
        }
        return $this->hasOne($this->organizzazioniModule->createModel('OrganizationsPlaces')->className(), ['place_id' => 'address'])->via('operativeHeadquarter');
    }

    /**
     * @return string
     */
    public function getAddressField()
    {
        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            if (is_null($this->sedeIndirizzo)) {
                return '-';
            }

            return ($this->sedeIndirizzo->postal_code ? '(' . $this->sedeIndirizzo->postal_code . ')' : '') .
                ($this->sedeIndirizzo->region ? ' ' . $this->sedeIndirizzo->region : '') .
                ($this->sedeIndirizzo->city ? ' ' . $this->sedeIndirizzo->city : '') .
                ($this->sedeIndirizzo->address ? ' ' . $this->sedeIndirizzo->address : '') .
                ($this->sedeIndirizzo->street_number ? ' ' . $this->sedeIndirizzo->street_number : '');
        } else {
            $operativeHeadquarter = $this->operativeHeadquarter;
            return (!is_null($operativeHeadquarter) ? $operativeHeadquarter->getOldStyleAddress() : '-');
        }
    }

    /**
     * @return array
     */
    public function getAddressFieldAsArray()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            $operativeHeadquarter = $this->operativeHeadquarter;
            if (!is_null($operativeHeadquarter)) {
                return [
                    'postal_code' => ($operativeHeadquarter->cap_text ? $operativeHeadquarter->cap_text : ''),
                    'region' => (!is_null($operativeHeadquarter->province) && !is_null($operativeHeadquarter->province->istatRegioni) ? $operativeHeadquarter->province->istatRegioni->nome : ''),
                    'city' => (!is_null($operativeHeadquarter->city) ? $operativeHeadquarter->city->nome : ''),
                    'address' => ($operativeHeadquarter->address_text ? $operativeHeadquarter->address_text : ''),
                    'street_number' => '',
                ];
            } else {
                return null;
            }
        } elseif (!empty($this->sedeIndirizzo)) {
            return [
                'postal_code' => $this->sedeIndirizzo->postal_code,
                'region' => $this->sedeIndirizzo->region,
                'city' => $this->sedeIndirizzo->city,
                'address' => $this->sedeIndirizzo->address,
                'street_number' => $this->sedeIndirizzo->street_number
            ];
        } elseif (!empty($this->mainOperativeHeadquarterAddress)){
            $data = $this->getGooglePlaceInfo($this->mainOperativeHeadquarterAddress);
            return $data->result->formatted_address;
        }else {
            return null;
        }
    }

    /**
     * @param array|string $addressArray
     * @return string
     */
    private function getAddressForView($addressArray)
    {
        $headquarterAddress = "-";
        $addressFieldAsArray = $addressArray;
        if (!empty($addressFieldAsArray) && is_array($addressFieldAsArray)) {
            $headquarterAddress = '';
            if (!empty($addressFieldAsArray['address'])) {
                $headquarterAddress .= $addressFieldAsArray['address'];
                if (!empty($addressFieldAsArray['street_number'])) {
                    $headquarterAddress .= ', ' . $addressFieldAsArray['street_number'];
                }
            }
            if (strlen($headquarterAddress) > 0) {
                $headquarterAddress .= '<br />';
            }
            $postalCode = false;
            if (!empty($addressFieldAsArray['postal_code'])) {
                $headquarterAddress .= $addressFieldAsArray['postal_code'];
                $postalCode = true;
            }
            $city = false;
            if (!empty($addressFieldAsArray['city'])) {
                $city = true;
                if ($postalCode) {
                    $headquarterAddress .= ' ';
                }
                $headquarterAddress .= $addressFieldAsArray['city'];
            }
            if ($postalCode || $city) {
                $headquarterAddress .= '<br />';
            }
            $headquarterAddress .= $addressFieldAsArray['region'];
            if (empty($headquarterAddress)) {
                $headquarterAddress = "-";
            }
        }elseif(!empty($addressFieldAsArray) && is_string($addressFieldAsArray)){
            $headquarterAddress = $addressFieldAsArray;
        }
        return $headquarterAddress;
    }

    /**
     * @return string
     */
    public function getAddressFieldForView()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            return (!is_null($this->operativeHeadquarter) ? $this->operativeHeadquarter->getOldStyleAddress() : '-');
        }
        return $this->getAddressForView($this->getAddressFieldAsArray());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLegalHeadquarter()
    {
        return $this->hasOne($this->organizzazioniModule->createModel('ProfiloSediLegal')->className(), ['profilo_id' => 'id'])
            ->andWhere(['profilo_sedi_type_id' => ProfiloSediTypes::TYPE_LEGAL_HEADQUARTER])
            ->andWhere(['active' => 1])
            ->andWhere(['is_main' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeLegaleIndirizzo()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            return null;
        }
        return $this->hasOne($this->organizzazioniModule->createModel('OrganizationsPlaces')->className(), ['place_id' => 'address'])->via('legalHeadquarter');
    }

    /**
     * @return string
     */
    public function getAddressFieldSedeLegale()
    {
        if (!$this->organizzazioniModule->oldStyleAddressEnabled) {
            if (is_null($this->sedeLegaleIndirizzo)) {
                return '-';
            }

            return ($this->sedeLegaleIndirizzo->postal_code ? '(' . $this->sedeLegaleIndirizzo->postal_code . ')' : '') .
                ($this->sedeLegaleIndirizzo->region ? ' ' . $this->sedeLegaleIndirizzo->region : '') .
                ($this->sedeLegaleIndirizzo->city ? ' ' . $this->sedeLegaleIndirizzo->city : '') .
                ($this->sedeLegaleIndirizzo->address ? ' ' . $this->sedeLegaleIndirizzo->address : '') .
                ($this->sedeLegaleIndirizzo->street_number ? ' ' . $this->sedeLegaleIndirizzo->street_number : '');
        } else {
            return (!is_null($this->legalHeadquarter) ? $this->legalHeadquarter->getOldStyleAddress() : '-');
        }
    }

    /**
     * @return array
     */
    public function getAddressFieldSedeLegaleAsArray()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            $legalHeadquarter = $this->legalHeadquarter;
            if (!is_null($legalHeadquarter)) {
                return [
                    'postal_code' => ($legalHeadquarter->cap_text ? $legalHeadquarter->cap_text : ''),
                    'region' => (!is_null($legalHeadquarter->province) && !is_null($legalHeadquarter->province->istatRegioni) ? $legalHeadquarter->province->istatRegioni->nome : ''),
                    'city' => (!is_null($legalHeadquarter->city) ? $legalHeadquarter->city->nome : ''),
                    'address' => ($legalHeadquarter->address_text ? $legalHeadquarter->address_text : ''),
                    'street_number' => '',
                ];
            } else {
                return null;
            }
        } elseif (!empty($this->sedeLegaleIndirizzo)) {
            return [
                'postal_code' => $this->sedeLegaleIndirizzo->postal_code,
                'region' => $this->sedeLegaleIndirizzo->region,
                'city' => $this->sedeLegaleIndirizzo->city,
                'address' => $this->sedeLegaleIndirizzo->address,
                'street_number' => $this->sedeLegaleIndirizzo->street_number
            ];
        } elseif (!empty($this->mainLegalHeadquarterAddress)){
            $data = $this->getGooglePlaceInfo($this->mainLegalHeadquarterAddress);
            return $data->result->formatted_address;
        }else{
            return null;
        }
    }

    
    private function getGooglePlaceInfo(string $address)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query(array(
            'key' => Yii::$app->params['google-maps']['key'],
            'place_id' => $address,
        ));

        $json = file_get_contents($url);
        $data = json_decode($json);
        return $data;
    }
    
    /**
     * @return string
     */
    public function getAddressFieldSedeLegaleForView()
    {
        if ($this->organizzazioniModule->oldStyleAddressEnabled) {
            return (!is_null($this->legalHeadquarter) ? $this->legalHeadquarter->getOldStyleAddress() : '-');
        }
        return $this->getAddressForView($this->getAddressFieldSedeLegaleAsArray());
    }

    /**
     * @return bool
     */
    public function isMunicipality()
    {
        return ($this->profilo_enti_type_id == ProfiloEntiType::TYPE_MUNICIPALITY);
    }

    /**
     * @return bool
     */
    public function isOtherEntity()
    {
        return ($this->profilo_enti_type_id == ProfiloEntiType::TYPE_OTHER_ENTITY);
    }

    /**
     * Return the recordset of all recipients relative to the networks
     * associated to the relative user (they may be different? check it!)
     * @param array $networkIds
     * @param array $usersId
     * @return array
     */
    public function getListOfRecipients($networkIds = [], $usersId = [])
    {
        $query = new Query();
        /** @var Profilo $modelProfilo */
        $modelProfilo = $this->organizzazioniModule->createModel('Profilo');
        /** @var ProfiloUserMm $modelProfiloUserMm */
        $modelProfiloUserMm = $this->organizzazioniModule->createModel('ProfiloUserMm');
        $profiloTable = $modelProfilo::tableName();
        $profiloUserMmTable = $modelProfiloUserMm::tableName();
        $query->select([
            "CONCAT('" . $profiloTable . "', '-', " . $profiloUserMmTable . ".profilo_id) AS objID",
            $profiloTable . '.id', $profiloTable . '.name', $profiloTable . '.deleted_at',
            $profiloUserMmTable . '.profilo_id', $profiloUserMmTable . '.profilo_id AS reference',
            $profiloUserMmTable . '.deleted_at'
        ])
            ->from(static::tableName())
            ->leftJoin($profiloUserMmTable, $profiloUserMmTable . '.profilo_id = ' . $profiloTable . '.id
        AND ' . $profiloUserMmTable . '.deleted_at IS NULL')
            ->where([$profiloTable . '.id' => $networkIds])
            ->andWhere([
                $profiloUserMmTable . '.user_id' => $usersId,
                $profiloTable . '.deleted_at' => null,
            ]);
        return $query->all();
    }

    /**
     * This method checks if the user is already an employee for this organization.
     * If the parameter is null, the method uses the logged user id.
     * If the method is called on a new object, it throw an AmosException.
     * @param int|null $userId
     * @return bool
     * @throws AmosException
     */
    public function userIsEmployee($userId = null)
    {
        if (is_null($userId)) {
            $userId = Yii::$app->user->id;
        }

        if (!$this->id) {
            throw new AmosException(Module::t('amosorganizzazioni', '#userIsEmployee_no_model_id'));
        }

        if (($this->rappresentante_legale == $userId) || ($this->referente_operativo == $userId)) {
            return true;
        }

        $profiloUsersCount = $this->getProfiloUsers()->andWhere([User::tableName() . '.id' => $userId])->count();

        return ($profiloUsersCount > 0);
    }

	 /**
     * @return array
     */
    public function getStatusToRenderToHide()
    {
        $statusToRender = [
            self::PROFILO_WORKFLOW_STATUS_DRAFT => Module::t('amosorganizzazioni', 'Modifica in corso'),
        ];
        $isCommunityManager = false;
        if (!is_null(\Yii::$app->getModule('community'))) {
            $isCommunityManager = CommunityUtil::isLoggedCommunityManager();
            if ($isCommunityManager) {
                $isCommunityManager = true;
            }
        }

        // if you are a community manager a validator/facilitator or ADMIN you Can publish directly
        if (\Yii::$app->user->can('ProfiloValidate', ['model' => $this]) || \Yii::$app->user->can('ADMIN') || $isCommunityManager) {
            $statusToRender = ArrayHelper::merge($statusToRender, [self::PROFILO_WORKFLOW_STATUS_VALIDATED => Module::t('amosorganizzazioni', 'Pubblicata')]);
            $hideDraftStatus = [];
        } else {
            $statusToRender = ArrayHelper::merge($statusToRender, [
                self::PROFILO_WORKFLOW_STATUS_TOVALIDATE => Module::t('amosorganizzazioni', 'Richiedi pubblicazione'),
            ]);
            $hideDraftStatus[] = self::PROFILO_WORKFLOW_STATUS_VALIDATED;

        }
        return ['statusToRender' => $statusToRender, 'hideDraftStatus' => $hideDraftStatus];
    }

    /**
     * @return boolean
     */
    public function hasSubNetworks()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowBaseStatusLabel()
    {
        $status = parent::getWorkflowBaseStatusLabel();
        return ((strlen($status) > 0) ? Module::t('amosorganizzazioni', $status) : '-');
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowStatusLabel()
    {
        $status = parent::getWorkflowStatusLabel();
        return ((strlen($status) > 0) ? Module::t('amosorganizzazioni', $status) : '-');
    }

    /**
     * This method returns the organization that match the provided secret code. If nothing match it returns null.
     * @param string $uniqueSecretCode
     * @return Profilo|null
     */
    public static function findBySecretCode($uniqueSecretCode)
    {
        return static::findOne(['unique_secret_code' => $uniqueSecretCode]);
    }

    /**
     * This method generates the unique secret code useful i the external user invitation to join an organization when he register.
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateUniqueSecretCode()
    {
        do {
            $uniqueSecretCode = 'org-' . Yii::$app->security->generateRandomString(self::UNIQUE_SECRET_CODE_LEN);
            $uniqueSecretCodeExists = static::findBySecretCode($uniqueSecretCode);
        } while (!is_null($uniqueSecretCodeExists));
        return $uniqueSecretCode;
    }

    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return 'organizzazioni';
    }

    /**
     * @inheritdoc
     */
    public function getModelControllerName()
    {
        return 'profilo';
    }

    /**
     *
     */
    public function createCommunityOrganizzazione($communityType)
    {
        if (is_null($this->community_id)) {
            $managerStatus = CommunityUserMm::STATUS_ACTIVE; //$this->getManagerStatus($model, $oldAttributes);

            $eventBefore = new Event();
            $eventBefore->sender = [
                'organization' => $this
            ];
            $this->trigger(ProfiloController::EVENT_BEFORE_CREATE_COMMUNITY, $eventBefore);

            $ok = OrganizzazioniUtility::createCommunity($this, $managerStatus, $communityType);

            $eventAfter = new Event();
            $eventAfter->sender = [
                'organization' => $this,
                'creationOk' => $ok
            ];
            $this->trigger(ProfiloController::EVENT_AFTER_CREATE_COMMUNITY, $eventAfter);

            if ($ok) {
                // If it's the first validation, check if the logged user is the same as the manager.
                // In that case set the manager in the active status.
                $managers = OrganizzazioniUtility::findOrganizzazioneManagers($this);

                foreach ($managers as $eventManager) {
                    /** @var CommunityUserMm $eventManager */
                    if (($eventManager->user_id == Yii::$app->getUser()->getId()) && ($eventManager->status != CommunityUserMm::STATUS_ACTIVE)) {
                        $eventManager->status = CommunityUserMm::STATUS_ACTIVE;
                        $eventManager->save();
                    }
                }
            }

            if ($this->save(false) && $ok) {
                Yii::$app->getSession()->addFlash(
                    'success',
                    Module::t('amosorganizzazioni', '#community_create_success')
                );
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amosorganizzazioni', '#community_create_error'));
            }
        } else {
            Yii::$app->getSession()->addFlash(
                'info',
                Module::t('amosorganizzazioni', '#community_create_already_exists')
            );
        }
    }

    /**
     * @param $user_id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function removeMembershipFromCommunities($user_id){
        $community = $this->community;
        if($community){
            $this->recursiveRemoveMembershipFromCommunitiesDelete($community->id, $user_id);
        }
    }

    /**
     * @param $community_id
     * @param $user_id
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function recursiveRemoveMembershipFromCommunitiesDelete($community_id, $user_id){
        $currentCommunity = Community::findOne($community_id);
        $children = Community::find()->andWhere(['parent_id' => $community_id])->all();

        /** @var  $node Community*/
        foreach ($children as $community) {
            $this->recursiveRemoveMembershipFromCommunitiesDelete($community->id, $user_id);
        }
        //DELETE user assigned and the node
        $members = $currentCommunity->getCommunityUserMms()->andWhere(['community_user_mm.user_id' => $user_id])->all();
        if($members){
            foreach ($members as $member){
            $member->delete();
            }

        }
        return true;
    }
}
