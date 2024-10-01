<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\rules\ExprsOfIntAllowedStatesRule;

/**
 * Class m191120_112955_remove_expression_of_interests_draft_status_from_creator
 */
class m191120_112955_remove_expression_of_interests_draft_status_from_creator extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT,
                'update' => true,
                'newValues' => [
                    'removeParents' => [ExprsOfIntAllowedStatesRule::className()]
                ]
            ]
        ];
    }
}
