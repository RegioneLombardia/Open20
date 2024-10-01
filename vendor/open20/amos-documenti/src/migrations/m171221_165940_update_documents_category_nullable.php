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
 * Class m171221_165940_update_documents_category_nullable
 */
class m171221_165940_update_documents_category_nullable extends Migration
{
    private $table = null;

    public function __construct()
    {
        $this->table = Documenti::tableName();
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn($this->table, 'documenti_categorie_id',  $this->integer(11)->null()->comment('Categoria'));

        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn($this->table, 'documenti_categorie_id',  $this->integer(11)->notNull()->comment('Categoria'));

        return true;
    }
}
