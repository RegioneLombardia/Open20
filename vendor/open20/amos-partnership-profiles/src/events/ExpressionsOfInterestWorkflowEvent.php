<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\events
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\events;

use open20\amos\core\controllers\CrudController;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesEmailUtility;
use yii\base\Event;
use yii\base\BaseObject;

/**
 * Class ExpressionsOfInterestWorkflowEvent
 * @package open20\amos\partnershipprofiles\events
 */
class ExpressionsOfInterestWorkflowEvent extends BaseObject
{
    /**
     * @param Event $yiiEvent
     * @return bool
     */
    public function sendConfirmToPartnershipProfileCreator(Event $yiiEvent)
    {
        /** @var ExpressionsOfInterest $expressionOfInterest */
        $expressionOfInterest = $yiiEvent->data;
        /** @var CrudController $controller */
        $controller = \Yii::$app->controller;
        $toEmails = [$expressionOfInterest->partnershipProfile->createdUserProfile->user->email];
        $bccEmails = $this->getCCEmails($expressionOfInterest);
        $contentView = "@vendor/open20/amos-partnership-profiles/src/views/expressions-of-interest/email/creation_content";
        $subject = $controller->renderMailPartial($contentView . "_subject");
        $text = $controller->renderMailPartial($contentView, [
            'expressionOfInterest' => $expressionOfInterest
        ]);
        $ok = PartnershipProfilesEmailUtility::sendMail(null, $toEmails, $subject, $text, [], $bccEmails);
        return $ok;
    }

    /**
     * @param ExpressionsOfInterest $expressionOfInterest
     * @return array
     */
    private function getCCEmails($expressionOfInterest)
    {
        $bccEmails = [];
        if (!is_null($expressionOfInterest->partnershipProfile->partnershipProfileFacilitator)) {
            $bccEmails[] = $expressionOfInterest->partnershipProfile->partnershipProfileFacilitator->user->email;
        }
        if (!is_null($expressionOfInterest->createdUserProfile->facilitatore)) {
            $bccEmails[] = $expressionOfInterest->createdUserProfile->facilitatore->user->email;
        }
        return $bccEmails;
    }

    /**
     * @param Event $yiiEvent
     * @return bool
     */
    public function sendNotifyToExpressionsOfInterestCreator(Event $yiiEvent)
    {
        /** @var ExpressionsOfInterest $expressionOfInterest */
        $expressionOfInterest = $yiiEvent->data;
        $toEmails = [$expressionOfInterest->createdUserProfile->user->email];
        $bcc = [$expressionOfInterest->partnershipProfile->createdUserProfile->user->email];
        $subjectPart = '';
        $relevantStr = '';
        switch ($expressionOfInterest->status) {
            case ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE:
                $subjectPart = Module::t('amospartnershipprofiles', '#in_validation_mail');
                $relevantStr = Module::t('amospartnershipprofiles', 'is examining');
                break;
            case ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT:
                $subjectPart = Module::t('amospartnershipprofiles', '#relevant_mail');
                $relevantStr = Module::t('amospartnershipprofiles', 'considers');
                break;
            case ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_REJECTED:
                $subjectPart = Module::t('amospartnershipprofiles', '#rejected_mail');
                $relevantStr = Module::t('amospartnershipprofiles', 'does not consider');
                break;
        }
        /** @var CrudController $controller */
        $controller = \Yii::$app->controller;
        $contentView = "@vendor/open20/amos-partnership-profiles/src/views/expressions-of-interest/email/evaluation_content";
        $subject = $controller->renderMailPartial($contentView . "_subject", [
            'subjectPart' => $subjectPart,
        ]);
        $text = $controller->renderMailPartial($contentView, [
            'expressionOfInterest' => $expressionOfInterest,
            'relevantStr' => $relevantStr,
        ]);
        $ok = PartnershipProfilesEmailUtility::sendMail(null, $toEmails, $subject, $text, [], $bcc);
        return $ok;
    }
}
