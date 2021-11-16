<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace common\models;


/**
 * This is the model class for table "italia_comuni".
 *
 * @property integer $id
 * @property string $nome
 * @property string $codice_catastale
 * @property integer $regione_id
 * @property integer $provincia_id
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 *
 * @property ItaliaProvince $provincia
 * @property ItaliaRegioni $regione
 * @property OperatoriIefp[] $operatoriIefps
 * @property OperatoriIefp[] $operatoriIefps0
 * @property OperatoriIefp[] $operatoriIefps1
 */
class ItaliaComuni extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'italia_comuni';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'regione_id', 'provincia_id'], 'required'],
            [['nome'], 'string'],
            [['regione_id', 'provincia_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['codice_catastale'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('amosplatform', 'ID'),
            'nome' => \Yii::t('amosplatform', 'Nome'),
            'codice_catastale' => \Yii::t('amosplatform', 'Codice Catastale'),
            'regione_id' => \Yii::t('amosplatform', 'Regione ID'),
            'provincia_id' => \Yii::t('amosplatform', 'Provincia ID'),
            'created_by' => \Yii::t('amosplatform', 'Creato da'),
            'created_at' => \Yii::t('amosplatform', 'Creato il'),
            'updated_by' => \Yii::t('amosplatform', 'Aggiornato da'),
            'updated_at' => \Yii::t('amosplatform', 'Aggiornato il'),
            'deleted_by' => \Yii::t('amosplatform', 'Cancellato da'),
            'deleted_at' => \Yii::t('amosplatform', 'Cancellato il'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvincia()
    {
        return $this->hasOne(ItaliaProvince::className(), ['id' => 'provincia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegione()
    {
        return $this->hasOne(ItaliaRegioni::className(), ['id' => 'regione_id']);
    }
  
    /**
     * @inheritdoc
     * @return ItaliaComuniQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItaliaComuniQuery(get_called_class());
    }
}
