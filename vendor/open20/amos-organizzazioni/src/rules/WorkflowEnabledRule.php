<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\rules
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\rules;

use open20\amos\organizzazioni\Module;
use yii\rbac\Rule;

/**
 * Class WorkflowEnabledRule
 * @package open20\amos\organizzazioni\rules
 */
class WorkflowEnabledRule extends Rule
{
    public $name = 'workflowEnabledRule';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        /** @var Module $module */
        $module = Module::instance();
        if (is_null($module)) {
            return false;
        }
        return $module->enableWorkflow;
    }
}
