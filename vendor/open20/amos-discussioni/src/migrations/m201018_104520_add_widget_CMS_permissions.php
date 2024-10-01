<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\core\rules\UserValidatorContentRule;
use open20\amos\news\models\News;
use open20\amos\news\widgets\icons\WidgetIconNewsDaValidare;
use yii\rbac\Permission;
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */


class m201018_104520_add_widget_CMS_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsCmsAllDiscussioni::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI']
            ],
            [
                'name' => \open20\amos\discussioni\widgets\graphics\WidgetGraphicsCmsAllDiscussioni::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['LETTORE_DISCUSSIONI']
                ]
            ],
            

        ];
    }
}