<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use backend\modules\operators\rules\CreateOperatorRule;
use backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser;
use yii\rbac\Permission;


/**
 * Class m210317_123600_update_roles */
class m210317_123600_update_roles extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [
    
            [
                'name' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsCmsUltimiDocumenti::className(),
                'update' => true,
                'newValues' => [                    
                    'removeParents' => ['LETTORE_DOCUMENTI', 'AMMINISTRATORE_DOCUMENTI'],
                ],
            ],  

        ];
    }
}
