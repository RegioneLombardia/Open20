<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\rules\workflow
 * @category   CategoryName
 */

namespace open20\amos\events\rules\workflow;

use open20\amos\core\rules\ToValidateWorkflowContentRule;
use open20\amos\events\AmosEvents;

/**
 * Class EventsToValidateWorkflowRule
 * @package open20\amos\events\rules\workflow
 */
class EventsToValidateWorkflowRule extends ToValidateWorkflowContentRule
{
    public $name = 'eventsToValidateWorkflow';
    public $validateRuleName = 'EventValidate';
    
    /**
     * @inheridoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var AmosEvents $eventsModule */
        $eventsModule = AmosEvents::instance();
        if ($eventsModule->enableAgid) {
            return false;
        }
        return parent::ruleLogic($user, $item, $params, $model);
    }
}
