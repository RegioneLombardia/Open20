<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use yii\db\Migration;

/**
 * Class m180807_132348_add_admin_first_access_mail_url_field
 */
class m180807_132348_add_admin_first_access_mail_url_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(UserProfile::tableName(), 'first_access_mail_url', $this->string(255)->defaultValue(null)->comment('First Access Mail Url')->after('first_access_redirect_url'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(UserProfile::tableName(), 'first_access_mail_url');
        return true;
    }
}
