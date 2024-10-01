<?php

namespace app\modules\cms\helpers;

use luya\helpers\Inflector;
use Yii;
use yii\helpers\Html;


/**
 * Class CmsHelper
 * @package app\modules\cms\helpers
 */
class CmsHelper extends Html
{
    /**
     * @param string $fieldName
     * @param array $viewFields
     * @return bool
     */
    public static function in_arrayCmsViewField($fieldName, array $viewFields)
    {
        foreach ($viewFields as $field) {
            if ($field->name == $fieldName) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 
     * @param type $alias
     * @return type
     */
    public static function slugifyAlias($alias)
    {
        return Inflector::slug(strtolower($alias), '-', true, false);
    }
    
    /**
     * 
     * @param string $key
     * @param string $default
     * @return string
     */
    public static function itemQueryStringValue($key, $default = '')
    {
        $value = $default;
        $getValues = Yii::$app->request->get();
        if(isset($getValues[$key]))
        {
            $value = $getValues[$key];
        }
        return $value;
    }
}
