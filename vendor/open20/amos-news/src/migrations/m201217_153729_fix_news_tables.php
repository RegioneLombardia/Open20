<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m201217_153729_fix_news_tables
 */
class m201217_153729_fix_news_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->removeForeignKey('news', 'fk-edited-by-agid-organizational-unit-id');
        $this->removeForeignKey('news_agid_person_mm', 'fk-news-id');
        $this->removeForeignKey('news_agid_person_mm', 'fk-agid-person-id');
        $this->removeForeignKey('news_related_news_mm', 'fk-news-related-news-mm-news-id');
        $this->removeForeignKey('news_related_news_mm', 'fk-news-related-news-mm-related-news-id');
        $this->removeForeignKey('news_related_documenti_mm', 'fk-news-related-documenti-mm-news-id');
        $this->removeForeignKey('news_related_documenti_mm', 'fk-news-related-documenti-mm-related-documenti-id');
        $this->removeForeignKey('news_related_agid_service_mm', 'fk-news-related-agid-service-mm-news-id');
        $this->removeForeignKey('news_related_agid_service_mm', 'fk-news-related-agid-service-mm-related-agid-service-id');
        $this->removeForeignKey('news', 'fk-news-content-type-id');
        $this->removeForeignKey('news', 'fk-news-news-documento-id');
        $this->removeForeignKey('news', 'fk-news-image-site-management-slider-id');
        $this->removeForeignKey('news', 'fk-news-video-site-management-slider-id');
        return true;
    }
    
    /**
     * @param string $tableName
     * @param string $foreignKeyName
     */
    protected function removeForeignKey($tableName, $foreignKeyName)
    {
        $tableSchema = $this->db->schema->getTableSchema($tableName, true);
        $foreignKeys = $tableSchema->foreignKeys;
        if (isset($foreignKeys[$foreignKeyName])) {
            $this->dropForeignKey($foreignKeyName, $tableName);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m201217_153729_fix_news_tables cannot be reverted.\n";
        return false;
    }
}
