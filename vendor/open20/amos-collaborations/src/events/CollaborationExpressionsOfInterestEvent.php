<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\events
 * @category   CategoryName
 */

namespace open20\amos\collaborations\events;

use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\utility\CollaborationProposalsEmailUtility;
use open20\amos\core\user\User;
use yii\base\Event;
use Yii;
use yii\base\BaseObject;

/**
 * Class CollaborationExpressionsOfInterestEvent
 * @package open20\amos\collaborations\events
 */
class CollaborationExpressionsOfInterestEvent extends BaseObject
{
    /**
     * @param Event $yiiEvent
     * @return bool
     */
    public function sendNotifyToCollaborationProposalInterested($model)
    {
        //$expressionOfInterest = $yiiEvent->data;
        $controller = \Yii::$app->controller;
        $auth = \Yii::$app->authManager;
        $staff = array_unique(\yii\helpers\ArrayHelper::merge(
            Yii::$app->authManager->getUserIdsByRole('ADMIN'),
            Yii::$app->authManager->getUserIdsByRole('STAFF_TRAPANI_HUB')
        ));
        $emails = [];
        foreach ($staff as $key => $staffId) {
            $emails[] = User::findOne($staffId)->email;
        }
        // Se la proposta è dalla piattaforma arriva la mail anche al creatore
        if ($model->collaborationProposals->category_id == CollaborationProposals::CATEGORY_FROM_PLATFORM) {
            $proposalCreatorEmail = $model->collaborationProposals->createdUserProfile->user->email;
            $emails[] = $proposalCreatorEmail;
        }
        $toEmails = $emails;
        $cc = [];
        // Se la proposta è dalla piattaforma arriva la mail anche al creatore in cc
        /*if ($model->collaborationProposals->category_id == CollaborationProposals::CATEGORY_FROM_PLATFORM) {
            $proposalCreator = $model->collaborationProposals->createdUserProfile->user->email;
            $cc = [$proposalCreator];
        }*/
        //$bccEmails = [$model->createdUserProfile->user->email];
        $contentView = "@vendor/open20/amos-collaborations/src/views/collaboration-expressions-of-interest/email/creation_content";
        $subject = $controller->renderMailPartial($contentView . "_subject");
        $text = $controller->renderMailPartial($contentView, [
            'expressionOfInterest' => $model
        ]);
        $ok = CollaborationProposalsEmailUtility::sendMail(null, $toEmails, $subject, $text, [], [], [], 0, false, $cc);
        return $ok;
    }
}
