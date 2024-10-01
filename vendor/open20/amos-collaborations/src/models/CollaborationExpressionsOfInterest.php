<?php

namespace open20\amos\collaborations\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\comuni\models\IstatComuni;
use open20\amos\comuni\models\IstatNazioni;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\core\helpers\StringHelper;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "collaboration_expressions_of_interest".
 *
 * @property-read null|string $iconMarker
 * @property-read \open20\amos\collaborations\models\url|null $urlEvent
 * @property-read \open20\amos\collaborations\models\color|null $colorEvent
 * @property-read string $proposerFullName
 * @property-read null|\open20\amos\collaborations\models\title $titleEvent
 * @property-read null|array $events
 */
class CollaborationExpressionsOfInterest extends \open20\amos\collaborations\models\base\CollaborationExpressionsOfInterest
{
    public $proposal_category;
    public $proposer_full_name;
    public $collaborationProposalStatus;

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' =>
                    [
                        'class' => FileBehavior::className()
                    ],
            ]
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

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['file'], 'file', 'maxFiles' => 5, 'extensions' => ['pdf','doc','docx']],
            [['proposer_full_name', 'proposal_category', 'collaborationProposalStatus'], 'safe']
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                    'proposal_category' => \Yii::t('amoscollaborations', 'Categoria proposta'),
                    'proposer_full_name' => \Yii::t('amoscollaborations', 'Manifestazione effettuata da'),
                    'collaborationProposalStatus' => \Yii::t('amoscollaborations', 'Stato proposta'),
                    'file' => \Yii::t('amoscollaborations', 'File')
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'collaboration_proposal_id',
                'label' => $labels['collaboration_proposal_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'proposal_title',
                'label' => $labels['proposal_title'],
                'type' => 'string'
            ],
            [
                'slug' => 'proposer_first_name',
                'label' => $labels['proposer_first_name'],
                'type' => 'string'
            ],
            [
                'slug' => 'proposer_last_name',
                'label' => $labels['proposer_last_name'],
                'type' => 'string'
            ],
            [
                'slug' => 'proposer_full_name',
                'label' => $labels['proposer_full_name'],
                'type' => 'string'
            ],
            [
                'slug' => 'proposer_email',
                'label' => $labels['proposer_email'],
                'type' => 'string'
            ],
            [
                'slug' => 'proposer_phone',
                'label' => $labels['proposer_phone'],
                'type' => 'string'
            ],
            [
                'slug' => 'company',
                'label' => $labels['company'],
                'type' => 'string'
            ],
            [
                'slug' => 'country',
                'label' => $labels['country'],
                'type' => 'string'
            ],
            [
                'slug' => 'city',
                'label' => $labels['city'],
                'type' => 'string'
            ],
            [
                'slug' => 'province',
                'label' => $labels['province'],
                'type' => 'string'
            ],
            [
                'slug' => 'municipality',
                'label' => $labels['municipality'],
                'type' => 'string'
            ],
            [
                'slug' => 'address',
                'label' => $labels['address'],
                'type' => 'string'
            ],
            [
                'slug' => 'postal_code',
                'label' => $labels['postal_code'],
                'type' => 'string'
            ],
            [
                'slug' => 'manifestazioneinteresse',
                'label' => $labels['manifestazioneinteresse'],
                'type' => 'string'
            ]
            
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

    public function getProposerFullName()
    {
        return $this->proposer_first_name . ' ' . $this->proposer_last_name;
    }
    public function getCountryName($countryId)
    {
        if ($this->collaborationProposals->category_id == CollaborationProposals::CATEGORY_FROM_PLATFORM) {
            return IstatNazioni::findOne($countryId)->nome;
        } else {
            return IstatNazioni::findOne($countryId)->nome_inglese;
        }
    }

    public function getProvinceName($provinceId)
    {
        return IstatProvince::findOne($provinceId)->nome;
    }

    public function getCityName($cityId)
    {
        return IstatComuni::findOne($cityId)->nome;
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
