<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileRole;
use yii\db\Migration;

/**
 * Class m181012_131920_add_user_profile_role_field_1
 */
class m181012_131920_add_user_profile_role_field_1 extends Migration
{
    private $tableName;
    private $fieldName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = UserProfileRole::tableName();
        $this->fieldName = 'type_cat';
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->fieldName, $this->integer()->notNull()->defaultValue(0)->after('order')->comment('Type Cat'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->fieldName);
        return true;
    }
}
