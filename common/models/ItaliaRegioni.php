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
 * This is the model class for table "italia_regioni".
 *
 * @property integer $id
 * @property string $nome
 *
 * @property ItaliaComuni[] $italiaComunis
 * @property ItaliaProvince[] $italiaProvinces
 */
class ItaliaRegioni extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'italia_regioni';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'string', 'max' => 50]
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItaliaComunis()
    {
        return $this->hasMany(ItaliaComuni::className(), ['regione_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItaliaProvinces()
    {
        return $this->hasMany(ItaliaProvince::className(), ['regione_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ItaliaRegioniQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItaliaRegioniQuery(get_called_class());
    }
}
