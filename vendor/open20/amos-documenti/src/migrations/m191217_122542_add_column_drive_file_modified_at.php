<?php

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m190409_150242_add_link_document
 */
class m191217_122542_add_column_drive_file_modified_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            Documenti::tableName(), 
            'drive_file_modified_at',
            $this
                ->dateTime()
                ->defaultValue(null)
                ->comment('Drive file modified at')
                ->after('drive_file_id')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Documenti::tableName(), 'drive_file_modified_at');
    }

}
