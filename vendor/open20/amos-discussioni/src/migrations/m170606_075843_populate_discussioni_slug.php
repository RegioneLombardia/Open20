<?php

use open20\amos\discussioni\models\DiscussioniTopic;

use yii\db\Migration;

class m170606_075843_populate_discussioni_slug extends Migration
{
    public function safeUp()
    {

        $topics = DiscussioniTopic::find()
                     ->andWhere(['slug' => null])
                     ->orderBy(['id' => SORT_ASC])
                     ->all();
        
        foreach ($topics as $topic) {
            /**@var $topic \open20\amos\discussioni\models\DiscussioniTopic */
            $topic->detachBehaviors();

            $topic->attachBehavior(
                'slug', 
                [
                    'class' => \yii\behaviors\SluggableBehavior::className(),
                    'attribute' => 'titolo',
                    'slugAttribute' => 'slug',
                    'ensureUnique' => true
                ]
            );

            $topic->validate(['slug']);
            $topic->save(false);

            \yii\helpers\Console::stdout("SLUG: {$topic->slug}\n\n");
        }

        return true;
    }

    public function safeDown()
    {

        return true;
    }
}
