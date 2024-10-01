<?php

namespace open20\prodottiservizi\models\base;

use Yii;
use open20\prodottiservizi\models\ProdottiServizi;

/**
 * This is the base-model class for table "prodotti_servizi_accordion".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $is_visible
 * @property integer $position
 * @property integer $prodotto_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\prodottiservizi\models\ProdottiServizi $prodotto
 */
class  ProdottiServiziAccordion extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prodotti_servizi_accordion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','description'], 'required'],
            [['is_visible', 'position', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'prodotto_id'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['prodotto_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProdottiServizi::className(), 'targetAttribute' => ['prodotto_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('prodottiservizi', 'ID'),
            'title' => Yii::t('prodottiservizi', 'Title'),
            'description' => Yii::t('prodottiservizi', 'Content'),
            'is_visible' => Yii::t('prodottiservizi', 'Is Visible'),
            'position' => Yii::t('prodottiservizi', 'Position'),
            'prodotto_id' => Yii::t('prodottiservizi', 'Prodotto ID'),
            'created_at' => Yii::t('prodottiservizi', 'Created At'),
            'updated_at' => Yii::t('prodottiservizi', 'Updated At'),
            'deleted_at' => Yii::t('prodottiservizi', 'Deleted At'),
            'created_by' => Yii::t('prodottiservizi', 'Created By'),
            'updated_by' => Yii::t('prodottiservizi', 'Updated By'),
            'deleted_by' => Yii::t('prodottiservizi', 'Deleted By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdotto()
    {
        return $this->hasOne(ProdottiServizi::className(), ['id' => 'prodotto_id']);
    }
}