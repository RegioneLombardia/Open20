<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use cornernote\workflow\manager\models\Status;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;

/**
 * Class m180411_162621_modify_news_metadata_3
 */
class m180411_162621_modify_news_metadata_3 extends Migration
{
    const WORKFLOW_NAME = 'NewsWorkflow';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->updateStatus('BOZZA', 'Bozza');
        $this->updateStatus('DAVALIDARE', 'In richiesta di pubblicazione');
        $this->updateStatus('VALIDATO', 'Pubblicata');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->updateStatus('BOZZA', 'Modifica in corso');
        $this->updateStatus('DAVALIDARE', 'Richiedi pubblicazione');
        $this->updateStatus('VALIDATO', 'Validato');
    }

    /**
     * @param string $status
     * @param string $label
     * @return bool
     */
    private function updateStatus($status, $label)
    {
        try {
            $this->update(Status::tableName(), ['label' => $label], [
                'workflow_id' => self::WORKFLOW_NAME,
                'id' => $status
            ]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore durante aggiornamento stato workflow: workflow = ' . self::WORKFLOW_NAME . '; status = ' . self::WORKFLOW_NAME . '/' . $status . '; label = ' . $label . ';');
            return false;
        }
        MigrationCommon::printConsoleMessage("Aggiornamento stato workflow eseguito correttamente: workflow = " . self::WORKFLOW_NAME . "; status = " . self::WORKFLOW_NAME . '/' . $status . '; label = ' . $label . ';');
        return true;
    }
}
