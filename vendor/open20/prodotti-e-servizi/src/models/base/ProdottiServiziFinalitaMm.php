<?php

namespace open20\prodottiservizi\models\base;

use Yii;
use open20\prodottiservizi\models\ProdottiFinalita;
use open20\prodottiservizi\models\ProdottiServizi;

/**
 * This is the base-model class for table "prodotti_servizi_finalita_mm".
 *
 * @property integer $id
 * @property integer $prodotto_id
 * @property integer $finalita_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property array $finalita
 * @property array $prodotto
 */
class ProdottiServiziFinalitaMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prodotti_servizi_finalita_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prodotto_id', 'finalita_id'], 'required'],
            [['prodotto_id', 'finalita_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['finalita_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProdottiFinalita::className(), 'targetAttribute' => ['finalita_id' => 'id']],
            [['prodotto_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProdottiServizi::className(), 'targetAttribute' => ['prodotto_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prodotto_id' => 'Prodotto ID',
            'finalita_id' => 'Finalita ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinalita()
    {
        return $this->hasOne(ProdottiFinalita::className(), ['id' => 'finalita_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdotto()
    {
        return $this->hasOne(ProdottiServizi::className(), ['id' => 'prodotto_id']);
    }
}