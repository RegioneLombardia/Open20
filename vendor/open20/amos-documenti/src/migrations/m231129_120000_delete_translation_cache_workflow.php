<?php


use open20\amos\documenti\models\Documenti;


/**
 * Class m231129_120000_delete_translation_cache_workflow
 * Delete translation cache for new workflow status labels
 */
class m231129_120000_delete_translation_cache_workflow extends \yii\db\Migration
{
    const TABLE = '{{%language_source}}';

    /**
     * @return void
     */
    public function up()
    {
        $this->delete(self::TABLE, [
            'category' => 'amosdocumenti',
            'message' => Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE,
        ]);
        $this->delete(self::TABLE, [
            'category' => 'amosdocumenti',
            'message' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
        ]);
    }

    /**
     * @return true
     */
    public function down()
    {
        echo "m231129_120000_delete_translation_cache_workflow cannot be reverted.\n";

        return true;
    }
}