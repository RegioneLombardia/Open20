<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\views\my-activities
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;

/**
 * @var yii\web\View $this
 * @var \open20\amos\core\record\Record $model
 */

if (Yii::$app->hasModule(AmosAdmin::getModuleName())) {
    if ($model instanceof \open20\amos\myactivities\basic\WaitingContacts) {
        echo $this->render('_item_waiting_contacts', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('community')) {
    if ($model instanceof \open20\amos\myactivities\basic\RequestToParticipateCommunity) {
        echo $this->render('_item_request_to_partecipate_community', ['model' => $model]);
    }
}

if (Yii::$app->hasModule(AmosAdmin::getModuleName())) {
    if ($model instanceof \open20\amos\myactivities\basic\UserProfileToValidate) {
        echo $this->render('_item_user_profile_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('community')) {
    if ($model instanceof \open20\amos\myactivities\basic\CommunityToValidate) {
        echo $this->render('_item_community_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('organizzazioni')) {
    if ($model instanceof \open20\amos\myactivities\basic\ProfiloToValidate) {
        echo $this->render('_item_organizzazioni_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('news')) {
    if ($model instanceof \open20\amos\myactivities\basic\NewsToValidate) {
        echo $this->render('_item_news_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('discussioni')) {
    if ($model instanceof \open20\amos\myactivities\basic\DiscussionToValidate) {
        echo $this->render('_item_discussion_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('documenti')) {
    if ($model instanceof \open20\amos\myactivities\basic\DocumentToValidate) {
        echo $this->render('_item_document_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('events')) {
    if ($model instanceof \open20\amos\myactivities\basic\EventToValidate) {
        echo $this->render('_item_event_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('sondaggi')) {
    if ($model instanceof \open20\amos\myactivities\basic\SurveyToValidate) {
        echo $this->render('_item_survey_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('showcaseprojects')) {
    if ($model instanceof \open20\amos\myactivities\basic\ShowcaseProjectToValidate) {
        echo $this->render('_item_showcase_project_to_validate', ['model' => $model]);
    }
    if ($model instanceof \open20\amos\myactivities\basic\ShowcaseProjectUserToAccept) {
        echo $this->render('_item_showcase_project_user_to_accept', ['model' => $model]);
    }
    if ($model instanceof \open20\amos\myactivities\basic\ShowcaseProjectProposalToValidate) {
        echo $this->render('_item_showcase_project_proposal_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('results')) {
    if ($model instanceof \open20\amos\myactivities\basic\ResultsProposalToValidate) {
        echo $this->render('_item_proposal_results_to_validate', ['model' => $model]);
    }
    if ($model instanceof \open20\amos\myactivities\basic\ResultsToValidate) {
        echo $this->render('_item_results_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('partnershipprofiles')) {
    if ($model instanceof \open20\amos\myactivities\basic\PartnershipProfileToValidate) {
        echo $this->render('_item_partnership_profile_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('partnershipprofiles')) {
    if ($model instanceof \open20\amos\myactivities\basic\ExpressionOfInterestToEvaluate) {
        echo $this->render('_item_expression_of_interest_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('community')) {
    if ($model instanceof \open20\amos\myactivities\basic\RequestToParticipateCommunityForManager) {
        echo $this->render('_item_request_to_partecipate_community_for_manager', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('report')) {
    if ($model instanceof \open20\amos\myactivities\basic\ReportToRead) {
        echo $this->render('_item_report_to_read', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('organizations')) {
    if ($model instanceof \open20\amos\myactivities\basic\OrganizationsToValidate) {
        echo $this->render('_item_organizations_to_validate', ['model' => $model]);
    }
    if ($model instanceof \open20\amos\myactivities\basic\RequestExternalFacilitator) {
        echo $this->render('_item_request_external_facilitator', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('een')) {
    if ($model instanceof \open20\amos\myactivities\basic\EenExpressionOfInterestToTakeover) {
        echo $this->render('_item_een_expression_of_interest_to_takeover', ['model' => $model]);
    }
}

if (Yii::$app->hasModule(AmosAdmin::getModuleName())) {
    if ($model instanceof \open20\amos\myactivities\basic\UserProfileActivationRequest) {
        echo $this->render('_item_user_profile_activation_request', ['model' => $model]);
    }
}

if (Yii::$app->hasModule(AmosAdmin::getModuleName())) {
    if ($model instanceof \open20\amos\myactivities\basic\ProfileValidationNotifyToRead) {
        echo $this->render('_item_profile_validation_notify_to_read', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('organizzazioni')) {
    if ($model instanceof \open20\amos\myactivities\basic\RequestToJoinOrganizzazioniForReferees) {
        echo $this->render('_item_request_to_join_organizzazioni_for_referees', ['model' => $model]);
    }
    if ($model instanceof \open20\amos\myactivities\basic\RequestToJoinOrganizzazioniSediForReferees) {
        echo $this->render('_item_request_to_join_organizzazioni_sedi_for_referees', ['model' => $model]);
    }
    if ($model instanceof \open20\amos\myactivities\basic\RequestToJoinOrganizzazioniForEmployees) {
        echo $this->render('_item_request_to_join_organizzazioni_for_employees', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('landing')) {
    if ($model instanceof \open20\amos\myactivities\basic\TerritoryToValidate) {
        echo $this->render('_item_territory_to_validate', ['model' => $model]);
    }
}

if (Yii::$app->hasModule('innovativesolutions')) {
    if ($model instanceof \open20\amos\myactivities\basic\InnovativeSolutionToValidate) {
        echo $this->render('_item_innovative_solution_to_validate', ['model' => $model]);
    }
}