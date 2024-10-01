<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\controllers\console
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\console;

use open20\amos\core\user\User;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesEmailUtility;
use raoul2000\workflow\base\WorkflowException;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class PartnershipProfilesController
 * @package open20\amos\partnershipprofiles\controllers\console
 */
class PartnershipProfilesController extends Controller
{

    public $save;

    public function options($actionID)
    {
        return ['save'];
    }

    public function optionAliases()
    {
        return ['s' => 'save'];
    }

    public function actionArchivePartnershipProfiles()
    {
        $dateStartScript = date("d/m/Y H:i:s");

        switch (strtoupper($this->save)){
            case 'TRUE':
                $this->save = true;
                break;
            case 'FALSE':
                $this->save = false;
                break;
            default:
                $this->save = true;
        }

        $str = Module::t('amospartnershipprofiles', 'START ARCHIVE PARTNESHIP PROFILES');
        $this->stdout("\n$str\n", Console::BOLD);
        $partnershipProfiles = PartnershipProfiles::find()->orderBy('created_at ASC')->all();
        $archivedIds = [];
        $errorIds = [];
        /** @var PartnershipProfiles $partnershipProfile */
        foreach ($partnershipProfiles as $partnershipProfile) {
            if ($this->checkCanBeArchived($partnershipProfile)) {
                $idFormatted = $this->ansiFormat($partnershipProfile->id, Console::FG_YELLOW);
                $str = Module::t('amospartnershipprofiles', 'Id to archive');
                Console::stdout($str . " " . $idFormatted . ": ");
                try {
                    $partnershipProfile->detachBehaviors();
                    $partnershipProfile->status = PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_ARCHIVED;
                    if ($this->save) {
                        $partnershipProfile->save(false);
                    }
                    $archivedIds[] = $partnershipProfile->id;
                    $str = Module::t('amospartnershipprofiles', 'Success');
                    $str = $this->ansiFormat($str, Console::FG_GREEN);
                    Console::stdout("$str\n");
                } catch (Exception $e) {
                    $errorIds[] = $partnershipProfile->id;
                    $str = Module::t('amospartnershipprofiles', 'Failed');
                    $str = $this->ansiFormat($str, Console::FG_RED);
                    Console::stdout("$str" . $e->getMessage() . "\n");
                }
            }
        }
        $str = Module::t('amospartnershipprofiles', 'Number of archieved partnership profiles');
        $str = $this->ansiFormat($str . " " . count($archivedIds), Console::FG_GREEN);
        Console::stdout("$str\n");

        if (count($errorIds) > 0) {
            $str = Module::t('amospartnershipprofiles', 'Number partnership profiles errors');
            $str = $this->ansiFormat($str . " " . count($errorIds), Console::FG_RED);
            Console::stdout("$str\n");
        }
        
        if(count($archivedIds) == 0){
            Console::stdout("nessuna da archiviare\n");
            return;
        }

        $toEmails = $this->getAdministratorEmails();
        $subject = Module::t('amospartnershipprofiles', 'Archived partnership profiles notify');

        $text = $this->renderPartial("@vendor/open20/amos-partnership-profiles/src/views/expressions-of-interest/email/admin_notify_content", [
            'listOfArchived' => $archivedIds,
            'errorIds' => $errorIds,
            'dateStartScript' => $dateStartScript,
        ]);

        $ok = PartnershipProfilesEmailUtility::sendMail(null, $toEmails, $subject, $text, [], []);
//        if( $ok ) {
//            $str = Module::t('amospartnershipprofiles', 'Email correctly sent!');
//            $str = $this->ansiFormat($str, Console::FG_GREEN);
//            Console::stdout("$str\n");
//        } else {
//            $str = Module::t('amospartnershipprofiles', 'Email not sent...');
//            $str = $this->ansiFormat($str, Console::FG_RED);
//            Console::stdout("$str\n");
//        }
        $this->stdout("\nEND ACTION\n", Console::BOLD);
    }

    /**
     * @param PartnershipProfiles $partnershipProfile
     * @return bool
     */
    private function checkCanBeArchived($partnershipProfile)
    {
        $toArchiveAllowedStates = [
            PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED,
            PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED
        ];
        $canBeArchived = (in_array($partnershipProfile->status, $toArchiveAllowedStates));
        // If the states are OK to be archieved, check if is expired... so can be archieved
        if ($canBeArchived) {
            $canBeArchived = $partnershipProfile->isExpired();
        }
        return $canBeArchived;
    }

    /**
     *
     */
    private function getAdministratorEmails()
    {
        $ids = \Yii::$app->authManager->getUserIdsByRole('ADMIN');
        $emails = [];
        foreach ($ids as $userId) {
            $user = User::findOne($userId);
            $emails[] = $user->email;
        }
        return $emails;
    }

}
