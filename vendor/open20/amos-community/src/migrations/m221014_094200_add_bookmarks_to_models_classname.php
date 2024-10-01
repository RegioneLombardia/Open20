<?php

use yii\db\Migration;

class m221014_094200_add_bookmarks_to_models_classname extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('models_classname', [
            'classname' => open20\amos\community\models\Bookmarks::className(),
            'module' => 'community',
            'table' => 'bookmarks',
            'label' => 'Bookmarks',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->delete('models_classname', [
            'classname' => open20\amos\community\models\Bookmarks::className(),
            'module' => 'community',
            'table' => 'bookmarks',
            'label' => 'Bookmarks',
        ]);


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}