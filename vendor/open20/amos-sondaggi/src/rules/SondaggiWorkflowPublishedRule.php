<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\rules;

use open20\amos\core\rules\DefaultOwnContentRule;


class SondaggiWorkflowPublishedRule extends DefaultOwnContentRule
{
    public $name = 'sondaggiWorkflowPublished';

    public function execute($user, $item, $params)
    {
        if(\Yii::$app->controller->action->id == 'pubblica'){
            return true;
        }
        return false;
    }
}
