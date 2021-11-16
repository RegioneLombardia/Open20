<?php

use open20\amos\news\models\NewsCategorie;
use yii\db\Migration;
use yii\db\Schema;

/**
 */
class m200923_180000_news_categoria extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if (count(NewsCategorie::find()->all()) == 0) {
            $cat = new NewsCategorie();
            $cat->titolo = 'Default';
            $cat->save();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
       return true;
    }
}
