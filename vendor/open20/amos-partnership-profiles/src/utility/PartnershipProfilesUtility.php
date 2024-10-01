<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\utility
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\community\AmosCommunity;
use open20\amos\community\exceptions\CommunityException;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\EmailUtil;
use open20\amos\core\user\User;
use open20\amos\partnershipprofiles\exceptions\PartnershipProfilesException;
use open20\amos\partnershipprofiles\models\DevelopmentStage;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\models\search\PartnershipProfilesSearch;
use open20\amos\partnershipprofiles\Module;
use open20\amos\tag\models\EntitysTagsMm;
use yii\base\InvalidConfigException;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\log\Logger;

/**
 * Class PartnershipProfilesUtility
 * @package open20\amos\partnershipprofiles\utility
 */
class PartnershipProfilesUtility extends BaseObject
{
    /**
     * @var string $dbDateFormat
     */
    static $dbDateFormat = 'Y-m-d';

    /**
     * This method get the last visited url for the expressions of interest actions.
     * @param string $default
     * @return string
     */
    public static function getActionsRedirectLink($default = '')
    {
        $url = $default;
        if (is_null(\Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles()))) {
            return $url;
        }

        // Session saved date times
        $beginCreateNewSessionKeyDateTimeStr = \Yii::$app->session->get(Module::beginCreateNewSessionKeyExprOfIntDateTime());
        if (!$beginCreateNewSessionKeyDateTimeStr) {
            /**
             * This means that the user is never entered in the api request list. Then the external form link is used directly.
             */
            return self::getPartnershipProfilesLink();
        }
        $partnershipProfilesSessionKeyDateTimeStr = \Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfilesDateTime());

        // Creating DateTime objects
        $beginCreateNewSessionKeyDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $beginCreateNewSessionKeyDateTimeStr);
        $partnershipProfilesSessionKeyDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $partnershipProfilesSessionKeyDateTimeStr);

        // Retrieve objects timestamps
        $beginCreateNewSessionKeyDateTimeTimeStamp = $beginCreateNewSessionKeyDateTime->getTimestamp();
        $partnershipProfilesSessionKeyDateTimeTimeStamp = $partnershipProfilesSessionKeyDateTime->getTimestamp();

        /**
         * If the timestamp of the list link is older than the external form link,
         * the external form link is the last visited and it is the link to be used.
         */
        if ($beginCreateNewSessionKeyDateTimeTimeStamp < $partnershipProfilesSessionKeyDateTimeTimeStamp) {
            // This means that the links saved in session from the list was saved before the link set in session by the certificate list widget.
            // Then, the link for the certificate list widget is the last visited and the redirect link.
            $url = self::getPartnershipProfilesLink();
        }
        return $url;
    }

    /**
     * Get the external form link saved in session.
     * @return string
     */
    public static function getPartnershipProfilesLink()
    {
        $redirectLink = \Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles());
        return $redirectLink;
    }

    /**
     * Calculate expiry date and return the formatted date string.
     * @param PartnershipProfiles $model
     * @param bool $formatted
     * @return string
     * @throws PartnershipProfilesException
     * @throws \yii\base\InvalidConfigException
     */
    public static function calcExpiryDateStr($model, $formatted = false)
    {
        $partnershipProfileDate = self::calcExpiryDate($model);
        $retValDateTime = '';
        if (!is_null($partnershipProfileDate)) {
            $retValDateTime = $partnershipProfileDate->format(self::$dbDateFormat);
            if ($formatted) {
                $retValDateTime = \Yii::$app->getFormatter()->asDate($retValDateTime);
            }
        }
        return $retValDateTime;
    }

    /**
     * Calculate expiry date and return the DateTime object.
     * @param PartnershipProfiles $model
     * @return bool|\DateTime|null
     * @throws PartnershipProfilesException
     */
    public static function calcExpiryDate($model)
    {
        if (is_null($model) || !$model->id) {
            throw new PartnershipProfilesException('calcPartnershipProfileExpiryDate: missing model');
        }
        if (!$model->partnership_profile_date || !$model->expiration_in_months) {
            return null;
        }
        $partnershipProfileDate = \DateTime::createFromFormat(self::$dbDateFormat, $model->partnership_profile_date);
        $interval = 'P' . $model->expiration_in_months . 'M';
        $partnershipProfileDate->add(new \DateInterval($interval));
        return $partnershipProfileDate;
    }

    /**
     * Calculate expiry date without object, only with params, and return an array with the formatted date.
     * @param string $partnershipProfileDate
     * @param string $expirationInMonths
     * @return array
     */
    public static function calcExpiryDateWithParams($partnershipProfileDate, $expirationInMonths)
    {
        $retval = [];
        if ($partnershipProfileDate && $expirationInMonths) {
            $dbDateFormat = 'Y-m-d';
            $date = \DateTime::createFromFormat($dbDateFormat, $partnershipProfileDate);
            if (!is_null($date) && !is_null($expirationInMonths) && is_numeric($expirationInMonths)) {
                $interval = 'P' . $expirationInMonths . 'M';
                $date->add(new \DateInterval($interval));
                $retValDate = $date->format($dbDateFormat);
                try {
                    $retval['dateTimeToView'] = \Yii::$app->formatter->asDate($retValDate);
                } catch (InvalidConfigException $exception) {
                    $retval = [];
                }
            }
        }
        return $retval;
    }

    /**
     * @param PartnershipProfiles $model
     * @param int[] $selectedUserIds
     * @return bool
     */
    public static function createProjectGroupCommunity($model, $selectedUserIds)
    {
        /** @var AmosCommunity $communityModule */
        $communityModule = \Yii::$app->getModule('community');
        $title = $model->title;
        $type = CommunityType::COMMUNITY_TYPE_CLOSED;
        $context = Community::className();
        $managerRole = CommunityUserMm::ROLE_COMMUNITY_MANAGER;
        $description = $model->short_description;
        $managerStatus = CommunityUserMm::STATUS_ACTIVE;
        try {
            $model->community_id = $communityModule->createCommunity($title, $type, $context, $managerRole, $description, $model, $managerStatus);
            $ok = $model->save(false);
            if (!is_null($model->community_id)) {
                $ok = self::duplicatePartnershipProfilesTagForCommunity($model);
            }
        } catch (CommunityException $exception) {
            \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
            $ok = false;
        }
        if ($ok) {
            $ok = self::addCommunityParticipants($model->community_id, $selectedUserIds);
        }
        return $ok;
    }

    /**
     * Duplicate the partnership profile tags for the related community
     * @param PartnershipProfiles $model
     * @return bool
     */
    public static function duplicatePartnershipProfilesTagForCommunity($model)
    {
        $eventTags = EntitysTagsMm::findAll([
            'classname' => Module::instance()->model('PartnershipProfiles'),
            'record_id' => $model->id
        ]);
        $ok = true;
        foreach ($eventTags as $eventTag) {
            $entityTag = new EntitysTagsMm();
            $entityTag->classname = Community::className();
            $entityTag->record_id = $model->community_id;
            $entityTag->tag_id = $eventTag->tag_id;
            $entityTag->root_id = $eventTag->root_id;
            $ok = $entityTag->save(false);
            if (!$ok) {
                break;
            }
        }
        return $ok;
    }

    /**
     * @param int $communityId
     * @param int[] $selectedUserIds
     * @return bool
     */
    public static function addCommunityParticipants($communityId, $selectedUserIds)
    {
        $allOk = true;
        foreach ($selectedUserIds as $userId) {
            $communityUserMm = new CommunityUserMm();
            $communityUserMm->community_id = $communityId;
            $communityUserMm->user_id = $userId;
            $communityUserMm->status = CommunityUserMm::STATUS_INVITE_IN_PROGRESS;
            $communityUserMm->role = CommunityUserMm::ROLE_PARTICIPANT;
            $ok = $communityUserMm->save(false);
            if (!$ok) {
                $allOk = false;
                break;
            } else {
                /** @var User $userToInvite */
                $userToInvite = User::findOne($userId);
                /** @var UserProfile $userToInviteProfile */
                $userToInviteProfile = $userToInvite->getProfile();
                $community = Community::findOne($communityId);
                /** @var User $loggedUser */
                $loggedUser = \Yii::$app->getUser()->identity;
                $emailUtil = new EmailUtil(EmailUtil::INVITATION, $communityUserMm->role, $community, $userToInviteProfile->nomeCognome, $loggedUser->userProfile->getNomeCognome());
                $subject = $emailUtil->getSubject();
                $text = $emailUtil->getText();
                $toEmails = [$userToInvite->email];
                $ok = PartnershipProfilesEmailUtility::sendMail(null, $toEmails, $subject, $text, [], []);
                if ($ok) {
                    $communityUserMm->status = CommunityUserMm::STATUS_WAITING_OK_USER;
                    $ok = $communityUserMm->save(false);
                    if (!$ok) {
                        $allOk = false;
                        break;
                    }
                } else {
                    $allOk = false;
                    break;
                }
            }
        }
        return $allOk;
    }

    /**
     * This method find all logged user own interests partnership profiles.
     * The method returns an array of PartnershipProfiles objects or an array of ids.
     * @param bool $onlyIds
     * @return PartnershipProfiles[]
     */
    public static function getOwnInterestPartnershipProfiles($onlyIds = false)
    {
        /** @var PartnershipProfilesSearch $partnershipProfileSearch */
        $partnershipProfileSearch = Module::instance()->createModel('PartnershipProfilesSearch');
        $query = $partnershipProfileSearch->searchQuery(\Yii::$app->request->getQueryParams());
        $ownInterestPartnershipProfiles = $query->all();

        if (!$onlyIds) {
            return $ownInterestPartnershipProfiles;
        }

        $partnershipProfileIds = [];
        foreach ($ownInterestPartnershipProfiles as $ownInterestPartnershipProfile) {
            $partnershipProfileIds[] = $ownInterestPartnershipProfile->id;
        }

        return $partnershipProfileIds;
    }

    /**
     * @param PartnershipProfiles $model
     * @return bool
     */
    public static function canView($model)
    {
        $loggedUserId = \Yii::$app->user->id;

        // Check if logged user is the partnership profile creator
        if ($model->created_by == $loggedUserId) {
            return true;
        }

        // Check if logged user is the partnership profile facilitator
        if (!is_null($model->partnershipProfileFacilitator) && ($model->partnershipProfileFacilitator->user_id == $loggedUserId)) {
            return true;
        }

        // Check if logged user have role "PARTNERSHIP_PROFILES_VALIDATOR"
        if (\Yii::$app->user->can('PARTNERSHIP_PROFILES_VALIDATOR', ['model' => $model])) {
            return true;
        }

        if (!(
            ($model->status == PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT) ||
            ($model->status == PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE))
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed|null
     */
    public static function getDevelopmentStageListReadyForSelect()
    {
        return ArrayHelper::map(DevelopmentStage::find()->orderBy(['priority' => SORT_ASC])->all(),'id', 'value');
    }
}
