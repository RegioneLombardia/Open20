<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    common/console/migrations
 * @category
 */

use yii\db\Migration;

/**
 * Handles alter table `{{%ebike_assets}}`.
 */
class m221130_162424_add_missing_socials_profilo extends Migration {

    /**
     * Bullet counter table
     * @var type 
     */
    protected $tableName;
    
    /**
     *
     * @var type 
     */
    protected $tableOptions;
    
    /**
     * @inheritdoc
     */
    public function init() {
        $this->tableName = '{{%profilo}}';
        $this->tableOptions = null;
    }

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn(
            $this->tableName, 
            'instagram', 
            $this->char(255)->defaultValue(null)
                ->comment('Instagram')->after('google')
        );
        
        $this->addColumn(
            $this->tableName, 
            'youtube', 
            $this->char(255)->defaultValue(null)
                ->comment('Instagram')->after('google')
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn($this->tableName, 'instagram');
        $this->dropColumn($this->tableName, 'youtube');
    }

}
