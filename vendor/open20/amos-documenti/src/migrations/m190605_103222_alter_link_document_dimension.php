<?php

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m190605_103222_alter_link_document_dimension
 */
class m190605_103222_alter_link_document_dimension extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(
            Documenti::tableName(), 
            'link_document', 
            $this->text()
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190605_103222_alter_link_document_dimension cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190605_103222_alter_link_document_dimension cannot be reverted.\n";

        return false;
    }
    */
}
