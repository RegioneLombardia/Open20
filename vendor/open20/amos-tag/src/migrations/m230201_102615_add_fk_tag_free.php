<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */
use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m230201_102615_add_fk_tag_free
 */
class m230201_102615_add_fk_tag_free extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {

        $this->addForeignKey('fk_tagfree_mm1', 'tag_free_model_mm', 'tag_id', 'tag_free', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {

        $this->dropForeignKey('fk_tagfree_mm1', 'tag_free_model_mm');
    }

}
