<?php

namespace open20\amos\socialauth\models;

use Yii;
use yii\helpers\ArrayHelper;
use open20\amos\core\user\User;

/**
 * This is the model class for table "social_idm_user".
 *
 * @property integer $id
 * @property integer $user_id User ID
 * @property string $numeroMatricola
 * @property string $codiceFiscale
 * @property string $nome
 * @property string $cognome
 * @property string $ssoDN
 * @property string $tipoAutenticazione
 * @property string $ssoUrlLogout
 * @property string $responseBase64
 * @property string $emailAddress
 * @property string $livelloVerifica
 * @property string $nomeUtente
 * @property string $sesso
 * @property string $dataNascita
 * @property string $luogoNascita
 * @property string $provinciaNascita
 * @property string $statoNascita
 * @property string $identificativoUtente
 * @property string $cellulare
 * @property string $ragioneSociale
 * @property string $sedeLegale
 * @property string $partitaIVA
 * @property string $docIdentita
 * @property string $scadDocIdentita
 * @property string $domicilioFisico
 * @property string $domicilioDigitale
 * @property string $origineDatiUtente
 * @property string $statoValidazioneProfiloUtente
 * @property string $idComuneRegistrazione
 * @property string $accessMethod
 * @property string $accessLevel
 * @property string $rawData
 * @property User $user User
 */
class SocialIdmUser extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_idm_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numeroMatricola'], 'required'],
            [[
                'numeroMatricola',
                'codiceFiscale',
                'nome',
                'cognome',
                'ssoDN',
                'tipoAutenticazione',
                'ssoUrlLogout',
                'responseBase64',
                'emailAddress',
                'livelloVerifica',
                'nomeUtente',
                'sesso',
                'dataNascita',
                'luogoNascita',
                'provinciaNascita',
                'statoNascita',
                'identificativoUtente',
                'cellulare',
                'ragioneSociale',
                'sedeLegale',
                'partitaIVA',
                'docIdentita',
                'scadDocIdentita',
                'domicilioFisico',
                'domicilioDigitale',
                'origineDatiUtente',
                'statoValidazioneProfiloUtente',
                'idComuneRegistrazione',
                'accessMethod',
                'accessLevel'
            ], 'string', 'max' => 255],
            [[
                'rawData'
            ], 'string'],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Module::t('amossocialauth', 'User ID'),
            'numeroMatricola' => Yii::t('app', 'Numero di Matricola'),
            'codiceFiscale' => Yii::t('app', 'Codice Fiscale'),
            'nome' => Yii::t('app', 'Nome'),
            'cognome' => Yii::t('app', 'Cognome'),
            'ssoDN' => Yii::t('app', 'ssoDN'),
            'tipoAutenticazione' => Yii::t('app', 'Tipo di Autenticazione'),
            'ssoUrlLogout' => Yii::t('app', 'Url di Logout SSO'),
            'responseBase64' => Yii::t('app', 'Risposta BASE64'),
            'emailAddress' => Yii::t('app', 'Indirizzo Email'),
            'livelloVerifica' => Yii::t('app', 'Livello di Verifica'),
            'nomeUtente' => Yii::t('app', 'Nome Utente'),
            'sesso' => Yii::t('app', 'Sesso'),
            'dataNascita' => Yii::t('app', 'Data di Nascita'),
            'luogoNascita' => Yii::t('app', 'Luogo di Nascita'),
            'provinciaNascita' => Yii::t('app', 'Provincia di Nascita'),
            'statoNascita' => Yii::t('app', 'Stato di Nascita'),
            'identificativoUtente' => Yii::t('app', 'Identificativo Utente'),
            'cellulare' => Yii::t('app', 'Cellulare'),
            'ragioneSociale' => Yii::t('app', 'Ragione Sociale'),
            'sedeLegale' => Yii::t('app', 'Sede Legale'),
            'partitaIVA' => Yii::t('app', 'Partita IVA'),
            'docIdentita' => Yii::t('app', 'Documento di Identita'),
            'scadDocIdentita' => Yii::t('app', 'Scadenza Documento'),
            'domicilioFisico' => Yii::t('app', 'Domicilio Fisico'),
            'domicilioDigitale' => Yii::t('app', 'Domicilio Digitale'),
            'origineDatiUtente' => Yii::t('app', 'Origina Dati'),
            'statoValidazioneProfiloUtente' => Yii::t('app', 'Stato di Validazione Profilo'),
            'idComuneRegistrazione' => Yii::t('app', 'ID Comune di Registrazione'),
        ];
    }

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [];
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
