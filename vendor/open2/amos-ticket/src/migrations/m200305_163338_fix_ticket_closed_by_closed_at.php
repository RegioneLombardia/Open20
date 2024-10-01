<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\workflow\models\WorkflowTransitionsLog;
use open2\amos\ticket\models\Ticket;
use yii\db\ActiveQuery;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m200305_163338_fix_ticket_closed_by_closed_at
 */
class m200305_163338_fix_ticket_closed_by_closed_at extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ticketsToFix = $this->findTicketsToFix();
        foreach ($ticketsToFix as $ticketToFix) {
            try {
                $this->update(
                    Ticket::tableName(),
                    ['closed_at' => $ticketToFix['ticket_closed_at'], 'closed_by' => $ticketToFix['ticket_closed_by']],
                    ['id' => $ticketToFix['ticket_id']]
                );
            } catch (\Exception $exception) {
                MigrationCommon::printCheckStructureError($ticketToFix, 'Errore update ticket');
                return false;
            }
        }
        MigrationCommon::printConsoleMessage('Utente e data chiusura ticket aggiornati con successo');
        return true;
    }

    private function findTicketsToFix()
    {
        $ticketTable = Ticket::tableName();
        $transitionLogTable = WorkflowTransitionsLog::tableName();

        $query = new Query();
        $query->select([
            $ticketTable . '.id AS ticket_id',
            $transitionLogTable . '.created_at AS ticket_closed_at',
            $transitionLogTable . '.created_by AS ticket_closed_by',
        ]);
        $query->from($ticketTable);
        $query->innerJoin($transitionLogTable,
            $transitionLogTable . '.owner_primary_key = ' . $ticketTable . '.id AND ' .
            $transitionLogTable . ".end_status = '" . Ticket::TICKET_WORKFLOW_STATUS_CLOSED . "' AND " .
            $transitionLogTable . ".classname LIKE '%Ticket%' AND " .
            $transitionLogTable . ".deleted_at IS NULL");
        $query->andWhere([$ticketTable . '.status' => Ticket::TICKET_WORKFLOW_STATUS_CLOSED]);
        $query->andWhere([$ticketTable . '.closed_by' => null]);
        $query->andWhere([$ticketTable . '.deleted_at' => null]);
        $ticketsToFix = $query->all();
//        MigrationCommon::printConsoleMessage($query->createCommand()->getRawSql());
//        MigrationCommon::printConsoleMessage($ticketsToFix);
//        die();
        return $ticketsToFix;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200305_163338_fix_ticket_closed_by_closed_at cannot be reverted.\n";
        return false;
    }
}
