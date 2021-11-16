<?php
use open20\amos\core\migration\AmosMigration;
use open20\amos\moodle\models\MoodleCourse;

/**
* Class m180209_104824_alter_table_moodle_course*/
class m200129_102625_alter_table_moodle_course_add_enrolment_methods extends AmosMigration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            MoodleCourse::tableName(), 
            'enrollment_methods', 
            $this->char(255)->notNull()->comment('Enroll methods self/paypal and so on')->after('community_id')
        );
        
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(MoodleCourse::tableName(), 'enrollment_methods');
        return true;
    }
}
