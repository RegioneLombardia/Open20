<?php

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m190409_150242_add_link_document
 */
class m191015_095342_add_count_link_download extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            Documenti::tableName(), 
            'count_link_download',
            $this
                ->integer()
                ->defaultValue(0)
                ->comment('n of click on link document')
                ->after('hits')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Documenti::tableName(), 'count_link_download');
        return false;
    }

}
