<?php

use yii\db\Migration;

class m180808_120303_update_language_categories extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        try {
            $this->update('language_source', ['category' => 'amosevents',], ['category' => 'amosevent']);
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
