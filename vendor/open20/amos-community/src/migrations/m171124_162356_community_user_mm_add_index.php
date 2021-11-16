<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m171124_162356_community_user_mm_add_index
 */
class m171124_162356_community_user_mm_add_index extends Migration
{
    const TABLE = 'community_user_mm';
    
    private $indexName = '';
    
    public function init()
    {
        parent::init();
        
        $this->indexName = self::TABLE . '_user_community_deleted_at_idx';
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->checkIndexExists()) {
            $this->createIndex($this->indexName, self::TABLE, ['user_id', 'community_id', 'deleted_at']);
        } else {
            MigrationCommon::printConsoleMessage('Indice esistente');
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->checkIndexExists()) {
            if ($this->db->driverName = 'mysql') {
                $this->execute('SET FOREIGN_KEY_CHECKS=0;');
            }
            $this->dropIndex($this->indexName, self::TABLE);
            if ($this->db->driverName = 'mysql') {
                $this->execute('SET FOREIGN_KEY_CHECKS=1;');
            }
        } else {
            MigrationCommon::printConsoleMessage('Indice inesistente');
        }
        return true;
    }
    
    /**
     * @return bool
     */
    private function checkIndexExists()
    {
        $query = new Query();
        $query->select('INDEX_NAME')->distinct();
        $query->from('INFORMATION_SCHEMA.STATISTICS');
        $query->where(['TABLE_SCHEMA' => 'poi', 'TABLE_NAME' => self::TABLE]);
        $indexes = $query->all();
        $indexExists = false;
        foreach ($indexes as $index) {
            if (in_array($this->indexName, $index)) {
                $indexExists = true;
            }
        }
        return $indexExists;
    }
}
