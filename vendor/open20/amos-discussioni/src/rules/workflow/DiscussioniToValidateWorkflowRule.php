<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace open20\amos\discussioni\rules\workflow;

use open20\amos\core\rules\ToValidateWorkflowContentRule;

class DiscussioniToValidateWorkflowRule extends ToValidateWorkflowContentRule
{

    public $name = 'discussioniToValidateWorkflow';
    public $validateRuleName = 'DiscussionValidate';

}