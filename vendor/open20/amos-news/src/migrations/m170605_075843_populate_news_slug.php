<?php

use yii\db\Migration;

class m170605_075843_populate_news_slug extends Migration
{
    public function safeUp()
    {

        foreach (\open20\amos\news\models\News::find()
                     ->andWhere([
                         'slug' => null
                     ])
                     ->orderBy(['id' => SORT_ASC])
                     ->all() as $news) {


            /**@var $news \open20\amos\news\models\News */
            $news->detachBehaviors();

            $news->attachBehavior('slug', [
                'class' => \yii\behaviors\SluggableBehavior::className(),
                'attribute' => 'titolo',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ]);

            $news->validate(['slug']);
            $news->save(false);

            \yii\helpers\Console::stdout("SLUG: {$news->slug}\n\n");
        }

        return true;
    }

    public function safeDown()
    {

        return true;
    }
}
