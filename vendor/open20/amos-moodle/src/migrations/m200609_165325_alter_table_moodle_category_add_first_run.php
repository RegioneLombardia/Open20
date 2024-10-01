<?php
use open20\amos\core\migration\AmosMigration;
use open20\amos\moodle\models\MoodleCategory;

/**
 * m200609_165325_alter_table_moodle_category_add_first_run
 */
class m200609_165325_alter_table_moodle_category_add_first_run extends AmosMigration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            MoodleCategory::tableName(), 
            'first_run', 
            $this->integer()->notNull()->defaultValue(null)->comment('First run used to reset all community with e-learning')->after('community_id')
        );
        
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(MoodleCategory::tableName(), 'first_run');
        
        return true;
    }
}