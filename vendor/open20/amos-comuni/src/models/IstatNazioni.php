<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\models;

use Yii;

//use backend\modules\cwh\behaviors\CwhNetworkBehaviors;
//use backend\modules\eventi\models\query\EventiQuery;
//use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "istat_nazioni".
 */
class IstatNazioni extends \open20\amos\comuni\models\base\IstatNazioni
{
//const _WORKFLOW = 'DiscussioniTopicWorkflow';

//public $regola_pubblicazione;
//public $destinatari;
//public $validatori;

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

    /**
     * Scommentare le seguenti function(), gli attributi sopra  e gli "use"
     * nel caso il modulo necessiti di regole di pubblicazione e personalizzarle
     * a piacimento. E' necessario per poter utilizzare le regole di
     * pubblicazione creare la classe Query.php
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
    /*
    public function attributeLabels()
    {
    return
    ArrayHelper::merge(
    parent::attributeLabels(),
    [
    'tagValues' => '',
    'regola_pubblicazione' => 'Pubblicata per',
    'destinatari' => 'Per i condominii',
    ]);
    }

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
    $Query = new Query(get_called_class());
    $Query->andWhere('.deleted_at IS NULL');
    return $Query;
    }
    */

    /**
     * Restituisce il percorso del marker, da personalizzare a piacimento
     * @return string Il percorso del marker che sarà utilizzato nella mappa
     */
    public function getIconaMarker()
    {
        return null;
    }

    /**
     * Restituisce il colore della categoria
     */
    public function getColoreCategoria()
    {
        return NULL; //da personalizzare
    }

    /**
     * Restituisce il nome della categoria per la legenda
     */
    public function getNomeLegenda()
    {
        return NULL; //da personalizzare
    }

    /**
     * Funzione che crea gli eventi da visualizzare sulla mappa in caso di più eventi legati al singolo model
     * Andrà valorizzato il campo array a true nella configurazione della vista calendario nella index
     */
    public function getEvents()
    {
        return NULL; //da personalizzare
    }

    /**
     * Restituisce l'url per il calendario dell'attività
     */
    public function getUrlEvento()
    {
        return NULL; //da personalizzare magari con Yii::$app->urlManager->createUrl([]);
    }

    /**
     * Restituisce il colore associato all'evento
     */
    public function getColoreEvento()
    {
        return NULL; //da personalizzare
    }

    /**
     * Restituisce il titolo, possono essere anche più dati, associato all'evento
     */
    public function getTitoloEvento()
    {
        return NULL; //da personalizzare
    }

    /**
     * Restituisce un'immagine se associata al model
     */
    public function getAvatarUrl($dimension = 'small')
    {
        $url = '/img/img_default.jpg';
//funzione da implementare
        return $url;
    }

    /**
     *   Restituisce il testo da inserire nella visualizzazione a icona (una specie di descrizione)
     */
    public function getTestoIcon()
    {
//da personalizzare, è necessario adattare anche la view _icon definendo un'altezza fissa
//o usando flexbox / display table. Se si usa un'altezza fissa
//troncare il testo in più
        return NULL;
    }

    /**
     *   Restituisce il testo da inserire nella visualizzazione a Lista
     */
    public function getTestoItem()
    {
//da personalizzare
        return NULL;
    }

    /**
     *   Restituisce il numero di telefono di default
     */
    public function getTelefonoDefault()
    {
//da personalizzare
        return NULL;
    }

    /**
     *   Restituisce l'email di default
     */
    public function getEmailDefault()
    {
//da personalizzare
        return NULL;
    }

    /**
     *   Restituisce il campo dei contatti in comune per la view Icon
     */
    public function getContattiComune()
    {
//da personalizzare
        return NULL;
    }

}
