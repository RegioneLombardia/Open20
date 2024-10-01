<?php

class m221013_170700_add_cwh_bookmarks_config extends \yii\db\Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        try {
            $classname = open20\amos\community\models\Bookmarks::className();
            $this->update(open20\amos\cwh\models\CwhConfigContents::tableName(), ['classname' => $classname], [
                'tablename' => open20\amos\community\models\Bookmarks::tableName()
            ]);
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Error transaction " . $classname . " " . $ex->getMessage());
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        try {
            $classname = open20\amos\community\models\Bookmarks::className();
            $this->update(open20\amos\cwh\models\CwhConfigContents::tableName(), ['classname' => $classname], [
                'tablename' => open20\amos\community\models\Bookmarks::tableName()
            ]);
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Error transaction " . $classname . " " . $ex->getMessage());
        }
    }

}