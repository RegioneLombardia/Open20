<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models
 * @category   CategoryName
 */

namespace open2\amos\ticket\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ticket_categorie_users_mm".
 */
class TicketCategorieUsersMm extends \open2\amos\ticket\models\base\TicketCategorieUsersMm
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
                parent::attributeLabels(), [
            ]);
    }
}
