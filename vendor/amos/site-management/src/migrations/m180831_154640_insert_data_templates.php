<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m180308_094940_add_foreign_keys_site_management_metadata
 */
class m180831_154640_insert_data_templates extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('site_management_template', ['content_model' => 'open20\amos\news\models\News'], ['id' => 5] );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->update('site_management_template', ['content_model' => null], ['id' => 5] );


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
