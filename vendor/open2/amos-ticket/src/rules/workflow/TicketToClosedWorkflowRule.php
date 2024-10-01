<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\rules\workflow
 * @category   CategoryName
 */

namespace open2\amos\ticket\rules\workflow;

//use yii\rbac\Rule;
use open20\amos\core\rules\BasicContentRule;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;

/**
 * Class TicketToClosedWorkflowRule
 * @package open2\amos\ticket\rules\workflow
 */
class TicketToClosedWorkflowRule extends BasicContentRule
{
    public $name = 'ticketToClosedWorkflowRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var Ticket $model */
        if (!$model->id) {
            return true;    // TODO errore nella creazione in una categoria tecnica nella chiusura del ticket appena creato sistemare diversamente
        }
        $isCategoriaTecnica = $model->ticketCategoria->tecnica;
//        pr($model->attributes);
//        pr((($model->created_by == $user) && $isCategoriaTecnica) ? "primo if true" : "primo if false");
//        pr(($model->isReferente($user, true, true)) ? "isReferente true" : "isReferente false");
//        pr(($isCategoriaTecnica || ($model->status == Ticket::TICKET_WORKFLOW_STATUS_PROCESSING)) ? "tecnica processing true" : "tecnica processing false");
//        pr((($model->status == Ticket::TICKET_WORKFLOW_STATUS_WAITING) && $model->isAncestor()) ? "waiting ancestor true" : "waiting ancestor false");
//        die();
        return (
            (($model->created_by == $user) && $isCategoriaTecnica) || // creato in una categoria tecnica
//            () || // Permesso di chiusura se referente della vecchia categoria
            (
                $model->isReferente($user) &&
                ($isCategoriaTecnica || ($model->status == Ticket::TICKET_WORKFLOW_STATUS_PROCESSING)) ||
                (($model->status == Ticket::TICKET_WORKFLOW_STATUS_WAITING) && $model->isAncestor())   // è stato forwadato
            )
        );
    }
}
