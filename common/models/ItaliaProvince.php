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
 * This is the model class for table "italia_province".
 *
 * @property integer $id
 * @property string $nome
 * @property integer $regione_id
 * @property string $sigla
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 *
 * @property ItaliaComuni[] $italiaComunis
 * @property ItaliaRegioni $regione
 * @property OperatoriIefp[] $operatoriIefps
 * @property OperatoriIefp[] $operatoriIefps0
 * @property OperatoriIefp[] $operatoriIefps1
 */
class ItaliaProvince extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'italia_province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regione_id'], 'required'],
            [['regione_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nome'], 'string', 'max' => 20],
            [['sigla'], 'string', 'max' => 2]
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
            'regione_id' => \Yii::t('amosplatform', 'Regione ID'),
            'sigla' => \Yii::t('amosplatform', 'Sigla'),
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
    public function getItaliaComunis()
    {
        return $this->hasMany(ItaliaComuni::className(), ['provincia_id' => 'id']);
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
     * @return ItaliaProvinceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItaliaProvinceQuery(get_called_class());
    }
}
