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
 * Class m190918_114047_alter_content
 */
class m190918_114047_alter_content extends Migration
{
    const CRAWLER_BUILDER_INDEX = "{{%crawler_builder_index}}";
    const CRAWLER_INDEX         = "{{%crawler_index}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->alterColumn(self::CRAWLER_BUILDER_INDEX, 'content', 'LONGTEXT');
       $this->alterColumn(self::CRAWLER_INDEX, 'content', 'LONGTEXT');
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