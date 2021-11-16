<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\rules
 * @category   CategoryName
 */

namespace open20\amos\utility\rules;

use yii\rbac\Rule;

/**
 * Class WidgetIconUtilityRule
 * @package open20\amos\utility\rules
 */
class WidgetIconUtilityRule extends Rule
{
    public $name = 'widgetIconUtility';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return true;
    }
}
