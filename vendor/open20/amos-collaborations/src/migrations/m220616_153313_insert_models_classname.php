<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m220616_153313_insert_models_classname extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('models_classname', [
            'classname' => \open20\amos\collaborations\models\CollaborationProposals::className(),
            'module' => 'collaborations',
            'table' => 'collaboration_proposals',
            'label' => 'Proposte di collaborazione',
        ]);

        $this->insert('models_classname', [
            'classname' => \open20\amos\collaborations\models\CollaborationExpressionsOfInterest::className(),
            'module' => 'collaborations',
            'table' => 'collaboration_expressions_of_interest',
            'label' => 'Manifestazioni di interesse',
        ]);





    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->delete('models_classname', [
            'classname' => \open20\amos\collaborations\models\CollaborationProposals::className(),
            'module' => 'collaborations',
            'table' => 'collaboration_proposal',
            'label' => 'Proposte di collaborazione',
        ]);

        $this->delete('models_classname', [
            'classname' => \open20\amos\collaborations\models\CollaborationExpressionsOfInterest::className(),
            'module' => 'collaborations',
            'table' => 'collaboration_expressions_of_interest',
            'label' => 'Manifestazioni di interesse',
        ]);


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
