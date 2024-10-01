<?php

namespace open20\amos\comuni\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "istat_comuni_cap".
 */
class IstatComuniCap extends \open20\amos\comuni\models\base\IstatComuniCap
{

    /*public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::_WORKFLOW)->getInitialStatusId();
        }
    }*/

    public function representingColumn()
    {
            return [
                //inserire il campo o i campi rappresentativi del modulo
                    ];
    }

    public function attributeHints(){
        return [
                    ];
    }

    /**
    * Returns the text hint for the specified attribute.
    * @param string $attribute the attribute name
    * @return string the attribute hint
    */
    public function getAttributeHint($attribute) {
            $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    /**
    * Scommentare le seguenti function(), gli attributi sopra  e gli "use"
    * nel caso il modulo necessiti di regole di pubblicazione e personalizzarle
    * a piacimento. E' necessario per poter utilizzare le regole di
    * pubblicazione creare la classe Istat Comuni CapQuery.php
    * che conterrà le query specifiche per gestire la pubblicazione
    * dei contenuti per differenti destinatari.
    * Nelle function() sotto facciamo il merge con le altre function()
    * eventualmente già presenti per il model
    */


    public function rules()
    {
            return ArrayHelper::merge(parent::rules(), [
            //[['regola_pubblicazione', 'destinatari', 'validatori'], 'safe'],
                    ]);
    }

    public function attributeLabels()
    {
        return
        ArrayHelper::merge(
            parent::attributeLabels(),
            [
            //'tagValues' => '',
        //'regola_pubblicazione' => 'Pubblicata per',
        //'destinatari' => 'Per i condominii',
                    ]);
    }
    /*
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'CwhNetworkBehaviors' => [
            'class' => CwhNetworkBehaviors::className(),
        ],
        [
            'class' => SimpleWorkflowBehavior::className(),
            'defaultWorkflowId' => self::_WORKFLOW,
            'propagateErrorsToModel' => true,
        ]
    ]);
    }

    public static function find()
    {
        $Istat Comuni CapQuery = new Istat Comuni CapQuery(get_called_class());
        $Istat Comuni CapQuery->andWhere('istat Comuni Cap.deleted_at IS NULL');
        return $Istat Comuni CapQuery;
    }
    */

    public static function getCapSospesi()
    {
        return self::find(['sospeso' => 1]);
    }

    public static function getCapAttivi()
    {
        return self::find(['sospeso' => 0]);
    }


}
