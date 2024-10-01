<?php
use open20\amos\core\migration\AmosMigration;
use open20\amos\moodle\models\MoodleCourse;

/**
 * Class m200612_165925_alter_table_moodle_course_enrolment_methods
 */
class m200612_165925_alter_table_moodle_course_enrolment_methods extends AmosMigration
{
    /**
     * Moodle course table
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
            'enrollment_methods', 
            $this->char(255)->notNull()->defaultValue(null)
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