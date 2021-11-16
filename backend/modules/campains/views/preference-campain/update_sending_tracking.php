<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/compains/views 
 */

use amos\newsletter\utility\MailupUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\Module;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\utility\MailUpEmailUtility;
use open20\amos\core\helpers\Html;
use open20\amos\workflow\widgets\WorkflowTransitionSimplifiedButtonsWidget;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\utilities\WorkflowTransitionWidgetUtility;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * @var yii\web\View $this
 * @var backend\modules\campains\models\PreferenceCampain $model
 */
if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
    $this->title = Yii::t('campains', 'Aggiorna', [
        'modelClass' => 'Preference Campain',
    ]);
endif;

$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('campains', 'Preference Campain'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('campains', 'Modifica campagna - Invio e monitoraggio');


$haveNewsletter = CampainsUtility::campainHaveNewsletterChannel($model);
$haveSms = CampainsUtility::campainHaveSmsChannel($model);
$haveApp = CampainsUtility::campainHaveAppChannel($model);

$newsletterModule = \Yii::$app->getModule('newsletter');
$mailServiceClassname = $newsletterModule->mail_service_driver;
$mailService = new $mailServiceClassname();
?>

<?php
if ($model->status == PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_TOBEAPPROVED) :
    $form = ActiveForm::begin();

?>
    <?php
    echo WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW,
        'viewWidgetOnNewRecord' => true,

        'initialStatus' => PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_TOBEAPPROVED,
    ]);
    ?>
<?php

    ActiveForm::end();
endif;
?>



<div class="invio-monitoraggio">
    <h5 class="primary-color font-weight-bold"><?= $model->titolo ?></h5>
    <div class="row variable-gutters flex-wrap">

        <?php

        // $saveBtn = Html::submitButton(Module::t('campains', 'INVIA'), ['class' => 'btn btn-sm btn-primary py-md-1 px-md-4']);
        // $draftButtons = [

        //     PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED => [
        //         'button' => $saveBtn,
        //         'description' => '',
        //     ],
        // ];

        if ($haveNewsletter) :
            /** @var PreferenceCampainChannelMm $channelMm */
            $channelMm = CampainsUtility::campainGetNewsletterModel($model);

            // logica su vista... da evitare... per ora va bene così... 
            $report = null;
            if (!empty($channelMm->mailup_message_id)) {
                $report = $mailService->getEmailRecipients($channelMm->mailup_message_id);
                $reportNumOpened = $mailService->getStatisticOpened($channelMm->mailup_message_id, true);
                $reportNumBounces = $mailService->getStatisticBounces($channelMm->mailup_message_id, true);
                $reportClicks = $mailService->getStatisticClickedLinks($channelMm->mailup_message_id, true);
                $percentage = '-';

                $reportPages = $mailService->getStatisticMessagePages($channelMm->mailup_message_id);
                $linkReportOverall = (property_exists ($reportPages, 'Overall') && !empty($reportPages->Overall))? $reportPages->Overall: null;
            }


        ?>

            <div class="col-md-6 px-3 mb-4 mb-md-0">
                <div class="neutral-1-bg-a1 h-100 w-100 px-3">
                    <div class="d-flex flex-wrap py-4 align-items-top">
                        <div class="d-flex mr-auto pr-2">
                            <svg class="icon mr-2">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_email"></use>
                            </svg>
                            <p class="h5 font-weight-bold mb-0">Newsletter</p>
                        </div>

                        <?php if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED) : ?>

                            <!-- <a class="btn btn-sm btn-primary py-md-1 px-md-4" href="#">Invia</a> -->
                            <?php $form = ActiveForm::begin(); ?>

                                <div class="badge badge-pill badge-secondary">
                                    <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                                </div>
                                
                                <?php
                                    echo $form->field($channelMm, 'id')->hiddenInput()->label('');
                                    echo WorkflowTransitionButtonsWidget::widget([
                                        'form' => $form,
                                        'model' => $channelMm,
                                        'workflowId' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                                        'viewWidgetOnNewRecord' => true,
                                        'internalWorkflowStatusId' => 'workflow-newsletter-id',
                                        // 'initialStatusName' => "TOBEAPPROVED",
                                        'initialStatus' =>  PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_TOBEAPPROVED,

                                    ]);
                                ?>
                            <?php ActiveForm::end(); ?>
                        
                        <?php else : ?>

                            <div>
                                <div class="badge badge-pill badge-secondary">
                                    <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                                </div>
                                <?php
                                    // se ci troviamo in sending in progress ci sono gli stati di mailup da visualizzare
                                    if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS) :
                                        $statusLabel = isset(PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status]) ? PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status] : '';
                                        echo '<br />Stato MailUp: ' . $statusLabel;
                                    endif;
                                    ?>

                               <?php 
                                    if ($channelMm->status != PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT) :    
                                        if (!empty($channelMm->mailup_message_id) && (!empty($channelMm->mailup_list_id)) && !empty($channelMm->mailup_status)):
                                        $nSent = MailUpEmailUtility::getNumberOfSendingRecipients($channelMm);
                                        $tot = $channelMm->numberOfRecipients;
                                        if ($nSent > 0) {
                                            $perc = floor(($nSent * 100) / $tot);
                                        } else {
                                            $perc = 0;
                                        }
                                    ?>

                                    <div class="progress-bar-label text-left">
                                        <span class="sr-only">Progresso </span>INVIO IN CORSO: <?= $perc ?>%
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $perc ?>%" aria-valuenow="<?= $perc ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <?php
                                    endif;
                                endif;
                                ?>
                            </div>
                        <?php
                        endif;
                        ?>

                        <div class="progress-bar-container">

                            <!-- <div class="progress-bar-label text-left">
                            <span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Destinatari</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p class="font-weight-bold">
                                <?php
                                if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT) :
                                    ?>
                                    <?= $channelMm->mailup_n_sent ?>
                                    <?php
                                else :
                                    ?>
                                    <?= $channelMm->numberOfRecipients ?>
                                    <?php
                                endif;
                                ?>                            
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Data inizio</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p><?= Yii::$app->formatter->asDatetime($channelMm->start_date) ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Data fine</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p><?= Yii::$app->formatter->asDatetime($channelMm->end_date) ?></p>
                        </div>
                    </div>
                    <hr class="w-100 border-black">
                    <div class="d-flex align-items-center mb-4">
                        <svg class="icon mr-2">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_equalizer"></use>
                        </svg>
                        <p class="text-uppercase font-weight-bold mb-0">Monitoraggio Newsletter</p>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-left">email inviate</p>
                        </div>
                        <div class="col-6">
                            <?php
                            $nSent = '-';
                            if (!is_null($report)) {
                                $nSent = $report->TotalElementsCount;
                            }

                            if (!empty($channelMm->mailup_n_sent)) {
                                $percentage = floor(($nSent / $channelMm->mailup_n_sent) * 100);
                            }
                            ?>
                            <p class="text-left text-sm-right"><?= $nSent ?> su <?= $channelMm->mailup_n_sent ? $channelMm->mailup_n_sent : '-'  ?> <strong>(<?= $percentage ? $percentage : '-' ?>%)</strong></p>
                        </div>
                    </div>
                    <!-- <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-left">email consegnate</p>
                        </div>
                        <div class="col-6">
                            <p class="text-left text-sm-right">922 su 1270 <strong>(85%)</strong></p>
                        </div>
                    </div> -->
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-left">email aperte</p>
                        </div>
                        <div class="col-6">
                            <?php
                            $percentage = '-';
                            if (!empty($channelMm->mailup_n_sent)) {
                                $percentage = floor(($reportNumOpened / $channelMm->mailup_n_sent) * 100);
                            }
                            ?>
                            <p class="text-left text-sm-right"><?= isset($reportNumOpened) ? $reportNumOpened : '-' ?> su <?= ($channelMm->mailup_n_sent) ? $channelMm->mailup_n_sent : '-' ?> <strong>(<?= $percentage ?>%)</strong></p>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-left">Count dei clicks sui Link</p>
                        </div>
                        <div class="col-6">
                            <?php
                            $clicks = '-';
                            $nLink = '-';

                            if (!empty($reportClicks)) {
                                $nLink = $reportClicks->TotalElementsCount;
                                $clicks = 0;
                                foreach ($reportClicks->Items as  $itemLink) {
                                    $clicks = $clicks + $itemLink->Count;
                                }
                            }

                            ?>
                            <p class="text-left text-sm-right"><?= $clicks ?> click su <?= $nLink ?> link</p>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p class="text-left">email rimbalzate</p>
                        </div>
                        <div class="col-6">
                            <?php
                            $percentage = '-';
                            if (!empty($channelMm->mailup_n_sent)) {
                                $percentage = floor(($reportNumBounces / $channelMm->mailup_n_sent) * 100);
                            }
                            ?>
                            <p class="text-left text-sm-right"><?= isset($reportNumBounces) ? $reportNumBounces : '-' ?> su <?= ($channelMm->mailup_n_sent) ? $channelMm->mailup_n_sent : '-' ?> <strong>(<?= $percentage ?>%)</strong></p>
                        </div>
                    </div>

                    <?php
                    if (!is_null($linkReportOverall) && ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT)):
                        ?>
                        <div class="row align-items-center">
                            <div class="col-12">
                                <p class="text-left">
                                    
                                <a href="<?= $linkReportOverall ?>" target="_blank">DATI ANALITICI CAMPAGNA...</a>
                                </p>
                            </div>
                            <?php
                                $today = new DateTime();
                                $dateTimeToStats = new DateTime($channelMm->end_date);
                                $dateTimeToStats = $dateTimeToStats->add(new DateInterval('P0D'));
                                if ($today >= $dateTimeToStats):
                                    $url = Url::to(['/statistics/preference-campaigns/index', 'CampainsViewsStats[channel_id]' => $channelMm->id]);
                                    ?>
                                    <div class="col-12">
                                        <p class="text-left">
                                            <a href="<?= $url ?>" target="_blank">ANALISI APERTURE...</a>
                                        </p>
                                    </div>
                                    <?php
                                endif;
                            ?>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>
            </div>

        <?php
        endif;

        if ($haveSms):
            /** @var PreferenceCampainChannelMm $channelMm */
            $channelMm = CampainsUtility::campainGetSmsModel($model);

            $report = null;
            if (!empty($channelMm->mailup_message_id)) {
                $report = $mailService->reportSMS($channelMm->mailup_message_id);
                // VarDumper::dump( $report, $depth = 10, $highlight = true);
            }

        ?>

            <div class="col-md-6 px-3 mb-4 mb-md-0">
                <div class="neutral-1-bg-a1 h-100 w-100 px-3">
                    <div class="d-flex flex-wrap py-4 align-items-top">
                        <div class="d-flex mr-auto pr-2">
                            <svg class="icon mr-2">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_chat"></use>
                            </svg>
                            <p class="h5 font-weight-bold mb-0">SMS</p>
                        </div>
                        <?php if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED) : ?>
                            <!-- <a class="btn btn-sm btn-primary py-md-1 px-md-4" href="#">Invia</a> -->
                            <?php $form = ActiveForm::begin(); ?>

                            <div class="badge badge-pill badge-secondary">
                                <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                            </div>
                            
                            <?php
                                echo $form->field($channelMm, 'id')->hiddenInput()->label('');
                                echo WorkflowTransitionButtonsWidget::widget([
                                    'form' => $form,
                                    'model' => $channelMm,
                                    'workflowId' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                                    'viewWidgetOnNewRecord' => true,
                                    'internalWorkflowStatusId' => 'workflow-sms-id',

                                    'initialStatus' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_TOBEAPPROVED,

                                    // 'draftButtons' => $draftButtons
                                ]);
                            ?>
                            <?php ActiveForm::end(); ?>
                        <?php
                        else :
                        ?>
                            <div>
                                <div class="badge badge-pill badge-secondary">
                                    <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                                    
                                </div>
                                <?php
                                // se ci troviamo in sending in progress ci sono gli stati di mailup da visualizzare
                                if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS) :
                                    $statusLabel = isset(PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status]) ? PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status] : '';
                                    echo '<br />Stato MailUp: ' . $statusLabel;
                                endif;
                                ?>
                                <?php 
                                if ($channelMm->status != PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT) :    
                                        if (!empty($channelMm->mailup_message_id) && (!empty($channelMm->mailup_list_id)) && !empty($channelMm->mailup_status)):
                                            $nSent = MailUpEmailUtility::getNumberOfSendingRecipients($channelMm);
                                            $tot = $channelMm->numberOfRecipients;
                                            if ($nSent > 0) {
                                                $perc = floor(($nSent * 100) / $tot);
                                            } else {
                                                $perc = 0;
                                            }
                                        ?>

                                        <div class="progress-bar-label text-left">
                                            <span class="sr-only">Progresso </span>INVIO IN CORSO: <?= $perc ?>%
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: <?= $perc ?>%" aria-valuenow="<?= $perc ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <?php
                                        endif;
                                    endif;
                                ?>
                                <!-- <div class="progress-bar-label text-left">
                                    <span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> -->
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Destinatari</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p class="font-weight-bold"><?= $channelMm->numberOfRecipients ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Data inizio</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p><?= Yii::$app->formatter->asDatetime($channelMm->start_date) ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Data fine</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p><?= Yii::$app->formatter->asDatetime($channelMm->end_date) ?></p>
                        </div>
                    </div>
                    <hr class="w-100 border-black">
                    <div class="d-flex align-items-center mb-4">
                        <svg class="icon mr-2">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_equalizer"></use>
                        </svg>
                        <p class="text-uppercase font-weight-bold mb-0">Monitoraggio sms</p>
                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-6">
                            <p class="text-left">sms inviati</p>
                        </div>
                        <div class="col-6">
                            <?php
                            $numMess = '-';
                            if (!is_null($report)) {
                                $numMess = $report->TotalSmsSent;
                            }
                            ?>
                            <p class="text-left text-sm-right">
                                <?= $numMess ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        endif;

        if ($haveApp):
            /** @var PreferenceCampainChannelMm $channelMm */
            $channelMm = CampainsUtility::campainGetAppModel($model);

            // $report = null;
            // if (!empty($channelMm->mailup_message_id)) {
            //     $report = $mailService->reportSMS($channelMm->mailup_message_id);
            //     // VarDumper::dump( $report, $depth = 10, $highlight = true);
            // }

        ?>

            <div class="col-md-6 px-3 mb-4 mb-md-0">
                <div class="neutral-1-bg-a1 h-100 w-100 px-3">
                    <div class="d-flex flex-wrap py-4 align-items-top">
                        <div class="d-flex mr-auto pr-2">
                            <svg class="icon mr-2">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_chat"></use>
                            </svg>
                            <p class="h5 font-weight-bold mb-0">APP</p>
                        </div>
                        <?php if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED) : ?>
                            <!-- <a class="btn btn-sm btn-primary py-md-1 px-md-4" href="#">Invia</a> -->
                            <?php $form = ActiveForm::begin(); ?>

                            <div class="badge badge-pill badge-secondary">
                                <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                            </div>
                            
                            <?php
                                echo $form->field($channelMm, 'id')->hiddenInput()->label('');
                                echo WorkflowTransitionButtonsWidget::widget([
                                    'form' => $form,
                                    'model' => $channelMm,
                                    'options' => [
                                     'id' => 'test-id',
                                    ],
                                    'workflowId' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                                    'viewWidgetOnNewRecord' => true,
                                    'internalWorkflowStatusId' => 'workflow-app-id',

                                    'initialStatus' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_TOBEAPPROVED,

                                    // 'draftButtons' => $draftButtons
                                ]);
                            ?>
                            <?php ActiveForm::end(); ?>
                        <?php
                        else :
                        ?>
                            <div>
                                <div class="badge badge-pill badge-secondary">
                                    <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                                    
                                </div>
                                <?php
                                // se ci troviamo in sending in progress ci sono gli stati di mailup da visualizzare
                                if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS) :
                                    $statusLabel = isset(PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status]) ? PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status] : '';
                                    echo '<br />Stato invio notifiche: ' . $statusLabel;
                                endif;
                                ?>
                                <?php 
                                if ($channelMm->status != PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT) :    
                                        if (!empty($channelMm->mailup_message_id) && (!empty($channelMm->mailup_list_id)) && !empty($channelMm->mailup_status)):
                                            $nSent = MailUpEmailUtility::getNumberOfSendingRecipients($channelMm);
                                            $tot = $channelMm->numberOfRecipients;
                                            if ($nSent > 0) {
                                                $perc = floor(($nSent * 100) / $tot);
                                            } else {
                                                $perc = 0;
                                            }
                                        ?>

                                        <div class="progress-bar-label text-left">
                                            <span class="sr-only">Progresso </span>INVIO IN CORSO: <?= $perc ?>%
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: <?= $perc ?>%" aria-valuenow="<?= $perc ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <?php
                                        endif;
                                    endif;
                                ?>
                                <!-- <div class="progress-bar-label text-left">
                                    <span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> -->
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Destinatari</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p class="font-weight-bold"><?= $channelMm->numberOfRecipients ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Data inizio</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p><?= Yii::$app->formatter->asDatetime($channelMm->start_date) ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 col-xs-4">
                            <p class="text-muted text-uppercase font-weight-bold">Data fine</p>
                        </div>
                        <div class="col-7 col-xs-8">
                            <p><?= Yii::$app->formatter->asDatetime($channelMm->end_date) ?></p>
                        </div>
                    </div>
                    <hr class="w-100 border-black">
                    <div class="d-flex align-items-center mb-4">
                        <svg class="icon mr-2">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_equalizer"></use>
                        </svg>
                        <p class="text-uppercase font-weight-bold mb-0">Monitoraggio app</p>
                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-6">
                            <p class="text-left">Comunicazioni inviate</p>
                        </div>
                        <div class="col-6">
                            <?php
                            $numMess = '-';
                            // if (!is_null($report)) {
                            //     $numMess = $report->TotalSmsSent;
                            // }
                            ?>
                            <p class="text-left text-sm-right">
                                <?= $numMess ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        endif;
        ?>

    </div>
</div>