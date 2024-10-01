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
 * Class m210319_110104_add_field_news_category
 */
class m210319_110104_add_field_news_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('news_categorie', 'color_background', $this->string(128)->defaultValue('#5e7887')->after('descrizione')->comment('Colore sfondo '));
        $this->addColumn('news_categorie', 'color_text', $this->string(128)->defaultValue('#FFFFFF')->after('color_background')->comment('Colore testo'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('news_categorie', 'color_background');
        $this->dropColumn('news_categorie', 'color_text');
    }
}
