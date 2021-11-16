<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace open20\amos\news\rules\workflow;

use open20\amos\core\rules\ToValidateWorkflowContentRule;

class NewsToValidateWorkflowRule extends ToValidateWorkflowContentRule
{

    public $name = 'newsToValidateWorkflow';
    public $validateRuleName = 'NewsValidate';

}