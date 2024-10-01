<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */


use yii\db\Migration;

/**
 * Class m210326_095400_update_fields_color_category
 */
class m210326_095400_update_fields_color_category extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('news_categorie', ['color_background' => null], ['color_background' => '#5e7887']);
        $this->update('news_categorie', ['color_text' => null], ['color_text' => '#FFFFFF']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
