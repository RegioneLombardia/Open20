<?php
use open20\amos\core\migration\AmosMigration;
use open20\amos\moodle\models\MoodleCourse;

/**
 * Class m180209_104824_alter_table_moodle_course
**/
class m200612_171824_alter_table_moodle_course_community_id extends AmosMigration
{

    /**
     * Moodle Course table
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
        $this->tableName = '{{%' . MoodleCourse::tableName() . '}}';
        $this->tableOptions = null;
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(
            $this->tableName, 
            'moodle_categoryid', 
            $this->integer(11)->notNull()->defaultValue(null)->comment('Category id in Moodle')->after('community_id')
        );
        
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    }
}