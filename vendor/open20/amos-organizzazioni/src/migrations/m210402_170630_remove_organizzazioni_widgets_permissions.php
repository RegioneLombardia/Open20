<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210402_170630_remove_organizzazioni_widgets_permissions
 */
class m210402_170630_remove_organizzazioni_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }
    
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Profilo';
        
        return [
            [
                'name' => 'open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloDashboard',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconProfiloDashboard',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'PROFILO_VALIDATOR', 'LETTORE_ORGANIZZAZIONI']
            ]
        ];
    }
}
