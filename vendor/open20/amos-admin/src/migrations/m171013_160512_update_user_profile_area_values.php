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
 * Class m171013_160512_update_user_profile_area_values
 */
class m171013_160512_update_user_profile_area_values extends Migration
{
    private $tableName = 'user_profile_area';
    
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
            $this->insert($this->tableName, ['id' => 1, 'name' => 'Purchasing', 'enabled' => 1, 'order' => 10]);
            $this->insert($this->tableName, ['id' => 2, 'name' => 'Administration/Finance', 'enabled' => 1, 'order' => 20]);
            $this->insert($this->tableName, ['id' => 3, 'name' => 'Commercial', 'enabled' => 1, 'order' => 30]);
            $this->insert($this->tableName, ['id' => 4, 'name' => 'Information systems', 'enabled' => 1, 'order' => 40]);
            $this->insert($this->tableName, ['id' => 5, 'name' => 'Logistic', 'enabled' => 1, 'order' => 50]);
            $this->insert($this->tableName, ['id' => 6, 'name' => 'Marketing', 'enabled' => 1, 'order' => 60]);
            $this->insert($this->tableName, ['id' => 7, 'name' => 'Communication', 'enabled' => 1, 'order' => 70]);
            $this->insert($this->tableName, ['id' => 8, 'name' => 'Organization and quality', 'enabled' => 1, 'order' => 80]);
            $this->insert($this->tableName, ['id' => 9, 'name' => 'Production', 'enabled' => 1, 'order' => 90]);
            $this->insert($this->tableName, ['id' => 10, 'name' => 'Research and development', 'enabled' => 7, 'order' => 100]);
            $this->insert($this->tableName, ['id' => 11, 'name' => 'Human resources', 'enabled' => 1, 'order' => 110]);
            $this->insert($this->tableName, ['id' => 12, 'name' => 'Other', 'enabled' => 1, 'order' => 1000]);
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
            $this->insert($this->tableName, ['id' => 1, 'name' => 'Administration', 'enabled' => 1, 'order' => 10]);
            $this->insert($this->tableName, ['id' => 2, 'name' => 'Commercial', 'enabled' => 1, 'order' => 20]);
            $this->insert($this->tableName, ['id' => 3, 'name' => 'ICT', 'enabled' => 1, 'order' => 30]);
            $this->insert($this->tableName, ['id' => 4, 'name' => 'Management', 'enabled' => 1, 'order' => 40]);
            $this->insert($this->tableName, ['id' => 5, 'name' => 'Marketing and Management', 'enabled' => 1, 'order' => 50]);
            $this->insert($this->tableName, ['id' => 6, 'name' => 'Production', 'enabled' => 1, 'order' => 60]);
            $this->insert($this->tableName, ['id' => 7, 'name' => 'Research and development', 'enabled' => 7, 'order' => 70]);
            $this->insert($this->tableName, ['id' => 8, 'name' => 'Human resources', 'enabled' => 1, 'order' => 80]);
            $this->insert($this->tableName, ['id' => 9, 'name' => 'Other', 'enabled' => 1, 'order' => 1000]);
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
