<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models
 * @category   CategoryName
 */

namespace open20\agid\organizationalunit\models;

/**
 * Class AgidOrganizationalUnitContentTypeRoles
 * This is the model class for table "".
 * @package 
 */
class AgidOrganizationalUnitContentTypeRoles extends \open20\agid\organizationalunit\models\base\AgidOrganizationalUnitContentTypeRoles
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

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'agid_organizational_unit_content_type_id',
                'label' => $labels['agid_organizational_unit_content_type_id'],
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
