<?php

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m190409_150242_add_link_document
 */
class m191212_124042_add_column_drive_file_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            Documenti::tableName(), 
            'drive_file_id',
            $this
                ->string()
                ->defaultValue(null)
                ->comment('File drive id')
                ->after('link_document')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Documenti::tableName(), 'drive_file_id');
    }

}
