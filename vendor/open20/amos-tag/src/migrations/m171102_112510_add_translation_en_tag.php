<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

class m171102_112510_add_translation_en_tag extends \yii\db\Migration
{

    public function safeUp()
    {

        $this->addColumn(\open20\amos\tag\models\Tag::tableName(), 'nome_en',
            $this->text()
                ->null()
                ->after('nome')
        );

        $this->addColumn(\open20\amos\tag\models\Tag::tableName(), 'descrizione_en',
            $this->string(255)
                ->null()
                ->after('descrizione')
        );

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(\open20\amos\tag\models\Tag::tableName(), 'nome_en');
        $this->dropColumn(\open20\amos\tag\models\Tag::tableName(), 'descrizione_en');

        return true;
    }
}
