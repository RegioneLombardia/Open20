<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\utility\TicketUtility;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var open2\amos\ticket\models\Ticket $model
 */

/** @var AmosTicket $module */
$module = \Yii::$app->getModule('ticket');
$isNewRecord = $model->isNewRecord;
$enableOrganizationNameString = (!empty($module) ? $module->enableOrganizationNameString : false);
$buttonLabel = ($isNewRecord ? AmosTicket::t('amosticket', 'Invia') : AmosTicket::t('amosticket', 'Modifica'));
$statusToRenderToHide = $model->getStatusToRenderToHide();
/** @var UserProfile $creatorUserProfile */
$creatorUserProfile = $model->createdUserProfile;

$nameCat = '';
/** @var TicketCategorie $cat */
$cat = null;
if ($isNewRecord && !empty($_GET['ticket_categoria_id'])) {
    $cat = TicketCategorie::findOne($_GET['ticket_categoria_id']);
    if (!empty($cat)) {
        $nameCat = $cat->nomeCompleto;
    }
}

if (empty($nameCat)) {
    $cat = $model->ticketCategoria;
    $nameCat = $cat->nomeCompleto;
}

$disableInfoFields = (!empty($module) ? $module->disableInfoFields : false);
$disableCategory = (!empty($module) ? $module->disableCategory : false);
$disableTicketOrganization = (!empty($module) ? $module->disableTicketOrganization : false);

if (!$disableInfoFields) {
    if ($isNewRecord) {
        if (Yii::$app->user->isGuest) {
            $creatorNameSurname = AmosTicket::t('amosticket', '#guest_user');
            $email = '-';
        } else {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $creatorNameSurname = $user->userProfile->nomeCognome;
            $email = $user->email;
        }
        $createdAt = date('d-m-Y H:i:s');
        if ($model->hasWorkflowStatus()) {
            $status = $model->getWorkflowStatus()->getLabel();
        } elseif ($cat->administrative) {
            $status = AmosTicket::t('amosticket', "TICKET_WORKFLOW_STATUS_WAITING_TECHNICAL_ASSISTANCE");
        } else {
            $status = AmosTicket::t('amosticket', "TICKET_WORKFLOW_STATUS_PROCESSING");
        }
    } else {
        $creatorNameSurname = $creatorUserProfile->nomeCognome;
        $email = $creatorUserProfile->user->email;
        $createdAt = $model->created_at;
        $status = $model->getWorkflowStatus()->getLabel();
    }
}

?>

<div class="ticket-form col-xs-12 nop">
    <?php
    $form = ActiveForm::begin(['id' => 'ticket-form',]);
    $this->beginBlock('dettagli');
    if (!$isNewRecord) : ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $model->getAttributeLabel('id') ?>: <?= $model->id ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!$disableInfoFields) { ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $model->getAttributeLabel('ticket_categoria_id') ?>: <?= $nameCat; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $model->getAttributeLabel('created_by') ?>:
                <?= $creatorNameSurname; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= AmosTicket::t('amosticket', '#ticket_creator_email') ?>:
                <?= $email; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $model->getAttributeLabel('created_at') ?>:
                <?= Yii::$app->formatter->asDatetime($createdAt); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $model->getAttributeLabel('status') ?>:
                <?= $status; ?>
            </div>
        </div>
    <?php } ?>
    
    <?php if (!$disableCategory) { ?>
        <div class="row">

            <div class="col-lg-12 col-sm-12">
                <?php
                // if (false) {
                //     $ticketCategories = TicketUtility::getTicketCategories(null, true)->orderBy('titolo')->all();
                //     $ticketCategoryId = $model->ticket_categoria_id;
                //     if (!$model->ticket_categoria_id && (count($ticketCategories) == 1)) {
                //         $ticketCategoryId = $ticketCategories[0]->id;
                //     }
                //
                //     echo $form->field($model, 'ticket_categoria_id')->widget(Select::className(),
                //         [
                //             'auto_fill' => true,
                //             'options' => [
                //                 'placeholder' => AmosTicket::t('amosticket', '#category_field_placeholder'),
                //                 'id' => 'ticket_categoria_id-id',
                //                 'disabled' => false,
                //                 'value' => $ticketCategoryId
                //             ],
                //             'data' => ArrayHelper::map(
                //                 TicketUtility::getTicketCategories(null, true)
                //                     ->orderBy('titolo')
                //                     ->all(), 'id', 'titolo'
                //             ),
                //         ]);
                // }
                
                if ($isNewRecord && !empty($_GET['ticket_categoria_id'])) {
                    $model->ticket_categoria_id = $_GET['ticket_categoria_id'];
                }
                
                echo $form->field($model, 'ticket_categoria_id')->hiddenInput()->label(false);
                ?>
            </div>
        </div>
    
    <?php } ?>
    
    <?php if (!$disableTicketOrganization) { ?>
        <?php if ($enableOrganizationNameString) { ?>
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'organization_name')->textInput(['maxlength' => true]) ?>
            </div>
        <?php } else { ?>
            <div class="col-lg-12 col-sm-12">
                <?php
                $searchOrganizationsUserId = ($isNewRecord ? Yii::$app->user->id : $model->created_by);
                echo $form->field($model, 'ticketOrganization')->widget(Select::classname(),
                    [
                        'data' => TicketUtility::getOrganizationsAndHeadquartersByUserId($searchOrganizationsUserId),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'multiple' => false,
                            'placeholder' => AmosTicket::t('amosticket', 'Seleziona') . '...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                ?>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="col-lg-12 col-sm-12">
        <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 col-sm-12">
        <?= $form->field($model, 'descrizione')->widget(TextEditorWidget::className(),
            [
                'clientOptions' => [
                    'placeholder' => AmosTicket::t('amosticket',
                        '#description_field_placeholder'),
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]);
        ?>
    </div>
    
    <?php if (!is_null($cat) && $cat->enable_dossier_id): ?>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'dossier_id')->textInput(['maxlength' => true]) ?>
        </div>
    <?php endif; ?>
    <?php if (!is_null($cat) && $cat->enable_phone): ?>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    <?php endif; ?>

    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>
    
    <?= $this->blocks['dettagli'] ?>
    
    <?= WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => Ticket::TICKET_WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
        'closeButton' => Html::a(AmosTicket::t('amosticket', 'Annulla'), Yii::$app->session->get('previousUrl'), ['class' => 'btn btn-secondary']),
        'initialStatusName' => "WAITING",
        'initialStatus' => Ticket::TICKET_WORKFLOW_STATUS_WAITING,
        'statusToRender' => $statusToRenderToHide['statusToRender'],
        
        //POII-1147 gli utenti validatore/facilitatore o ADMIN possono sempre salvare la news => parametro a false
        //altrimenti se stato VALIDATO => pulsante salva nascosto
        'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
        
        'draftButtons' => [
            Ticket::TICKET_WORKFLOW_STATUS_WAITING => [
                'button' => Html::submitButton($buttonLabel, ['class' => 'btn btn-workflow']),
                'description' => AmosTicket::t('amosticket', 'il ticket verrà al più presto preso in carico')
            ],
            Ticket::TICKET_WORKFLOW_STATUS_PROCESSING => [
                'button' => Html::submitButton(AmosTicket::t('amosticket', 'Salva'), ['class' => 'btn btn-workflow']),
                'description' => 'le modifiche al ticket'
            ],
            'default' => [
                'button' => Html::submitButton(AmosTicket::t('amosticket', 'Salva in bozza'), ['class' => 'btn btn-workflow']),
                'description' => AmosTicket::t('amosticket', 'le modifiche al ticket'),
            ]
        ]
    ]);
    ?>
    
    <?php ActiveForm::end(); ?>
</div>
