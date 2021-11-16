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
class m190320_163604_add_field_news_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('news_categorie', 'publish_only_on_community', $this->integer()->defaultValue(0)->after('notify_category')->comment('Publish only on Community'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('news_categorie', 'publish_only_on_community');
    }
}
