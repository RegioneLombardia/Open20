<?php

namespace open20\prodottiservizi\models;

use yii\helpers\Html;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "prodotti_servizi_accordion".
 */
class ProdottiServiziAccordion extends \open20\prodottiservizi\models\base\ProdottiServiziAccordion
{
    const UP_MOVE_OPERATION = 1;
    const DOWN_MOVE_OPERATION = 2;

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

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'string'
            ],
            [
                'slug' => 'is_visible',
                'label' => $labels['is_visible'],
                'type' => 'tinyint'
            ],
            [
                'slug' => 'position',
                'label' => $labels['position'],
                'type' => 'integer'
            ],
            [
                'slug' => 'prodotto_id',
                'label' => $labels['prodotto_id'],
                'type' => 'integer'
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

    public function isFirstPosition() {
        $query = ProdottiServiziAccordion::find()
            ->andWhere(['prodotto_id' => $this->prodotto_id])
            ->andWhere(['<', 'position', $this->position])
            ->one();
        return is_null($query);
    }

    public function isLastPosition() {
        $query = ProdottiServiziAccordion::find()
            ->andWhere(['prodotto_id' => $this->prodotto_id])
            ->andWhere(['>', 'position', $this->position])
            ->one();
        return is_null($query);
    }

    /**
     * @return string
     */
    public function getButtonsPosition() {
        $buttons = '';
        if(!$this->isFirstPosition()) {
            $buttons .= Html::button('<span class="mdi mdi-chevron-up"></span>', [
                'class' => 'btn btn-secondary move-up m-b-5',
                'title' => Yii::t('prodottiservizi', 'Sposta Sopra'),
                'data-accordion-id' => $this->id
            ]);
        }
        if(!$this->isLastPosition()) {
            $buttons .= Html::button('<span class="mdi mdi-chevron-down"></span>', [
                'class' => 'btn btn-secondary move-down m-b-5',
                'title' => Yii::t('prodottiservizi', 'Sposta Sotto'),
                'data-accordion-id' => $this->id
            ]);
        }
        return $buttons;
    }

    /**
     * @param $prodotto_id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function createPosition($prodotto_id) {
        $position = self::find()
            ->andWhere(['prodotto_id' => $prodotto_id])
            ->max('position');
        return is_null($position) ? 1 : $position + 1;
    }

    /**
     * @param $idProdotto ProdottiServizi
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getPreviousAccordion($idProdotto) {
        return ProdottiServiziAccordion::find()
            ->andWhere(['prodotto_id' => $idProdotto])
            ->andWhere(['<', 'position', $this->position])
            ->max('position');
    }

    /**
     * @param $idProdotto ProdottiServizi
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getFollowingAccordion($idProdotto) {
        return ProdottiServiziAccordion::find()
            ->andWhere(['prodotto_id' => $idProdotto])
            ->andWhere(['>', 'position', $this->position])
            ->min('position');
    }
}