<?php

namespace open20\amos\sondaggi\models\base;

use Yii;

/**
 * This is the base-model class for table "sondaggi_domande_rule".
 *
 * @property integer $id
 * @property string $nome
 * @property string $descrizione
 * @property string $namespace
 * @property string $standard
 * @property integer $custom
 * @property string $codice_custom
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\sondaggi\models\SondaggiDomandeRuleMm[] $sondaggiDomandeRuleMms
 */
class SondaggiDomandeRule extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sondaggi_domande_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['descrizione', 'codice_custom', 'namespace', 'standard'], 'string'],
            [['custom', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nome'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amossondaggi', 'ID'),
            'nome' => Yii::t('amossondaggi', 'Nome'),
            'descrizione' => Yii::t('amossondaggi', 'Descrizione'),
            'namespace' => Yii::t('amossondaggi', 'Namespace della rule'),
            'standard' => Yii::t('amossondaggi', 'Rule standard'),
            'custom' => Yii::t('amossondaggi', 'Custom'),
            'codice_custom' => Yii::t('amossondaggi', 'Codice Custom'),
            'created_at' => Yii::t('amossondaggi', 'Creato il'),
            'updated_at' => Yii::t('amossondaggi', 'Aggiornato il'),
            'deleted_at' => Yii::t('amossondaggi', 'Cancellato il'),
            'created_by' => Yii::t('amossondaggi', 'Creato da'),
            'updated_by' => Yii::t('amossondaggi', 'Aggiornato da'),
            'deleted_by' => Yii::t('amossondaggi', 'Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSondaggiDomandeRuleMms()
    {
        return $this->hasMany(\open20\amos\sondaggi\models\SondaggiDomandeRuleMm::className(),
                ['sondaggi_domande_rule_id' => 'id']);
    }
}