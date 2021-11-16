<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

/**
 * m191028_105817_add_admin_attach_dash_email_organizzazioni_models_classname
 *
 * Add these models in to models_classname table for b.c. scopes
 * - admin
 * - attachments-
 * - dashboard
 * - email
 * - organizzazioni
 * 
 */

class m191107_110317_add_admin_attach_dash_email_organizzazioni_models_classname extends \yii\db\Migration
{
    /**
     * Somewhere something was changed
     * @var type 
     */
    protected  $tableName;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->tableName = '{{%models_classname}}';
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert($this->tableName, [
            'classname' => 'open20\amos\admin\models\UserProfile',
            'module' => 'admin',
            'label' => 'Admin'
        ]);
        
        $this->insert($this->tableName, [
            'classname' => 'open20\amos\organizzazioni\models\Profilo',
            'module' => 'organizzazioni',
            'label' => 'Organizzazioni'
        ]);
        
        $this->insert($this->tableName, [
            'classname' => 'openinnovation\organizations\models\Organizations',
            'module' => 'organizations',
            'label' => 'organizations'
        ]);
        
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete($this->tableName, [
            'classname' => \open20\amos\admin\models\UserProfile::classname(),
            'module' => open20\amos\admin\AmosAdmin::getModuleName(),
            'label' => 'Admin'
        ]);
        
        $this->delete($this->tableName, [
            'classname' => \open20\amos\organizzazioni\models\Profilo::classname(),
            'module' => open20\amos\organizzazioni\Module::getModuleName(),
            'label' => 'Organizzazioni'
        ]);
        
        return true;
    }
}
