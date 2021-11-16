<?php

use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\utility\CampainsUtility;
use open20\amos\core\utilities\WorkflowTransitionWidgetUtility;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\VarDumper;

$haveNewsletter = CampainsUtility::campainHaveNewsletterChannel($model);
$haveSms = CampainsUtility::campainHaveSmsChannel($model);
$haveApp = CampainsUtility::campainHaveAppChannel($model);


$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

// VarDumper::dump( $haveNewsletter, $depth = 10, $highlight = true);
?>

<div class="collapse-body d-flex flex-column bg-light-grey py-3">
        <div class="detail-intestation d-flex align-items-center p-2">
        <span class="btn-xs btn-icon p-1 rounded-icon rounded-secondary rounded-circle bg-secondary p-1 ">
                <svg class="icon icon-white"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_search"></use></svg>
            </span>
            <p class="ml-2 text-uppercase mb-0 small font-weight-bold">Dettagli</p>
        </div>
        <div class="detail-value-intestation  d-flex flex-column flex-md-row justify-content-between align-items-start p-2">
            <div class="canale-container col-12 col-md-2 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary p-1 text-uppercase mb-0 small font-weight-bold full-size-bg-100">Canale</p>
                <?php
                if ($haveNewsletter):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetNewsletterModel($model);
                ?>
                    <p class="text-black mb-0 p-1 small font-weight-bold"><?= $channelMm->preferenceChannel->title ?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveSms):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetSmsModel($model);
                ?>
                    <p class="text-black mb-0 p-1 small font-weight-bold"><?= $channelMm->preferenceChannel->title ?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveApp):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetAppModel($model);
                ?>
                    <p class="text-black mb-0 p-1 small font-weight-bold"><?= $channelMm->preferenceChannel->title ?></p>
                <?php
                endif;
                ?>
                
            </div>

            <div class="destinatari-container col-12 col-md-3 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary text-uppercase p-1 mb-0 small font-weight-bold full-size-bg-100">Destinatari</p>
                <?php
                if ($haveNewsletter):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetNewsletterModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?= $channelMm->numberOfRecipients ?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveSms):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetSmsModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?= $channelMm->numberOfRecipients ?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveApp):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetAppModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?= $channelMm->numberOfRecipients ?></p>
                <?php
                endif;
                ?>
                
            </div>

            <div class="data-inizio-container col-12 col-md-2 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary text-uppercase p-1 mb-0 small font-weight-bold full-size-bg-100">Data inizio</p>

                <?php
                if ($haveNewsletter):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetNewsletterModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?= !empty($channelMm->start_date)?Yii::$app->getFormatter()->asDatetime($channelMm->start_date):'-'?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveSms):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetSmsModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?= !empty($channelMm->start_date )?Yii::$app->getFormatter()->asDatetime($channelMm->start_date ):'-' ?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveApp):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetAppModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?= !empty($channelMm->start_date )?Yii::$app->getFormatter()->asDatetime($channelMm->start_date ):'-' ?></p>
                <?php
                endif;
                ?>

            </div>

            <div class="data-fine-container col-12 col-md-2 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary text-uppercase p-1 mb-0 small font-weight-bold full-size-bg-100">Data fine</p>
                <?php
                if ($haveNewsletter):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetNewsletterModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?= !empty($channelMm->end_date)?Yii::$app->getFormatter()->asDatetime($channelMm->end_date ):'-'?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveSms):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetSmsModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?=!empty($channelMm->end_date)? Yii::$app->getFormatter()->asDatetime($channelMm->end_date ):'-'?></p>
                <?php
                endif;
                ?>

                <?php
                if ($haveApp):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetAppModel($model);
                ?>
                    <p class=" text-black p-1 mb-0 small"><?=!empty($channelMm->end_date)? Yii::$app->getFormatter()->asDatetime($channelMm->end_date ):'-'?></p>
                <?php
                endif;
                ?>
            </div>            

            <div class="invio-container col-12 col-md-3 p-0 d-flex flex-row flex-md-column">
                <p class="text-secondary p-1 pl-md-5 text-uppercase mb-0 small font-weight-bold full-size-bg-100">Invio</p>



                <?php
                if ($haveNewsletter):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetNewsletterModel($model);
                ?>


                    <div class="progress-bar-label p-1 pl-md-5 text-left">
                        <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                        <?php
                        // se ci troviamo in sending in progress ci sono gli stati di mailup da visualizzare
                        if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS) :
                            $statusLabel = isset(PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status])? PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status]: '';
                            echo '<br />Stato MailUp: ' . $statusLabel;
                        endif;
                        ?>
                    </div>

                    <!-- <div>
                    <div class="progress-bar-label p-1 pl-md-5 text-left"><span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                    </div>
                    <div class="m-1 progress ml-md-5">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    </div> -->
                <?php
                endif;
                ?>

                <?php
                if ($haveSms):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetSmsModel($model);
                ?>
                    <div>

                    <div class="progress-bar-label p-1 pl-md-5 text-left">
                        <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                        <?php
                        // se ci troviamo in sending in progress ci sono gli stati di mailup da visualizzare
                        if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS) :
                            $statusLabel = isset(PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status])? PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status]: '';
                            echo '<br />Stato MailUp: ' . $statusLabel;
                        endif;
                        ?>
                    </div>

                    <!-- <div class="progress-bar-label p-1 pl-md-5 text-left"><span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                    </div>
                    <div class="m-1 progress ml-md-5">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div> -->
                    </div>
                <?php
                endif;
                ?>

                <?php
                if ($haveApp):
                    /** @var PreferenceCampainChannelMm $channelMm */
                    $channelMm = CampainsUtility::campainGetAppModel($model);
                ?>
                    <div>

                    <div class="progress-bar-label p-1 pl-md-5 text-left">
                        <?= WorkflowTransitionWidgetUtility::getLabelStatus($channelMm) ?>
                        <?php
                        // se ci troviamo in sending in progress ci sono gli stati di mailup da visualizzare
                        if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENDINGINPROGRESS) :
                            $statusLabel = isset(PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status])? PreferenceCampainChannelMm::getLabelStatus()[$channelMm->mailup_status]: '';
                            echo '<br />Stato MailUp: ' . $statusLabel;
                        endif;
                        ?>
                    </div>

                    <!-- <div class="progress-bar-label p-1 pl-md-5 text-left"><span class="sr-only">Progresso </span>INVIO IN CORSO: 75%
                    </div>
                    <div class="m-1 progress ml-md-5">
                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div> -->
                    </div>
                <?php
                endif;
                ?>

            </div>
            
        </div>
    </div>