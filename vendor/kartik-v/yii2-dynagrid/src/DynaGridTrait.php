<?php

/**
 * @package   yii2-dynagrid
 * @version   1.5.1
 */

namespace kartik\dynagrid;

use Yii;

/**
 * Trait for dynagrid widgets
 *
 * @since 1.0
 */
trait DynaGridTrait
{
    /**
     * Gets the category translated description
     *
     * @param string  $cat the category 'grid', 'filter', or 'sort'
     * @param boolean $initCap whether to capitalize first letter.
     *
     * @return string
     */
    public static function getCat($cat, $initCap = false)
    {
        if ($initCap) {
            return ucfirst(static::getCat($cat, false));
        }
        switch ($cat) {
            case DynaGridStore::STORE_GRID:
                return Yii::t('kvdynagrid', 'grid');
            case DynaGridStore::STORE_SORT:
                return Yii::t('kvdynagrid', 'sort');
            case DynaGridStore::STORE_FILTER:
                return Yii::t('kvdynagrid', 'filter');
            default:
                return Yii::t('kvdynagrid', $cat);
        }
    }
}
