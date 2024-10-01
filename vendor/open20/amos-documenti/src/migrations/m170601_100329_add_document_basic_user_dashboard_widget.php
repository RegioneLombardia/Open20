<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;

/**
 * Class m170601_100329_add_document_basic_user_dashboard_widget
 */
class m170601_100329_add_document_basic_user_dashboard_widget extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [
            [
                'name' => WidgetIconDocumentiDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],

        ];
    }
}