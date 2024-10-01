<?php

namespace open20\gamification\models\base;

use open20\amos\core\record\Record;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "punteggi_tot_users_gamif".
 *
 * @property integer $id
 * @property integer $gamification_id
 * @property integer $posizione
 * @property integer $user_id
 * @property string $nome_cognome
 * @property integer $totale
 * @property integer $profilo
 * @property integer $community
 * @property integer $notizie
 * @property integer $documenti
 * @property integer $discussioni
 * @property integer $sondaggi
 *
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 */
class PunteggiTotUtenti extends Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'punteggi_tot_users_gamif';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(
            parent::rules(),
            [
                [['totale', 'profilo', 'community', 'notizie', 'documenti', 'discussioni', 'sondaggi', 'posizione'], 'default', 'value' => 0],
                [['nome_cognome'], 'string', 'max' => 255],
                [['gamification_id', 'user_id', 'posizione', 'totale', 'profilo', 'community', 'notizie','documenti',
                    'discussioni', 'sondaggi', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
                [['gamification_id', 'user_id', 'content_id', 'datetime_attivita', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ]
        );

        return $rules;
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
            'id' => Yii::t('punteggi_tot_users_gamif', 'ID'),
            'gamification_id' => Yii::t('punteggi_tot_users_gamif', 'ID gamification'),
            'user_id' => Yii::t('punteggi_tot_users_gamif', 'ID Utente'),
            'posizione' => Yii::t('punteggi_tot_users_gamif', 'Posizione'),
            'nome_cognome' => Yii::t('punteggi_tot_users_gamif', 'Nome e cognome utente'),
            'totale' => Yii::t('punteggi_tot_users_gamif', 'Punteggio totale'),
            'profilo' => Yii::t('punteggi_tot_users_gamif','Punteggio profilo'),
            'community' => Yii::t('punteggi_tot_users_gamif','Punteggio community'),
            'notizie' => Yii::t('punteggi_tot_users_gamif','Punteggio notizie'),
            'documenti' => Yii::t('punteggi_tot_users_gamif','Punteggio documenti'),
            'discussioni' => Yii::t('punteggi_tot_users_gamif','Punteggio discussioni'),
            'sondaggi' => Yii::t('punteggi_tot_users_gamif',"Punteggio sondaggi"),

            'created_by' => Yii::t('punteggi_tot_users_gamif', 'Creato da'),
            'created_at' => Yii::t('punteggi_tot_users_gamif', 'Creato il'),
            'updated_by' => Yii::t('punteggi_tot_users_gamif', 'Aggiornato da'),
            'updated_at' => Yii::t('punteggi_tot_users_gamif', 'Aggiornato il'),
            'deleted_by' => Yii::t('punteggi_tot_users_gamif', 'Eliminato da'),
            'deleted_at' => Yii::t('punteggi_tot_users_gamif', 'Aggiornato il'),
        ];
    }
}
