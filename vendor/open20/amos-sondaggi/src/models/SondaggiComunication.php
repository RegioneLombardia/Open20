<?php

namespace open20\amos\sondaggi\models;

use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\sondaggi\AmosSondaggi;

/**
 * This is the model class for table "sondaggi_comunication".
 */
class SondaggiComunication extends \open20\amos\sondaggi\models\base\SondaggiComunication
{

    const TUTTI_GLI_ENTI_INVITO_SPEDITO          = 0;
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

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

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
}
