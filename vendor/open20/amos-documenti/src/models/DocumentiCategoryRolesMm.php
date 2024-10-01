<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models
 * @category   CategoryName
 */

namespace open20\amos\documenti\models;

/**
 * Class DocumentiCategoryRolesMm
 * This is the model class for table "documenti_category_roles_mm".
 * @package open20\amos\documenti\models
 */
class DocumentiCategoryRolesMm extends \open20\amos\documenti\models\base\DocumentiCategoryRolesMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'documenti_categorie_id',
                'label' => $labels['documenti_categorie_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'role',
                'label' => $labels['role'],
                'type' => 'string'
            ],
        ];
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
