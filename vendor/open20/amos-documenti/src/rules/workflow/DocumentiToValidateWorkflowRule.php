<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace  open20\amos\documenti\rules\workflow;

use open20\amos\core\rules\ToValidateWorkflowContentRule;

class DocumentiToValidateWorkflowRule extends ToValidateWorkflowContentRule
{

    public $name = 'documentiToValidateWorkflow';
    public $validateRuleName = 'DocumentValidate';

}