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
 * Class m190208_114047_add_fulltextindex
 */
class m190208_114047_add_fulltextindex extends Migration
{
    const CRAWLER_BUILDER_INDEX = "{{%crawler_builder_index}}";
    const CRAWLER_INDEX         = "{{%crawler_index}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            ALTER TABLE `crawler_builder_index`
            ADD FULLTEXT(`url`,`title`,`content`,`description`,`language_info`,`url_found_on_page`,`group`,`content_hash`);

            ALTER TABLE `crawler_index`
            ADD FULLTEXT(`url`,`title`,`content`,`description`,`language_info`,`url_found_on_page`,`group`);


            ALTER TABLE `crawler_searchdata`
            ADD FULLTEXT(`query`,`language`);
            ');
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