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

use open20\amos\admin\models\UserProfile;
use open20\amos\core\rules\BasicContentRule;

/**
 * Class AssociateProfiloUserToOrganizationsRule
 * @package open20\amos\organizzazioni\rules
 */
class AssociateProfiloUserToOrganizationsRule extends BasicContentRule
{
    public $name = 'associateProfiloUserToOrganizations';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if ($model instanceof UserProfile) {
            return ($user == $model->user_id);
        }
        return false;
    }
}
