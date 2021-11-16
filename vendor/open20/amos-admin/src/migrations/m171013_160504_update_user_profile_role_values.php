<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;

/**
 * Class m171013_160504_update_user_profile_role_values
 */
class m171013_160504_update_user_profile_role_values extends Migration
{
    private $tableName = 'user_profile_role';
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ok = $this->truncateRoleTable();
        if (!$ok) {
            return false;
        }
        try {
            $this->insert($this->tableName, ['id' => 1, 'name' => 'Freelance/Consultant', 'enabled' => 1, 'order' => 10]);
            $this->insert($this->tableName, ['id' => 2, 'name' => 'Entrepreneur', 'enabled' => 1, 'order' => 20]);
            $this->insert($this->tableName, ['id' => 3, 'name' => 'Teacher/researcher/collaborator of an university or a research organization', 'enabled' => 1, 'order' => 30]);
            $this->insert($this->tableName, ['id' => 4, 'name' => 'Employee/collaborator of an enterprise', 'enabled' => 1, 'order' => 40]);
            $this->insert($this->tableName, ['id' => 5, 'name' => 'Employee/collaborator of a public administration', 'enabled' => 1, 'order' => 50]);
            $this->insert($this->tableName, ['id' => 6, 'name' => 'Employee/collaborator of subjects working in social (associations, foundations, onlus, etc.)', 'enabled' => 1, 'order' => 60]);
            $this->insert($this->tableName, ['id' => 7, 'name' => 'Other', 'enabled' => 1, 'order' => 1000]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Errore durante l'aggiornamento dei nuovi valori");
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $ok = $this->truncateRoleTable();
        if (!$ok) {
            return false;
        }
        try {
            $this->insert($this->tableName, ['id' => 1, 'name' => 'Freelance', 'enabled' => 1, 'order' => 10]);
            $this->insert($this->tableName, ['id' => 2, 'name' => 'Consultant', 'enabled' => 1, 'order' => 20]);
            $this->insert($this->tableName, ['id' => 3, 'name' => 'Entrepreneur', 'enabled' => 1, 'order' => 30]);
            $this->insert($this->tableName, ['id' => 4, 'name' => 'Teacher/researcher/university collaborator', 'enabled' => 1, 'order' => 40]);
            $this->insert($this->tableName, ['id' => 5, 'name' => 'Employee/collaborator of an organization (enterprise, entity, etc.)', 'enabled' => 1, 'order' => 50]);
            $this->insert($this->tableName, ['id' => 6, 'name' => 'Other', 'enabled' => 1, 'order' => 1000]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore durante il ripristino dei vecchi valori');
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        return true;
    }
    
    private function truncateRoleTable()
    {
        try {
            if ($this->db->driverName === 'mysql') {
                $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            }
            $this->truncateTable($this->tableName);
            if ($this->db->driverName === 'mysql') {
                $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
            }
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore durante truncate tabella user_profile_role');
            MigrationCommon::printConsoleMessage($exception->getMessage());
            return false;
        }
        return true;
    }
}
