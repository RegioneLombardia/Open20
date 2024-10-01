<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m201014_165713_insert_models_classname extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $this->insert('models_classname', [
            'classname' => \amos\podcast\models\PodcastEpisode::className(),
            'module' => 'podcast',
            'label' => 'Podcast episode',
            'description' => 'Podcast episode',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => '2020-10-14 17:02:00',
            'updated_at' => '2020-10-14 17:02:00',
        ]);
        $this->insert('models_classname', [
            'classname' => \amos\podcast\models\Podcast::className(),
            'module' => 'podcast',
            'label' => 'Podcast',
            'description' => 'Podcast',
            'created_by' =>1,
            'updated_by' =>1,
            'created_at' => '2020-10-14 17:02:00',
            'updated_at' => '2020-10-14 17:02:00',
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->delete('models_classname', [
            'classname' => \amos\podcast\models\PodcastEpisode::className(),
            'module' => 'podcast',
            'label' => 'Podcast episode',
            'description' => 'Podcast episode',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => '2020-10-14 17:02:00',
            'updated_at' => '2020-10-14 17:02:00',
        ]);
        $this->delete('models_classname', [
            'classname' => \amos\podcast\models\Podcast::className(),
            'module' => 'podcast',
            'label' => 'Podcast',
            'description' => 'Podcast',
            'created_by' =>1,
            'updated_by' =>1,
            'created_at' => '2020-10-14 17:02:00',
            'updated_at' => '2020-10-14 17:02:00',
        ]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
