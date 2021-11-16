<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Handles the dropping of table `news_allegati`.
 */
class m170217_162908_drop_news_allegati_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema('news_allegati', true) !== null) {
            $this->dropForeignKey('fk_news_allegati_news1_idx', 'news_allegati');
            $this->dropTable('news_allegati');
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m170217_162908_drop_news_allegati_table non può essere revertata";
        return true;
    }
}
