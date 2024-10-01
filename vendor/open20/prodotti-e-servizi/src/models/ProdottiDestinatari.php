<?php

namespace open20\prodottiservizi\models;

use Yii;
use yii\helpers\ArrayHelper;

use open20\amos\core\interfaces\SearchModelInterface;

/**
 * This is the model class for table "prodotti_destinatari".
 */
class ProdottiDestinatari extends \open20\prodottiservizi\models\base\ProdottiDestinatari implements
    SearchModelInterface
{
    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'parent',
                'label' => $labels['parent'],
                'type' => 'integer'
            ],
            [
                'slug' => 'icon',
                'label' => $labels['icon'],
                'type' => 'string'
            ],
        ];
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
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

    public function attributeHints()
    {
        return [
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    /**
     * @inheritdoc
     */
    public function globalSearch($searchParamsArray, $pageSize)
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function convertToSearchResult($model)
    {
        //
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdottiMm()
    {
        return $this->hasMany(ProdottiServiziDestinatariMm::className(), ['destinatario_id' => 'id']);
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getNProducts()
    {
        $query = ProdottiServiziDestinatariMm::find()
            ->joinWith('prodotto')
            ->andWhere(['destinatario_id' => $this->id])
            ->andWhere(['id_product_root' => null])
            ->count();
        return $query;
    }

    /**
     * @return ProdottiServiziDestinatariMm[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getProdottiSingle()
    {
        $query2 = ProdottiServiziDestinatariMm::find()
            ->select('prodotto_id')
            ->andWhere(['!=', 'destinatario_id', $this->id]);
        $query = ProdottiServizi::find()
            ->joinWith('destinatariMm')
            ->andWhere(['id_product_root' => null])
            ->andWhere(['destinatario_id' => $this->id])
            ->andWhere(['not in', 'prodotto_id', $query2])
            ->all();
        return $query;
    }

    /**
     * @return ProdottiServizi[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getProdottiMultiple()
    {
        $query2 = ProdottiServiziDestinatariMm::find()
            ->select('prodotto_id')
            ->andWhere(['!=', 'destinatario_id', $this->id]);
        $query = ProdottiServizi::find()
            ->joinWith('destinatariMm')
            ->andWhere(['id_product_root' => null])
            ->andWhere(['destinatario_id' => $this->id])
            ->andWhere(['in', 'prodotto_id', $query2])
            ->all();
        return $query;
    }
}