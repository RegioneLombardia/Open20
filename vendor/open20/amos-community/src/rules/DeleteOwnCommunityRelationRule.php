<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\rules
 * @category   CategoryName
 */

namespace open20\amos\community\rules;

use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\projectmanagement\utility\ProjectManagementUtility;
use yii\rbac\Rule;

/**
 * Class DeleteOwnCommunitiesRule
 * @package open20\amos\community\rules
 */
class DeleteOwnCommunityRelationRule extends Rule
{
    public $name = 'deleteOwnCommunityRelation';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $modulePm = \Yii::$app->getModule('project_management');
        if(!empty($modulePm) && !empty($params['model'])){
            return \Yii::$app->user->can('open20\amos\projectmanagement\rules\PmDeleteOwnCommunityRelationRule', ['model' => $params['model']]) ;
        }
        else
            return true;
    }
}
