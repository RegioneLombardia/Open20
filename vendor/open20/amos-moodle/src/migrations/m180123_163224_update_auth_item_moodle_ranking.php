<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\moodle\rules\ShowWidgetIconMoodleRankingRule;

/**
 * Class m180123_163224_update_auth_item_moodle_ranking
 */
class m180123_163224_update_auth_item_moodle_ranking extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleRanking::className(),
                'update' => true,
                'newValues' => [
                    'ruleName' => ShowWidgetIconMoodleRankingRule::className()
                ],
                'oldValues' => [
                    'ruleName' => null
                ]
            ]
        ];
    }
}
