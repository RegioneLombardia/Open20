<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m210326_090104_modify_field_news_category
 */
class m210326_090104_modify_field_news_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('news_categorie', 'color_background', $this->string(128)->defaultValue(null)->after('descrizione')->comment('Colore sfondo'));
        $this->alterColumn('news_categorie', 'color_text', $this->string(128)->defaultValue(null)->after('color_background')->comment('Colore testo'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
