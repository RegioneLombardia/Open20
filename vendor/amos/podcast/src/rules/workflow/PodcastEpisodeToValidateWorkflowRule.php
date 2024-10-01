<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace amos\podcast\rules\workflow;

use open20\amos\core\rules\ToValidateWorkflowContentRule;

class PodcastEpisodeToValidateWorkflowRule extends ToValidateWorkflowContentRule
{

    public $name = 'podcastEpisodeToValidateWorkflow';
    public $validateRuleName = 'PodcastEpisodeValidate';

}