<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontendmodules\collaborations\events
 * @category   CategoryName
 */

namespace open20\amos\collaborations\events;

use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\models\CollaborationProposalsExpressionsOfInterest;
use open20\amos\collaborations\Module;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use raoul2000\workflow\events\WorkflowEvent;
use Yii;
use yii\base\BaseObject;

/**
 * @package frontendmodules\collaborations\events
 */
class CollaborationProposalsEvents extends BaseObject
{
    /**
     * Invia le email di richiesta di validazione agli utenti con ruolo ADMIN e
     * SUPERUSER
     * @param WorkflowEvent $event Evento per cui inviare la richiesta di
     * validazione
     */
    public static function sendToValidateNotificationEmails($event)
    {
        $asset = $event->data;
        $from  = Yii::$app->params['supportEmail'];

        $soggetto = Yii::t('amoscollaborations', '#event_validate_asset', [
            'title' => $asset->title,
        ]);

        $admins = array_unique(\yii\helpers\ArrayHelper::merge(
            Yii::$app->authManager->getUserIdsByRole('ADMIN'),
            Yii::$app->authManager->getUserIdsByRole('SUPERUSER')
        ));

        $nome = Yii::$app->user->identity->userProfile->getNomeCognome();
        foreach ($admins as $userId) {
            $user = User::findOne($userId);
            if ($user->userProfile->attivo == 1) {
                $to   = $user->email;
                $email = new Email();
                $testo = Yii::t('amoscollaborations', '#email_body_validate_request', [
                    'nome' => $nome,
                    'url' => Yii::$app->params['platform']['frontendUrl']
                        . '/'
                        . 'collaborations/collaboration-proposals/update?id='
                        . $asset->id,
                    'title' => $asset->title,
                ]);

                $email->sendMail($from, $to, $soggetto, $testo, []);
            }
        }

        if (!empty($admins)) {
            Yii::$app->getSession()->addFlash('success',
                Yii::t('amoscollaborations', '#request_to_validate_success'));
        }
    }


     /**
     * Invia le email di proposta rifiutata agli utenti 
     * @param WorkflowEvent $event Evento per cui inviare la notifica di rifiuto
     */
    public static function sendRefusedNotificationEmails($event)
    {
        $asset = $event->data;
        $from  = Yii::$app->params['supportEmail'];

        $soggetto = Yii::t('amoscollaborations', '#event_validate_asset', [
            'title' => $asset->title,
        ]);

        $nome = Yii::$app->user->identity->userProfile->getNomeCognome();

        $to = $asset->createdUserProfile->user->email;

        $email = new Email();
        
        $testo = Yii::t('amoscollaborations', '#email_body_refused', [
            'nome' => $nome,
            'url' => Yii::$app->params['platform']['frontendUrl']
                    . '/'
                    . 'collaborations/collaboration-proposals/update?id='
                    . $asset->id,
            'title' => $asset->title,
            ]);

                $email->sendMail($from, $to, $soggetto, $testo, []);
    }
}