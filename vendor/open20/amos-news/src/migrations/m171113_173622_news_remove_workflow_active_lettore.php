<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\news\models\News;
use yii\helpers\ArrayHelper;

/**
 * Class m171113_173622_news_remove_workflow_active_lettore
 */
class m171113_173622_news_remove_workflow_active_lettore extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['LETTORE_NEWS']
                ]
            ]
        ];
    }
}
