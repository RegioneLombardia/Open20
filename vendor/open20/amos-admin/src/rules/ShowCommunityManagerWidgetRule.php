<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\rules
 * @category   CategoryName
 */

namespace open20\amos\admin\rules;

use yii\rbac\Rule;

/**
 * Class ShowCommunityManagerWidgetRule
 * @package open20\amos\admin\rules
 */
class ShowCommunityManagerWidgetRule extends Rule
{
    public $name = 'showCommunityManagerWidget';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $communityModule = \Yii::$app->getModule('community');
        return (!is_null($communityModule));
    }
}
