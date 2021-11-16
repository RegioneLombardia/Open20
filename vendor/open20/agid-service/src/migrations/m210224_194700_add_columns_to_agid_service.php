<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\agid\service\models\AgidService;

/**
 * Class m210224_194700_add_columns_to_agid_service
 */
class m210224_194700_add_columns_to_agid_service extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = AgidService::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'further_information', $this->string()->defaultValue(null)->comment("Ulteriori Informazioni")->after('recipients_description'));
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {

        $this->dropColumn($this->tableName, 'further_information');
        return true;
    }
}
