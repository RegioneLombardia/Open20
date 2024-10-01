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
* This is the base-model class for table "sondaggi_stato".
*
    * @property integer $id
    * @property string $stato
    * @property string $descrizione
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    * @property integer $version
    *
            * @property \open20\amos\sondaggi\models\Sondaggi[] $sondaggis
    */
class SondaggiStato extends \open20\amos\core\record\Record
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'sondaggi_stato';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['descrizione'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['stato'], 'string', 'max' => 255]
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => AmosSondaggi::t('amossondaggi', 'ID'),
    'stato' => AmosSondaggi::t('amossondaggi', 'Stato'),
    'descrizione' => AmosSondaggi::t('amossondaggi', 'Descrizione'),
    'created_at' => AmosSondaggi::t('amossondaggi', 'Creato il'),
    'updated_at' => AmosSondaggi::t('amossondaggi', 'Aggiornato il'),
    'deleted_at' => AmosSondaggi::t('amossondaggi', 'Cancellato il'),
    'created_by' => AmosSondaggi::t('amossondaggi', 'Creato da'),
    'updated_by' => AmosSondaggi::t('amossondaggi', 'Aggiornato da'),
    'deleted_by' => AmosSondaggi::t('amossondaggi', 'Cancellato da'),
    'version' => AmosSondaggi::t('amossondaggi', 'Versione numero'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSondaggis()
    {
    return $this->hasMany(\open20\amos\sondaggi\models\Sondaggi::className(), ['sondaggi_stato_id' => 'id']);
    }
}
