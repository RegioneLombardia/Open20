<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */
use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m190730_114047_alter_table
 */
class m190730_114047_alter_table extends Migration
{
    const CRAWLER_BUILDER_INDEX = "{{%crawler_builder_index}}";
    const CRAWLER_INDEX         = "{{%crawler_index}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->addColumn(self::CRAWLER_BUILDER_INDEX, 'processed', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo 'No down available';
        return true;
    }
}