<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\rules\workflow
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\rules\workflow;

use open20\amos\core\rules\ToValidateWorkflowContentRule;

/**
 * Class ProfiloToValidateWorkflowRule
 * @package open20\amos\organizzazioni\rules\workflow
 */
class ProfiloToValidateWorkflowRule extends ToValidateWorkflowContentRule
{
    public $name = 'profiloToValidateWorkflow';
    public $validateRuleName = 'ProfiloValidate';
}
