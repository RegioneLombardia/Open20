<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\rules
 * @category   CategoryName
 */

namespace open20\amos\admin\rules;

use open20\amos\admin\AmosAdmin;
use yii\rbac\Rule;

/**
 * Class FacilitatorsWidgetVisibilityRule
 * @package open20\amos\admin\rules
 */
class FacilitatorsWidgetVisibilityRule extends Rule
{
    public $name = 'facilitatorsWidgetVisibility';
    
    /**
     * @inheritdoc
     */
    public function execute($loggedUserId, $item, $params)
    {
        /** @var AmosAdmin $adminModule */
        $adminModule = AmosAdmin::instance();
        if (is_null($adminModule)) {
            return false;
        }
        return $adminModule->confManager->isFacilitatorsEnabled();
    }
}
