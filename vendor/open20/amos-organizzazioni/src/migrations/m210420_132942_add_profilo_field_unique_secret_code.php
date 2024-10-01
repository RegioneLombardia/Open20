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
use yii\db\ActiveQuery;
use yii\db\Migration;

/**
 * Class m210420_132942_add_profilo_field_unique_secret_code
 */
class m210420_132942_add_profilo_field_unique_secret_code extends Migration
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
        $this->addColumn($this->tableName, 'unique_secret_code', $this->string(50)->defaultValue(null)->after('name'));
        /** @var ActiveQuery $query */
        $query = Profilo::find();
        $organizations = $query->all();
        foreach ($organizations as $organization) {
            /** @var Profilo $organization */
            $uniqueSecretCode = $organization->generateUniqueSecretCode();
            $organization->unique_secret_code = $uniqueSecretCode;
            $organization->save(false);
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'unique_secret_code');
        return true;
    }
}
