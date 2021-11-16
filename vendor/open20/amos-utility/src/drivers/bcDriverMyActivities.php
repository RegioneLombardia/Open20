<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers;

use open20\amos\admin\models\UserContact;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\admin\models\base\UserProfile;
use open20\amos\myactivities\basic\WaitingContacts;
use open20\amos\myactivities\basic\NewsToValidate;
use open20\amos\myactivities\basic\CommunityToValidate;
use open20\amos\myactivities\basic\RequestToParticipateCommunity;
use open20\amos\myactivities\basic\RequestToParticipateCommunityForManager;
use open20\amos\myactivities\basic\DiscussionToValidate;
use open20\amos\myactivities\basic\DocumentToValidate;
use open20\amos\myactivities\basic\EenExpressionOfInterestToTakeover;
use open20\amos\myactivities\basic\EventToValidate;
use open20\amos\myactivities\basic\ExpressionOfInterestToEvaluate;
use open20\amos\myactivities\basic\RequestExternalFacilitator;
use open20\amos\myactivities\basic\OrganizationsToValidate;
use open20\amos\myactivities\basic\PartnershipProfileToValidate;
use open20\amos\myactivities\basic\ReportToRead;
use open20\amos\myactivities\basic\RequestToJoinOrganizzazioniForReferees;
use open20\amos\myactivities\basic\RequestToJoinOrganizzazioniSediForReferees;
use open20\amos\myactivities\basic\ResultsProposalToValidate;
use open20\amos\myactivities\basic\ResultsToValidate;
use open20\amos\myactivities\basic\ShowcaseProjectToValidate;
use open20\amos\myactivities\basic\ShowcaseProjectUserToAccept;
use open20\amos\myactivities\basic\UserProfileActivationRequest;
use open20\amos\myactivities\basic\UserProfileToValidate;
use open20\amos\utility\drivers\base\bcDriver;
use open20\amos\myactivities\models\MyActivities;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\utility\models\BulletCounters;
use open20\amos\myactivities\models\search\MyActivitiesModelSearch;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * 
 */
class bcDriverMyActivities extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = MyActivities::className(); // put here your model
        $this->widgetIconNames = [
            'WidgetIconMyActivities' => 'open20\amos\myactivities\widgets\icons\WidgetIconMyActivities',
        ];

        $this->counter = 0;
    }

    /**
     * @inheritdoc
     */
    public function calculateBulletCounters()
    {
        $myActivitiesModule = \Yii::$app->getModule('myactivities');
        $count = \open20\amos\myactivities\models\MyActivities::getCountActivities(true);

        $this->updateBulletCountersTable(
            $this->user_id, 'myactivities', 'open20\amos\myactivities\widgets\icons\WidgetIconMyActivities',
            $count, true
        );
    }
}