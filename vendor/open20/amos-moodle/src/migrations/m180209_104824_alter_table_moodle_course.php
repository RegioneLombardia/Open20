<?php
use open20\amos\core\migration\AmosMigration;
use open20\amos\moodle\models\MoodleCourse;

/**
* Class m180209_104824_alter_table_moodle_course*/
class m180209_104824_alter_table_moodle_course extends AmosMigration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(MoodleCourse::tableName(), 'moodle_categoryid', $this->integer(11)->notNull()->comment('Category id in Moodle'));
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(MoodleCourse::tableName(), 'moodle_categoryid');
        return true;
    }
}
