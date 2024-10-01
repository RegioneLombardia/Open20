<?php

class m220609_113700_add_cwh_config extends \yii\db\Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        try {
            $classname = open20\amos\collaborations\models\CollaborationProposals::className();
            $this->update(open20\amos\cwh\models\CwhConfigContents::tableName(), ['classname' => $classname], [
                'tablename' => open20\amos\collaborations\models\CollaborationProposals::tableName()
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
            $classname = open20\amos\collaborations\models\CollaborationProposals::className();
            $this->update(open20\amos\cwh\models\CwhConfigContents::tableName(), ['classname' => $classname], [
                'tablename' => open20\amos\collaborations\models\CollaborationProposals::tableName()
            ]);
        } catch (Exception $ex) {
            \yii\helpers\Console::stdout("Error transaction " . $classname . " " . $ex->getMessage());
        }
    }

}
