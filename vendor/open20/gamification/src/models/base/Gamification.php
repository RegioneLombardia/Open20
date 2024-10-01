<?php

namespace open20\gamification\models\base;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\core\record\Record;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "gamification".
 *
 *
 * @property integer $id
 * @property string $titolo
 * @property string $descrizione
 * @property integer $starting_date
 * @property integer $finish_date
 * @property string $status
 *
 * @property integer $utente_iscrizione
 * @property integer $utente_foto
 * @property integer $utente_colleg
 * @property integer $community_creazione
 * @property integer $community_iscrizione
 * @property integer $notizie_creazione
 * @property integer $notizie_commento
 * @property integer $notizie_like
 * @property integer $documenti_creazione
 * @property integer $discussioni_creazione
 * @property integer $discussioni_commento
 * @property integer $discussioni_like
 * @property integer $sondaggi_partec
 *
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 */
class Gamification extends Record
{
    const GAMIFICATION_WORKFLOW = 'GamificationWorkflow';
    const GAMIFICATION_WORKFLOW_STATUS_DRAFT = 'GamificationWorkflow/DRAFT';
    const GAMIFICATION_WORKFLOW_STATUS_OPENING = 'GamificationWorkflow/OPENING';
    const GAMIFICATION_WORKFLOW_STATUS_CONCLUDED = 'GamificationWorkflow/CONCLUDED';

    const defaultValue_utente_iscrizione = 200;
    const defaultValue_utente_foto = 150;
    const defaultValue_utente_colleg = 10;
    const defaultValue_community_creazione = 100;
    const defaultValue_community_iscrizione = 20;
    const defaultValue_notizie_creazione = 50;
    const defaultValue_notizie_commento = 20;
    const defaultValue_notizie_like = 20;
    const defaultValue_documenti_creazione = 50;
    const defaultValue_discussioni_creazione = 100;
    const defaultValue_discussioni_commento = 20;
    const defaultValue_discussioni_like = 20;
    const defaultValue_sondaggi_partec = 50;

    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gamification';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->status = $this->getWorkflowSource()->getWorkflow(self::GAMIFICATION_WORKFLOW)->getInitialStatusId();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::GAMIFICATION_WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'workflowLog' => [
                    'class' => WorkflowLogFunctionsBehavior::className()
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(
            parent::rules(),
            [
                [['titolo', 'descrizione', 'status'], 'string'],
                [['titolo', 'descrizione', 'status'], 'string', 'max' => 255],
                [['titolo', 'descrizione', 'status','starting_date', 'finish_date'], 'required'],
                [
                    [
                        'utente_iscrizione', 'utente_foto', 'utente_colleg', 'community_creazione', 'community_iscrizione',
                        'notizie_creazione', 'notizie_commento', 'notizie_like', 'documenti_creazione', 'discussioni_creazione',
                        'discussioni_commento', 'discussioni_like', 'sondaggi_partec', 'created_by', 'updated_by', 'deleted_by'
                    ], 'integer', 'min' => 0
                ],
                [['created_at', 'updated_at', 'deleted_at'], 'safe'],
                [['starting_date'], 'validatedInputStartingDate', 'skipOnEmpty' => false],
                [['finish_date'], 'validatedInputFinishDate', 'skipOnEmpty' => false],
            ]
        );

        return $rules;
    }

    public function validatedInputStartingDate($attribute, $params) {
        $query1 = Gamification::find()
            ->andWhere(['<=', 'starting_date', $this->starting_date])
            ->andWhere(['>=', 'finish_date', $this->starting_date]);
        $count1 = $query1->count();

        if ($count1 > 0) {
            if ($count1 == 1) {
                $query1 = $query1->one();

                if ($this->id == null){
                    $this->addError('starting_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }

                if (!($query1->id == $this->id)) {
                    $this->addError('starting_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }
            } elseif ($count1 > 1) {
                $this->addError('starting_date',
                    '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
            }
        }

        $query2 = Gamification::find()
            ->andWhere(['>=', 'starting_date', $this->starting_date])
            ->andWhere(['<=', 'finish_date', $this->finish_date]);
        $count2 = $query2->count();

        if ($count2 > 0) {
            if ($count2 == 1) {
                $query2 = $query2->one();

                if ($this->id == null) {
                    $this->addError('starting_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }

                if (!($query2->id == $this->id)) {
                    $this->addError('starting_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }
            } elseif ($count2 > 1) {
                $this->addError('starting_date',
                    '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
            }
        }
    }

    public function validatedInputFinishDate($attribute, $params) {
        $query1 = Gamification::find()
            ->andWhere(['<=', 'starting_date', $this->finish_date])
            ->andWhere(['>=', 'finish_date', $this->finish_date]);
        $count1 = $query1->count();

        if ($this->starting_date >= $this->finish_date){
            $this->addError('starting_date',
                'La data di inizio deve essere minore della data di fine');
        }

        if ($count1 > 0) {
            if ($count1 == 1) {
                $query1 = $query1->one();

                if ($this->id == null) {
                    $this->addError('starting_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }

                if (!($query1->id == $this->id)) {
                    $this->addError('finish_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }
            } elseif ($count1 > 1) {
                $this->addError('starting_date',
                    '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
            }
        }

        $query2 = Gamification::find()
            ->andWhere(['>=', 'starting_date', $this->starting_date])
            ->andWhere(['<=', 'finish_date', $this->finish_date]);
        $count2 = $query2->count();

        if ($count2 > 0) {
            if ($count2 == 1) {
                $query2 = $query2->one();

                if ($this->id == null) {
                    $this->addError('starting_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }

                if (!($query2->id == $this->id)) {
                    $this->addError('finish_date',
                        '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
                }
            } elseif ($count2 > 1) {
                $this->addError('starting_date',
                    '“E’ già presente una campagna nel periodo selezionato. 
                        È consentito avere una solo campagna attiva. 
                        Verifica l’intervallo di date inserito');
            }
        }
    }

    public function afterFind()
    {
        return parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('gamification', 'ID'),
            'titolo' => Yii::t('gamification', 'Titolo'),
            'descrizione' => Yii::t('gamification','Descrizione'),
            'starting_date' => Yii::t('gamification','Data inizio'),
            'finish_date' => Yii::t('gamification','Data fine'),
            'status' => Yii::t('gamification','Stato'),

            'utente_iscrizione' => Yii::t('gamification', 'Iscrizione alla piattaforma'),
            'utente_foto' => Yii::t('gamification', 'Caricamento di un\'immagine nel profilo'),
            'utente_colleg' => Yii::t('gamification', 'Collegamento con utente'),
            'community_creazione' => Yii::t('gamification', 'Creazione di una community'),
            'community_iscrizione' => Yii::t('gamification', 'Iscrizione di una community'),
            'notizie_creazione' => Yii::t('gamification', 'Creazione di una notizia'),
            'notizie_commento' => Yii::t('gamification', 'Commento ad una notizia'),
            'notizie_like' => Yii::t('gamification', 'Like ad una notizia'),
            'documenti_creazione' => Yii::t('gamification', 'Creazione di un documento'),
            'discussioni_creazione' => Yii::t('gamification', 'Creazione di una discussione'),
            'discussioni_commento' => Yii::t('gamification', 'Commento ad una discussione'),
            'discussioni_like' => Yii::t('gamification', 'Like ad una discussione'),
            'sondaggi_partec' => Yii::t('gamification', 'Partecipazione ad un sondaggio'),
            'created_by' => Yii::t('gamification', 'Creato da'),
            'created_at' => Yii::t('gamification', 'Creato il'),
            'updated_by' => Yii::t('gamification', 'Aggiornato da'),
            'updated_at' => Yii::t('gamification', 'Aggiornato il'),
            'deleted_by' => Yii::t('gamification', 'Eliminato da'),
            'deleted_at' => Yii::t('gamification', 'Aggiornato il'),
        ];
    }
}
