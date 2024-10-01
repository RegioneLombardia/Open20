<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiStato;
use yii\db\ActiveQuery;
use yii\db\Migration;
use yii\helpers\ArrayHelper;

/**
 * Class m180910_104820_migrate_sondaggi_stati_to_sondaggi_workflow
 */
class m180910_104820_migrate_sondaggi_stati_to_sondaggi_workflow extends Migration
{
    /**
     * @var array $oldStatuses
     */
    private $oldStatuses = [];

    /**
     * @var array $statusMap
     */
    private $statusMap = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->oldStatuses = ArrayHelper::map(SondaggiStato::find()->all(), 'id', 'stato');
        $this->statusMap = [
            'BOZZA' => Sondaggi::WORKFLOW_STATUS_BOZZA,
            'DA_VALIDARE' => Sondaggi::WORKFLOW_STATUS_DAVALIDARE,
            'VALIDATO' => Sondaggi::WORKFLOW_STATUS_VALIDATO,
            'NON_VALIDATO' => Sondaggi::WORKFLOW_STATUS_BOZZA
        ];
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /** @var ActiveQuery $query */
        $query = Sondaggi::find();
        $sondaggi = $query->all();

        $allOk = true;

        foreach ($sondaggi as $sondaggio) {
            /** @var Sondaggi $sondaggio */
            if ($sondaggio->status) {
                continue;
            }
            $newStatus = $this->mapOldToNewStatus($sondaggio->sondaggi_stato_id);
            if (!$newStatus) {
                MigrationCommon::printCheckStructureError($sondaggio->attributes, 'Stato non trovato per il sondaggio');
                $allOk = false;
                continue;
            }
            $sondaggio->status = $newStatus;
            $sondaggio->detachBehaviors();
            $ok = $sondaggio->save(false);
            if (!$ok) {
                MigrationCommon::printCheckStructureError($sondaggio->attributes, "Errore durante il salvataggio del sondaggio");
                $allOk = false;
                continue;
            }
        }

        if ($allOk) {
            MigrationCommon::printConsoleMessage('Aggiornamento stati dei sondaggi avvenuto correttamente');
        }

        return true;
    }

    /**
     * @param string $oldStatus
     * @return string
     */
    private function mapOldToNewStatus($oldStatusId)
    {
        $oldStatus = $this->oldStatuses[$oldStatusId];
        if ($oldStatus && $this->statusMap[$oldStatus]) {
            return $this->statusMap[$oldStatus];
        }
        return '';
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180910_104820_migrate_sondaggi_stati_to_sondaggi_workflow cannot be reverted.\n";
        return false;
    }
}
