<?php

namespace app\modules\cms\helpers;

/**
 * Class CmsHelper
 * @package app\modules\cms\helpers
 */
class CmsHelper extends \yii\helpers\Html
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
}
