<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use backend\modules\operators\rules\CreateOperatorRule;
use backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser;
use yii\rbac\Permission;


/**
 * Class m210319_180900_update_roles */
class m210319_180900_update_roles extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [
    
            [
                'name' => \open20\amos\news\widgets\graphics\WidgetGraphicsCmsUltimeNews::className(),
                'update' => true,
                'newValues' => [                    
                    'removeParents' => ['LETTORE_NEWS', 'AMMINISTRATORE_NEWS'],
                ],
            ],  

        ];
    }
}
