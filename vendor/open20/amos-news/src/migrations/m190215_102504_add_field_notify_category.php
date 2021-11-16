<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m171214_162104_add_documenti_fields_2
 */
class m190215_102504_add_field_notify_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('news_categorie', 'notify_category', $this->integer()->defaultValue(1)->after('filemanager_mediafile_id')->comment('Notify category'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('news_categorie', 'notify_category');
    }
}
