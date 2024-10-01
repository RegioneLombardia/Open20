<?php

namespace open20\socialwall\models;

use Yii;
use yii\helpers\ArrayHelper;

use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;

/**
 * This is the model class for table "socialwall_live_contents".
 */
class SocialwallLiveContents extends \open20\socialwall\models\base\SocialwallLiveContents implements
    ContentModelSearchInterface,
    SearchModelInterface
{
    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
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

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'socialwall_id',
                'label' => $labels['socialwall_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'json_data_content',
                'label' => $labels['json_data_content'],
                'type' => 'text'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchDefaultOrder($dataProvider)
    {
        //
    }


    /**
     * @inheritdoc
     */
    public function searchOwnInterestsQuery($params)
    {
        //
    }


    /**
     * @inheritdoc
     */
    public function searchAllQuery($params)
    {
        //
    }


    /**
     * @inheritdoc
     */
    public function searchCreatedByMeQuery($params)
    {
        //
    }


    /**
     * @inheritdoc
     */
    public function searchToValidateQuery($params)
    {
        //
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


}
