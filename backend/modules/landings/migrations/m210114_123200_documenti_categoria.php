<?php

use open20\amos\documenti\models\DocumentiCategorie;
use yii\db\Migration;
use yii\db\Schema;

/**
 */
class m210114_123200_documenti_categoria extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if (count(DocumentiCategorie::find()->all()) == 0) {
            $cat = new DocumentiCategorie();
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
