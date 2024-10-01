<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m240130_164300_add_permissions_community_widget_videoconferenza extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\videoconference\widgets\icons\WidgetIconVideoconference::class,
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],
        ];
    }
}