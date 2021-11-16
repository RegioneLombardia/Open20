<?php

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m190409_150242_add_link_document
 */
class m190409_150242_add_link_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            Documenti::tableName(), 
            'link_document', 
            $this
                ->char(255)
                ->null()
                ->defaultValue(null)
                ->comment('link to online document')
                ->after('version_parent_id')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Documenti::tableName(), 'link_document');

        return false;
    }

}
