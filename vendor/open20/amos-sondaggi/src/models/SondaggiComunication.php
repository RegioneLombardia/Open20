<?php

namespace open20\amos\sondaggi\models;

use open20\amos\core\user\User;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\SondaggiInvitations;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidValueException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sondaggi_comunication".
 */
class SondaggiComunication extends \open20\amos\sondaggi\models\base\SondaggiComunication
{

    const TUTTI_GLI_ENTI_INVITO_SPEDITO         = 0;
    const TUTTI_GLI_ENTI_COMPILATO              = 1;
    const TUTTI_GLI_ENTI_INVITATI_NON_COMPILATO = 2;

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

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(), [
                    'name' => AmosSondaggi::t('amossondaggi', 'Nome'),
                    'subject' => AmosSondaggi::t('amossondaggi', 'Oggetto'),
                    'message' => AmosSondaggi::t('amossondaggi', 'Messaggio'),
                    'query' => AmosSondaggi::t('amossondaggi', 'Query'),
                    'count' => AmosSondaggi::t('amossondaggi', 'Conteggio')
        ]);
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'sondaggi_id',
                'label' => $labels['sondaggi_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'subject',
                'label' => $labels['subject'],
                'type' => 'string'
            ],
            [
                'slug' => 'message',
                'label' => $labels['message'],
                'type' => 'text'
            ],
            [
                'slug' => 'query',
                'label' => $labels['query'],
                'type' => 'text'
            ],
            [
                'slug' => 'count',
                'label' => $labels['count'],
                'type' => 'integer'
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
     * @param $target
     * @return array
     */
    public function getFilterData($target = null, $isCommunitySurvey = false) {
        $data = [];
        if (!is_null($target)) {
            $this->target = $target;
        }

        if ($this->target == SondaggiInvitations::TARGET_USERS) {
            if (!$isCommunitySurvey) {
                $data = [
                    SondaggiComunication::TUTTI_GLI_ENTI_INVITO_SPEDITO => AmosSondaggi::t('amossondaggi', '#all_users_invited_to_poll'),
                    SondaggiComunication::TUTTI_GLI_ENTI_COMPILATO => AmosSondaggi::t('amossondaggi', '#users_poll_compiled'),
                    SondaggiComunication::TUTTI_GLI_ENTI_INVITATI_NON_COMPILATO => AmosSondaggi::t('amossondaggi', '#users_invited_to_poll_not_compiled')
                ];
            } else {
                $data = [
                    SondaggiComunication::TUTTI_GLI_ENTI_INVITO_SPEDITO => AmosSondaggi::t('amossondaggi', '#all_community_users_invited_to_poll'),
                    SondaggiComunication::TUTTI_GLI_ENTI_COMPILATO => AmosSondaggi::t('amossondaggi', '#community_users_poll_compiled'),
                    SondaggiComunication::TUTTI_GLI_ENTI_INVITATI_NON_COMPILATO => AmosSondaggi::t('amossondaggi', '#community_users_invited_to_poll_not_compiled')
                ];
            }
        }
        else if ($this->target == SondaggiInvitations::TARGET_ORGANIZATIONS) {
            $data = [
                SondaggiComunication::TUTTI_GLI_ENTI_INVITO_SPEDITO => AmosSondaggi::t('amossondaggi', '#all_organizations_invited_to_poll'),
                SondaggiComunication::TUTTI_GLI_ENTI_COMPILATO => AmosSondaggi::t('amossondaggi', '#organizations_poll_compiled'),
                SondaggiComunication::TUTTI_GLI_ENTI_INVITATI_NON_COMPILATO => AmosSondaggi::t('amossondaggi', '#organizations_invited_to_poll_not_compiled')
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getTargetLabel()
    {
        return [
            SondaggiInvitations::TARGET_USERS => AmosSondaggi::t('amossondaggi', 'Utenti'),
            SondaggiInvitations::TARGET_ORGANIZATIONS => AmosSondaggi::t('amossondaggi', 'Organizzazioni'),
        ];
    }

    /**
     * Aggiorna i campi 'query' e 'count' del model
     * @param bool $save Se true il model verra' salvato, se false (o viene
     * omesso) il model non verra' salvato
     * @throws InvalidArgumentException Se gli attributi 'type' o 'target' del
     * model non sono valorizzati
     * @throws InvalidValueException Se i valori degli attributi 'type' o
     * 'target' non sono fra quelli previsti
     */
    public function updateQueryAndCount($save = false)
    {
        if (is_null($this->type)) {
            throw new InvalidArgumentException('Type non definito');
        }
        if (is_null($this->target)) {
            throw new InvalidArgumentException('Target non definito');
        }
        if (empty($this->sondaggi_id)) {
            throw new InvalidArgumentException('ID del sondaggio non presente');
        }
        
        $sondaggio = $this->sondaggi;
        if (empty($sondaggio)) {
            throw new InvalidArgumentException('Errore nel recupero dei dati'
                . ' del sondaggio');
        }
        
        $this->query = null;
        $this->count = 0;

        switch ($this->target) {
            case SondaggiInvitations::TARGET_USERS:
                switch ($this->type) {
                    case self::TUTTI_GLI_ENTI_INVITO_SPEDITO:
                        $q = $sondaggio->getInvitedUsers();
                        break;
                    case self::TUTTI_GLI_ENTI_COMPILATO:
                        $q = $sondaggio->getUtentiCheHannoCompilato();
                        break;
                    case self::TUTTI_GLI_ENTI_INVITATI_NON_COMPILATO:
                        $idsUtentiCheHannoCompilato =
                            $sondaggio->getUtentiCheHannoCompilato()
                            ->select(User::tableName() . '.id')->column();
                        $q = $sondaggio->getUtentiInvitatiNonCompilato(
                            $idsUtentiCheHannoCompilato);
                        break;
                    default:
                        throw new InvalidValueException('Valore di type non'
                            . ' riconosciunto (' . $this->type . ')');
                }
                break;
            case SondaggiInvitations::TARGET_ORGANIZATIONS:
                switch ($this->type) {
                    case self::TUTTI_GLI_ENTI_INVITO_SPEDITO:
                        $q = $sondaggio->getEntiInvitati();
                        break;
                    case self::TUTTI_GLI_ENTI_COMPILATO:
                        $q = $sondaggio->getEntiCheHannoCompilato();
                        break;
                    case self::TUTTI_GLI_ENTI_INVITATI_NON_COMPILATO:
                        $idsEntiCheHannoCompilato =
                            $sondaggio->getEntiCheHannoCompilato()
                            ->select(Profilo::tableName() . '.id')->column();
                        $q = $sondaggio->getEntiInvitatiNonCompilato(
                            $idsEntiCheHannoCompilato);
                        break;
                    default:
                        throw new InvalidValueException('Valore di type non'
                            . ' riconosciunto (' . $this->type . ')');
                }
                break;
            default:
                throw new InvalidValueException('Valore di target non'
                    . ' riconosciunto (' . $this->target . ')');
        }

        $this->query = $q->createCommand()->rawSql;
        $this->count = $q->count();

        if (!empty($save)) {
            $this->save();
        }
    }
}
