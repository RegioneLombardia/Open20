<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m190121_115513_add_columns_newsletter extends Migration
{
    const TABLE_NEWSLETTER = "newsletter";

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(self::TABLE_NEWSLETTER, 'name', $this->string()->after('id')->defaultValue(null)->comment('Name'));
        $this->addColumn(self::TABLE_NEWSLETTER, 'service_email_group_id', $this->integer()->after('newsletter_template_id')->defaultValue(null)->comment('Service mail group'));
        $this->addColumn(self::TABLE_NEWSLETTER, 'service_email_list_id', $this->integer()->after('newsletter_template_id')->defaultValue(null)->comment('Service mail List'));
        $this->alterColumn(self::TABLE_NEWSLETTER, 'newsletter_template_id', $this->integer()->defaultValue(null)->comment('Template'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_NEWSLETTER, 'name');
        $this->dropColumn(self::TABLE_NEWSLETTER, 'service_email_group_id');
        $this->dropColumn(self::TABLE_NEWSLETTER, 'service_email_list_id');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
