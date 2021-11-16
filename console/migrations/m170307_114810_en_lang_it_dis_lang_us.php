<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use yii\db\Migration;

class m170307_114810_en_lang_it_dis_lang_us extends Migration
{
    const TABLE_NAME = '{{%language}}';

    public function safeUp()
    {
        // Enable Italian language
        $this->update(
            self::TABLE_NAME,
            ['status' => '1'],
            ['language_id' => 'it-IT']
        );
        // Disable United States language
        $this->update(
            self::TABLE_NAME,
            ['status' => '0'],
            ['language_id' => 'en-US']
        );
        return true;
    }

    public function safeDown()
    {
        // Disable Italian language
        $this->update(
            self::TABLE_NAME,
            ['status' => '0'],
            ['language_id' => 'it-IT']
        );
        // Enable United States language
        $this->update(
            self::TABLE_NAME,
            ['status' => '1'],
            ['language_id' => 'en-US']
        );
        return true;
    }
}
