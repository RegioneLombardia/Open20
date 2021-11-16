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
 * Class m190207_104447_modify_crawler_url_size
 */
class m190207_104447_modify_crawler_url_size extends Migration
{
    const CRAWLER_BUILDER_INDEX = "{{%crawler_builder_index}}";
    const CRAWLER_INDEX         = "{{%crawler_index}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->db->getTableSchema(self::CRAWLER_BUILDER_INDEX, true) !== null) {
            try {
                $this->dropIndex('crawler_builder_index-url_index', self::CRAWLER_BUILDER_INDEX);
            } catch (\Exception $ex) {
                Console::stdout('No Index crawler_builder_index-url_index');
            }
            try {
                $this->dropIndex('url', self::CRAWLER_BUILDER_INDEX);
            } catch (\Exception $ex) {
                Console::stdout('No Index url');
            }
            $this->alterColumn(self::CRAWLER_BUILDER_INDEX, 'url', $this->text());
            $this->alterColumn(self::CRAWLER_BUILDER_INDEX, 'title', $this->text());
            $this->alterColumn(self::CRAWLER_BUILDER_INDEX, 'url_found_on_page', $this->text());
            $this->createIndex('crawler_builder_index-url_index', self::CRAWLER_BUILDER_INDEX, 'url(255)',
                $unique = false);
        }

        if (Yii::$app->db->getTableSchema(self::CRAWLER_INDEX, true) !== null) {
            try {
                $this->dropIndex('crawler_index_url_url-index', self::CRAWLER_INDEX);
            } catch (\Exception $ex) {
                Console::stdout('No Index crawler_index_url_url-index');
            }
            try {
                $this->dropIndex('url', self::CRAWLER_INDEX);
            } catch (\Exception $ex) {
                Console::stdout('No Index url');
            }
            $this->alterColumn(self::CRAWLER_INDEX, 'url', $this->text());
            $this->alterColumn(self::CRAWLER_INDEX, 'title', $this->text());
            $this->alterColumn(self::CRAWLER_INDEX, 'url_found_on_page', $this->text());
            $this->createIndex('crawler_index_url_url-index', self::CRAWLER_INDEX, 'url(255)', $unique = false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190207_104447_modify_crawler_url_size cannot be reverted.\n";
        if (Yii::$app->db->getTableSchema(self::CRAWLER_BUILDER_INDEX, true) !== null) {
            $this->dropIndex('crawler_builder_index-url_index', self::CRAWLER_BUILDER_INDEX);
            $this->alterColumn(self::CRAWLER_BUILDER_INDEX, 'url', $this->string(200));
            $this->alterColumn(self::CRAWLER_BUILDER_INDEX, 'title', $this->string(200));
            $this->alterColumn(self::CRAWLER_BUILDER_INDEX, 'url_found_on_page', $this->string(200));
            $this->createIndex('crawler_builder_index-url_index', self::CRAWLER_BUILDER_INDEX, 'url', $unique = true);
        }

        if (Yii::$app->db->getTableSchema(self::CRAWLER_INDEX, true) !== null) {
            $this->dropIndex('crawler_index_url_url-index', self::CRAWLER_INDEX);
            $this->alterColumn(self::CRAWLER_INDEX, 'url', $this->string(200));
            $this->alterColumn(self::CRAWLER_INDEX, 'title', $this->string(200));
            $this->alterColumn(self::CRAWLER_INDEX, 'url_found_on_page', $this->string(200));
            $this->createIndex('crawler_index_url_url-index', self::CRAWLER_INDEX, 'url', $unique = true);
        }
        return true;
    }
}