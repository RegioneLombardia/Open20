<?php

namespace open20\prodottiservizi\models\base;

use Yii;

/**
 * This is the base-model class for table "prodotti_soluzioni".
 *
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
abstract class ProdottiSoluzioni extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prodotti_soluzioni';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'icon'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('prodottiservizi', 'ID'),
            'name' => Yii::t('prodottiservizi', 'Name'),
            'icon' => Yii::t('prodottiservizi', 'Icon'),
            'created_at' => Yii::t('prodottiservizi', 'Created At'),
            'updated_at' => Yii::t('prodottiservizi', 'Updated At'),
            'deleted_at' => Yii::t('prodottiservizi', 'Deleted At'),
            'created_by' => Yii::t('prodottiservizi', 'Created By'),
            'updated_by' => Yii::t('prodottiservizi', 'Updated By'),
            'deleted_by' => Yii::t('prodottiservizi', 'Deleted By'),
        ];
    }
}