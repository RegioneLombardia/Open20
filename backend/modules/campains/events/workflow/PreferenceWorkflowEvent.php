<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\events\workflow
 * @category   CategoryName
 */

namespace backend\modules\campains\events\workflow;

use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\Module;
use backend\modules\campains\utility\CampainsUtility;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\Event;
use yii\helpers\Console;

/**
 * Class PreferenceWorkflowEvent
 * @package backend\modules\campains\events\workflow
 */
class PreferenceWorkflowEvent
{

    public function whenCamapinIsValidated(Event $event)
    {
        $campain = PreferenceCampain::findOne($event->data->id);

        if (!empty($campain)) {
            $haveNewsletter = CampainsUtility::campainHaveNewsletterChannel($campain);
            $haveSms = CampainsUtility::campainHaveSmsChannel($campain);
            $haveApp = CampainsUtility::campainHaveAppChannel($campain);

            try {
                if ($haveNewsletter) {
                    $channelMm = CampainsUtility::campainGetNewsletterModel($campain);

                    if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_TOBEAPPROVED) {
                        $channelMm->mailup_status = PreferenceCampainChannelMm::STATUS_USERS_TO_IMPORT;
                        $channelMm->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED);
                        $channelMm->save();
                    }

                }
            } catch (WorkflowException $e) {
                Yii::$app->session->addFlash('danger', Module::t('campains', 'Impossibile approvare il canale Email'));
            }

            try {
                if ($haveSms) {
                    $channelMm = CampainsUtility::campainGetSmsModel($campain);

                    if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_TOBEAPPROVED) {
                        $channelMm->mailup_status = PreferenceCampainChannelMm::STATUS_USERS_TO_IMPORT;
                        $channelMm->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED);
                        $channelMm->save();
                    }

                }
            } catch (WorkflowException $e) {
                Yii::$app->session->addFlash('danger', Module::t('campains', 'Impossibile approvare il canale SMS'));
            }

            try {
                if ($haveApp) {
                    $channelMm = CampainsUtility::campainGetAppModel($campain);

                    if ($channelMm->status == PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_TOBEAPPROVED) {
                        $channelMm->mailup_status = PreferenceCampainChannelMm::STATUS_USERS_TO_IMPORT;
                        $channelMm->sendToStatus(PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_APPROVED);
                        $channelMm->save();
                    }

                }
            } catch (WorkflowException $e) {
                Yii::$app->session->addFlash('danger', Module::t('campains', 'Impossibile approvare il canale APP'));
            }

        }

    }

    public function whenCamapinIsApproved(Event $event)
    {
        $channel = PreferenceCampainChannelMm::findOne($event->data->id);
        if (!empty($channel)) {
            $campain = $channel->preferenceCampain
            /** @var PreferenceCampain $campain */;
            if (!empty($campain)) {

                if ($campain->status == PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_APPROVED) {
                    $campain->sendToStatus(PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_SENDINGINPROGRESS);
                    $campain->save(false);
                }

            }
        }
    }


    /**
     * Metodo per il controllo della validita dei preference_campain_channel_mm (canali)
     * al cammbio di stato della preference_campain (campagna)
     * dallo stato TOBEAPPROVED allo stato APPROVED
     *
     * @param Event $event
     * @return void
     */
    public function whenCampainChangeStateToApproved(Event $event){

        // get this preferenc_campain
        $campain = PreferenceCampain::findOne($event->data->id);

        // NEWSLETTER preference_campain_channel_mm
        if( CampainsUtility::existNewsletterChannel($campain) ){

            $preference_campain_channel_email = CampainsUtility::campainGetNewsletterModel($campain);
            // set dello scenario SCENARIO_NEWSLETTER
            $preference_campain_channel_email->scenario = PreferenceCampainChannelMm::SCENARIO_NEWSLETTER;

            // controllo la validita dei dati della preference campain channel newsletter secondo lo scenario SCENARIO_NEWSLETTER
            if( !$preference_campain_channel_email->validate() ){
        
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', "Errore! Non risulta possibile approvare la campagna prima di aver creato i contenuti per il canale E-MAIL."));
                $event->invalidate("Errore! Non risulta possibile approvare la campagna prima di aver creato i contenuti per il canale E-MAIL.");
            }
        }

        // SMS preference_campain_campain_channel_mm
        if( CampainsUtility::existSmsChannel($campain) ){

            $preference_campain_channel_sms = CampainsUtility::campainGetSmsModel($campain);
            // set dello scenario SCENARIO_SMS
            $preference_campain_channel_sms->scenario = PreferenceCampainChannelMm::SCENARIO_SMS;

            if( !$preference_campain_channel_sms->validate() ){

                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', "Errore! Non risulta possibile approvare la campagna prima di aver creato i contenuti per il canale SMS."));
                $event->invalidate("Errore! Non risulta possibile approvare la campagna prima di aver creato i contenuti per il canale SMS.");
            }
        }

       // APP preference_campain_channel_mm
       if( CampainsUtility::existAppChannel($campain) ){

            $preference_campain_channel_app = CampainsUtility::campainGetAppModel($campain);
            // set dello scenario SCENARIO_NEWSLETTER
            $preference_campain_channel_app->scenario = PreferenceCampainChannelMm::SCENARIO_APP;

            // controllo la validita dei dati della preference campain channel newsletter secondo lo scenario SCENARIO_NEWSLETTER
            if( !$preference_campain_channel_app->validate() ){
        
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', "Errore! Non risulta possibile approvare la campagna prima di aver creato i contenuti per il canale APP."));
                $event->invalidate("Errore! Non risulta possibile approvare la campagna prima di aver creato i contenuti per il canale APP.");
            }
        }
    }



    /**
     * Undocumented function
     *  
     * @param Event $event
     * @return void
     */
    public function whenChannelIsSent(Event $event)
    {
        
        $channel = PreferenceCampainChannelMm::findOne($event->data->id);
        if (!empty($channel)) {
            $campain = $channel->preferenceCampain;
            
            /** @var PreferenceCampain $campain */;
            if (!empty($campain)) {
                $allChannelAreSent = true;

                foreach ($campain->preferenceCampainChannelMms as $ch) {
                    if (($ch->id != $channel->id) && ($ch->status != PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT)) {
                        $allChannelAreSent = false;
                    }
                }                

                if ($allChannelAreSent) {
                    $campain->sendToStatus(PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_SENT);
                    $campain->save(false);
                }

            }
        }

    }

}
