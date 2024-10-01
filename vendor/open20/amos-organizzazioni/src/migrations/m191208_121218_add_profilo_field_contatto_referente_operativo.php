<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\Profilo;
use yii\db\Migration;

/**
 * Class m191208_121218_add_profilo_field_contatto_referente_operativo
 */
class m191208_121218_add_profilo_field_contatto_referente_operativo extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = Profilo::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'contatto_referente_operativo', $this->string(255)->defaultValue(null)->after('referente_operativo'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'contatto_referente_operativo');
        return true;
    }
}
