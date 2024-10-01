<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket
 * @category   CategoryName
 */

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\comments\widgets\CommentsWidget;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\widgets\forms\ByAtWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\Ticket|WorkflowLogFunctionsBehavior $model
 * @var AmosTicket $module
 */

/** @var AmosTicket $module */
$module = \Yii::$app->getModule('ticket');

$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Ticket'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$theUser = Yii::$app->getUser();
$isOperatore = !$theUser->can('AMMINISTRATORE_TICKET') && !$theUser->can('REFERENTE_TICKET');

$ticketIsWaiting = ($model->status == Ticket::TICKET_WORKFLOW_STATUS_WAITING);
$ticketIsClosed = ($model->status == Ticket::TICKET_WORKFLOW_STATUS_CLOSED);
if ($module->enableAdministrativeTicketCategory) {
    $ticketIsProcessing = (in_array($model->status, [Ticket::TICKET_WORKFLOW_STATUS_PROCESSING, Ticket::TICKET_WORKFLOW_STATUS_WAITING_TECHNICAL_ASSISTANCE]));
} else {
    $ticketIsProcessing = ($model->status == Ticket::TICKET_WORKFLOW_STATUS_PROCESSING);
}
if ($ticketIsWaiting) {
    $classState = 'state state-waiting';
} elseif ($ticketIsProcessing) {
    $classState = 'state state-processing';
} elseif ($ticketIsClosed) {
    $classState = 'state state-closed';
} else {
    $classState = 'state';
}

$ticketCategory = $model->ticketCategoria;
$ticketCreatorUserProfile = $model->createdUserProfile;

$disableTicketOrganization = (!empty($module) ? $module->disableTicketOrganization : false);
$disableForwardToAnotherCategory = (!empty($module) ? $module->disableForwardToAnotherCategory : false);

?>
<div class="ticket-view col-xs-12">
    <div class="row">
        <div class="col-xs-12 ticket-info nop">
            <div>
                <div class="ticket-label"><?= $model->getAttributeLabel('id') . ':' ?></div>
                <div class="ticket-content">
                    <?= $model->id /*. Html::a(AmosTicket::t('amosticket', 'Vedi la conversazione del ticket'), Url::current() . '#comments_contribute', ['class' => "btn btn-secondary"]);*/ ?>
                </div>
            </div>
            <div>
                <div class="ticket-label"><?= $model->getAttributeLabel('ticket_categoria_id') . ':' ?></div>
                <div class="ticket-content">
                    <?= $ticketCategory->nomeCompleto ?>
                    <?php if (!$ticketIsClosed && $model->isReferente($theUser->id)) { ?>
                        <?php
                        if (!$disableForwardToAnotherCategory) {
                            echo Html::a(AmosTicket::t('amosticket', 'Inoltra ad altra categoria'), Url::toRoute(['/ticket/ticket/change-category-ticket', 'id' => $model->id]), ['class' => "btn btn-secondary"]);
                        }
                        ?>
                    <?php } ?>
                </div>
            </div>
            <div>
                <div class="ticket-label"><?= AmosTicket::t('amosticket', '#ticket_status') . ':' ?></div>
                <div class="ticket-content <?= $classState ?>">
                    <span><?= $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--' ?></span>
                    <?php if ($ticketIsProcessing && $theUser->can(Ticket::TICKET_WORKFLOW_STATUS_CLOSED)) { ?>
                        <?php
                        echo Html::a(AmosTicket::t('amosticket', 'Chiudi il ticket'), Url::toRoute(['/ticket/ticket/closing-ticket', 'id' => $model->id]), ['class' => "btn btn-action-primary"]);
                        ?>
                    <?php } ?>
                </div>
            </div>
            <div>
                <div class="ticket-label"><?= AmosTicket::t('amosticket', '#creation_date') . ':' ?></div>
                <div class="ticket-content">
                    <?= Yii::$app->getFormatter()->asDatetime($model->created_at) ?>
                </div>
            </div>

            <?php
            if ($model->isGuestTicket()) :
            ?>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#ticket_creator') . ':' ?></div>
                    <div class="ticket-content">
                        <?= $model->guest_name; ?> <?= $model->guest_surname; ?>
                    </div>
                </div>

            <?php
            else :
            ?>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#ticket_creator') . ':' ?></div>
                    <div class="ticket-content">
                        <?= $ticketCreatorUserProfile->getNomeCognome() ?>
                    </div>
                </div>
            <?php
            endif;
            ?>

            <div>
                <div class="ticket-label"><?= AmosTicket::t('amosticket', '#ticket_creator_email') . ':' ?></div>
                <div class="ticket-content">

                    <?php
                    if ($model->isGuestTicket()) {
                        echo $model->guest_email;
                    } else {
                    ?>
                        <?= $ticketCreatorUserProfile->user->email ?>
                    <?php
                    }
                    ?>

                </div>
            </div>

            <?php
            if (!$disableTicketOrganization) {
            ?>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#organization_headquarter') . ':' ?></div>
                    <div class="ticket-content"><?= (!is_null($model->partnership) ? $model->partnership->name : '-') ?></div>
                </div>
            <?php
            } // endif of $disableTicketOrganization

            if ($ticketCategory->enable_dossier_id) : ?>
                <div>
                    <div class="ticket-label"><?= $model->getAttributeLabel('dossier_id') . ':' ?></div>
                    <div class="ticket-content"><?= ($model->dossier_id ? $model->dossier_id : '-') ?></div>
                </div>
            <?php endif; ?>
            <?php if ($ticketCategory->enable_phone) : ?>
                <div>
                    <div class="ticket-label"><?= $model->getAttributeLabel('phone') . ':' ?></div>
                    <div class="ticket-content"><?= ($model->phone ? $model->phone : '-') ?></div>
                </div>
            <?php endif; ?>

            <?php
            if (!$model->isGuestTicket()) :
            ?>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#first_answer_date') . ':' ?></div>
                    <div class="ticket-content"><?= ($model->firstOpeningDate ? Yii::$app->getFormatter()->asDatetime($model->firstOpeningDate) : '-') ?></div>
                </div>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#ticket_referee') . ':' ?></div>
                    <div class="ticket-content"><?= $model->ticketReferee ?></div>
                </div>
            <?php
            endif;
            ?>
            <div>
                <div class="ticket-label"><?= AmosTicket::t('amosticket', '#ticket_closing_date') . ':' ?></div>
                <div class="ticket-content"><?= ($model->ticketClosingDate ? Yii::$app->getFormatter()->asDatetime($model->ticketClosingDate) : '-') ?></div>
            </div>
            <div>
                <div class="ticket-label"><?= AmosTicket::t('amosticket', '#ticket_closing_referee') . ':' ?></div>
                <div class="ticket-content"><?= (!is_null($model->ticketClosingReferee) ? $model->ticketClosingReferee->getNomeCognome() : '-') ?></div>
            </div>

            <?php
            if (!$disableForwardToAnotherCategory) :
            ?>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#forward_date') . ':' ?></div>
                    <div class="ticket-content"><?= ($model->forwarded_from_id ? Yii::$app->getFormatter()->asDatetime($model->forwarded_at) : '-') ?></div>
                </div>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#forward_category') . ':' ?></div>
                    <div class="ticket-content"><?= (!is_null($model->forwardCategory) ? $model->forwardCategory->titolo : '-') ?></div>
                </div>
            <?php
            endif;
            ?>

            <?php
            if (!$model->isGuestTicket()) :
            ?>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#previous_ticket') . ':' ?></div>
                    <div class="ticket-content"><?= (!is_null($model->forwardedFromTicket) ? $model->forwardedFromTicket->titolo . ' (' . $model->getAttributeLabel('id') . ': ' . ($model->forwardedFromTicket->id) . ')' : '-') ?></div>
                </div>
                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', '#next_ticket') . ':' ?></div>
                    <div class="ticket-content"><?= (!is_null($model->nextTicket) ? $model->nextTicket->titolo . ' (' . $model->getAttributeLabel('id') . ': ' . ($model->nextTicket->id) . ')' : '-') ?></div>
                </div>
            <?php
            endif;
            ?>
        </div>
    </div>

    <?php
    // compatibilità con cose fatte in precedenze...
    // il prossimo blocco di informazioni è commetato in html... quindi non visibile!
    // lo mantengo solo per mantenere il codice scritto, ma lo tolgo dalle strutture di bootstrap per non
    // dare fastidio alla grafica...
    ?>

    <!--

    <?=
    ByAtWidget::widget([
        'model' => $model,
        'byAt' => 'created',
        'byAtLabel' => 'Apertura ticket',
    ])
    ?>
    <?=
    ByAtWidget::widget([
        'model' => $model,
        'byAt' => 'updated',
        'byAtLabel' => 'Ultimo aggiornamento ticket',
    ])
    ?>
    <?=
    ByAtWidget::widget([
        'model' => $model,
        'byAt' => 'closed',
        'byAtLabel' => 'Chiusura ticket',
    ])
    ?>

    <?php if (!is_null($model_ticket_forwarded_from)) { ?>
        <?=
        ByAtWidget::widget([
            'model' => $model,
            'byAt' => 'forwarded',
            'byAtLabel' => 'Inoltrato da altra categoria',
        ])
        ?>
    <?php } ?>

    <?php if (!is_null($model_ticket_forwarded_to)) { ?>
        <?=
        ByAtWidget::widget([
            'model' => $model,
            'byAt' => 'closed', // chi ha chiuso il vecchio è lo stesso che ha fatto il forward
            'byAtLabel' => 'Inoltrato ad altra categoria',
        ])
        ?>
    <?php } ?>

    -->

    <?php
    if (!$model->isGuestTicket()) :
    ?>
        <div class="row">
            <div class="col-xs-12 ticket-date">

                <?=
                Html::a(AmosTicket::t('amosticket', 'Vedi tutti i suoi ticket'), Url::toRoute([
                    '/ticket/ticket',
                    // 'enableSearch' => 1, apre la form
                    'TicketSearch[created_by]' => $model->created_by
                ]), ['class' => ""]);
                ?>

            </div>
        </div>
    <?php
    endif;
    ?>

    <?php if (!is_null($model_ticket_forwarded_from)) { ?>
        <div class="row">
            <div class="col-xs-12 ticket-forward-from nop">
                <div>
                    <div class="ticket-label">
                        <?= AmosTicket::t('amosticket', 'Ticket precedente') . ':' ?>
                    </div>
                    <div class="ticket-content">
                        <?= $model_ticket_forwarded_from->id ?>
                        <?php
                        if ($theUser->can('TICKET_READ', ['model' => $model_ticket_forwarded_from])) {
                            echo Html::a(AmosTicket::t('amosticket', 'Vedi'), Url::toRoute([
                                'view',
                                'id' => $model_ticket_forwarded_from->id
                            ]), ['class' => "btn btn-secondary"]);
                        }
                        ?>
                    </div>
                </div>
                <div>
                    <div class="ticket-label">
                        <?php
                        $forwarded_from_categoria = TicketCategorie::findOne($model_ticket_forwarded_from->ticket_categoria_id)
                        ?>
                        <?= AmosTicket::t('amosticket', 'Categoria del ticket precedente') . ':' ?>
                    </div>
                    <div class="ticket-content">
                        <?= $forwarded_from_categoria->nomeCompleto ?>
                    </div>
                </div>
                <?php if ($model->forward_message && (!$isOperatore || $model->forward_message_to_operator)) { ?>
                    <div>
                        <div class="ticket-label"><?= AmosTicket::t('amosticket', 'Messaggio di inoltro') . ':' ?></div>
                        <div class="ticket-content"><?= $model->forward_message ?></div>
                    </div>
                <?php } // $model->forward_message  
                ?>
                <br />
            </div>
        </div>
    <?php } ?>

    <?php if (!is_null($model_ticket_forwarded_to)) { ?>
        <div class="row">
            <div class="col-xs-12 ticket-forward-to nop">

                <div>
                    <div class="ticket-label"><?= AmosTicket::t('amosticket', 'Ticket successivo') . ':' ?></div>
                    <div class="ticket-content">
                        <?= $model_ticket_forwarded_to->id ?>
                        <?php
                        if ($theUser->can('TICKET_READ', ['model' => $model_ticket_forwarded_to])) {
                            echo Html::a(AmosTicket::t('amosticket', 'Vedi'), Url::toRoute([
                                'view',
                                'id' => $model_ticket_forwarded_to->id
                            ]), ['class' => "btn btn-secondary"]);
                        }
                        ?>
                    </div>
                </div>
                <div>
                    <div class="ticket-label">
                        <?php
                        // $ticketCategory->nomeCompleto
                        $forwarded_to_categoria = TicketCategorie::findOne($model_ticket_forwarded_to->ticket_categoria_id)
                        ?>
                        <?= AmosTicket::t('amosticket', 'Categoria del ticket successivo' . ':') ?>
                    </div>
                    <div class="ticket-content">
                        <?= $forwarded_to_categoria->nomeCompleto ?>
                    </div>
                </div>
                <?php if ($model_ticket_forwarded_to->forward_message && (!$isOperatore || $model_ticket_forwarded_to->forward_message_to_operator)) { ?>
                    <div>
                        <div class="ticket-label"><?= AmosTicket::t('amosticket', 'Messaggio di inoltro') . ':' ?></div>
                        <div class="ticket-content"><?= $model_ticket_forwarded_to->forward_message ?></div>
                    </div>
                <?php } // $model->forward_message 
                ?>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-xs-12 ticket-answer">
            <?php
            $firstAnswer = $model->getFirstAnswer();
            if (!is_null($firstAnswer)) {
                $firstAnswerUser = $model->getCommentCreatorUser($firstAnswer)->one();
            ?>
                <div>
                    <div class="ticket-label">
                        <?= AmosTicket::t('amosticket', 'Ticket preso in carico da') . ':' ?>
                    </div>
                    <div class="ticket-content">
                        <?= $firstAnswerUser->nome . " " . $firstAnswerUser->cognome . " - " .  Yii::$app->getFormatter()->asDatetime($firstAnswer->created_at) ?>
                    </div>
                </div>
            <?php } ?>
            <?php
            $lastAnswer = $model->getLastAnswer();
            if (!is_null($lastAnswer) && ($lastAnswer->id != $firstAnswer->id)) {
            ?>
                <?php $lastAnswerUser = $model->getCommentCreatorUser($lastAnswer)->one(); ?>
                <div>
                    <div class="ticket-label">
                        <?= AmosTicket::t('amosticket', 'Ultima risposta di un referente') . ':' ?>
                    </div>
                    <div class="ticket-content">
                        <?= $lastAnswerUser->nome . " " . $lastAnswerUser->cognome . " -  " . Yii::$app->getFormatter()->asDatetime($lastAnswer->created_at) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 ticket-text nop">

            <div class="col-xs-12 ticket-label-desc">
                <h3><?= $model->getAttributeLabel('descrizione') ?></h3>
            </div>
            <div class="col-xs-12 nop ticket-desc">
                <?php
                $description = \yii\helpers\HtmlPurifier::process($model->descrizione);
                echo nl2br($description);
                ?>
            </div>
            <div class="col-xs-12 nop ticket-comments">
                <?php
                $layoutWidget = '';
                if ($model->status == Ticket::TICKET_WORKFLOW_STATUS_CLOSED) {
                    $layoutWidget = '<div id="comments-container">{commentsSection}</div>';
                } else {
                    $layoutWidget = '<div id="comments-container">{commentSection}{commentsSection}</div>';
                }

                echo CommentsWidget::widget([
                    'model' => $model,
                    'layout' =>  $layoutWidget,
                ]);
                ?>

                <?php
                /*
                    if ($model->isCommentable()) { ?>
                        <div class="footer_sidebar text-right">
                            <?= Html::a(
                                AmosTicket::t('amostickets', 'Contribuisci'), Url::current() . '#comments_contribute', [
                                    'class' => 'btn btn-navigation-primary',
                                    'title' => AmosTicket::t('amostickets', 'commenta')
                                ]
                            ) ?>
                        </div>
                    <?php } // isCommentable()  */ ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 m-t-15">
        <?= Html::a(Yii::t('amoscore', 'Indietro'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>

</div>