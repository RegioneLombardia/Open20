<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models\base
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models\base;

use Yii;
use open20\amos\sondaggi\AmosSondaggi;

/**
 * This is the base-model class for table "sondaggi_pubblicazione".
 *
 * @property string $ruolo
 * @property integer $sondaggi_id
 * @property integer $tipologie_entita
 * @property integer $entita_id
 * @property integer $text_end_html
 * @property integer $text_not_compilable_html
 * @property string $mail_message
 * @property string $mail_subject
 * @property string $text_not_compilable
 * @property string $text_end
 * @property string $text_end_title
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\sondaggi\models\Sondaggi $sondaggi
 */
class SondaggiPubblicazione extends \open20\amos\core\record\Record {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sondaggi_pubblicazione';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ruolo', 'sondaggi_id', 'tipologie_entita'], 'required'],
            [['sondaggi_id', 'tipologie_entita', 'entita_id', 'closing_at', 'created_by', 'updated_by', 'deleted_by', 'version', 'text_end_html', 'text_not_compilable_html'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'mail_message', 'mail_subject', 'text_not_compilable', 'text_end', 'text_end_title'], 'safe'],
            [['ruolo'], 'string', 'max' => 255],
            [['ruolo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ruolo' => AmosSondaggi::t('amossondaggi', 'Ruolo'),
            'sondaggi_id' => AmosSondaggi::t('amossondaggi', 'Sondaggio'),
            'tipologie_entita' => AmosSondaggi::t('amossondaggi', 'Tipologie attività'),
            'entita_id' => AmosSondaggi::t('amossondaggi', 'Attività formativa'),
            'closing_at' => AmosSondaggi::t('amossondaggi', 'Chiude il'),
            'text_end_html' => AmosSondaggi::t('amossondaggi', 'Messaggio di fine sondaggio in HTML'),
            'text_not_compilable_html' => AmosSondaggi::t('amossondaggi', 'Messaggio di sondaggio non compilabile in HTML'),
            'mail_message' => AmosSondaggi::t('amossondaggi', 'Testo della e-mail di notifica'),
            'mail_subject' => AmosSondaggi::t('amossondaggi', 'Oggetto della e-mail di notifica'),
            'text_not_compilable' => AmosSondaggi::t('amossondaggi', 'Messaggio di sondaggio non compilabile'),
            'text_end' => AmosSondaggi::t('amossondaggi', 'Messaggio di fine sondaggio'),
            'text_end_title' => AmosSondaggi::t('amossondaggi', 'Titolo della pagina di fine sondaggio'),
            'created_at' => AmosSondaggi::t('amossondaggi', 'Creato il'),
            'updated_at' => AmosSondaggi::t('amossondaggi', 'Aggiornato il'),
            'deleted_at' => AmosSondaggi::t('amossondaggi', 'Cancellato il'),
            'created_by' => AmosSondaggi::t('amossondaggi', 'Creato da'),
            'updated_by' => AmosSondaggi::t('amossondaggi', 'Aggiornato da'),
            'deleted_by' => AmosSondaggi::t('amossondaggi', 'Cancellato da'),
            'version' => AmosSondaggi::t('amossondaggi', 'Versione'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSondaggi() {
        return $this->hasOne(\open20\amos\sondaggi\models\Sondaggi::className(), ['id' => 'sondaggi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeiAttivitaFormativa() {
        return $this->hasOne(\frontend\modules\attivitaformative\models\PeiAttivitaFormative::className(), ['id' => 'entita_id']);
    }

}
