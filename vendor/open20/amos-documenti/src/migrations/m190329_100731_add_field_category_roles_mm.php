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
 * Class m171206_092631_add_documenti_fields_1
 */
class m190329_100731_add_field_category_roles_mm extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('
            INSERT INTO documenti_category_roles_mm (documenti_categorie_id, role)
            SELECT id, "BASIC_USER"
            FROM documenti_categorie
        ');

        $this->execute('
            INSERT INTO documenti_category_roles_mm (documenti_categorie_id, role)
            SELECT id, "ADMIN"
            FROM documenti_categorie
        ');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;

    }
}
