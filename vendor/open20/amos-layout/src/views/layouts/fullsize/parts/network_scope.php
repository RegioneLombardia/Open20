<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\fullsize\parts
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\interfaces\OrganizationsModelInterface;
use open20\amos\core\interfaces\OrganizationsModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\record\Record;
use open20\amos\events\models\Event;

$moduleCwh = Yii::$app->getModule('cwh');
$moduleCommunity = Yii::$app->getModule('community');
$eventsModule = Yii::$app->getModule('events');
$layoutModule = Yii::$app->getModule('layout');

$currentAction = \Yii::$app->controller->module->id . '/' . \Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id;
$hideScopeInAction = !empty(\Yii::$app->params['hideScopeinAction']) ? \Yii::$app->params['hideScopeinAction'] : [];

$scope = null;
if (!empty($moduleCwh)) {
    /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
    $scope = $moduleCwh->getCwhScope();
}
if (!empty($scope)) {
    if (isset($scope['community'])) {
        $communityId = $scope['community'];
        $community = \open20\amos\community\models\Community::findOne($communityId);
    }
}
$controller = Yii::$app->controller;
$isActionUpdate = ($controller->action->id == 'update');
$confirm = $isActionUpdate ? [
    'confirm' => \open20\amos\core\module\BaseAmosModule::t('amoscore', '#confirm_exit_without_saving')
] : null;

$model = null;

/*
 * Commentato per non mostrare la fascia relativa alla community anche quando si arriva da un plugin dalla dashboard.
 */
if ($controller->hasProperty('model')) {
    $model = $controller->model;
    if ($model->hasProperty('community_id')) {
        $communityId = $model->community_id;
        $community = \open20\amos\community\models\Community::findOne($communityId);
    }
}

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();
/** @var AmosModule|OrganizationsModuleInterface $organizzazioniModule */
$organizzazioniModule = Yii::$app->getModule($adminModule->getOrganizationModuleName());

if (isset($community)) {
    if (!in_array($currentAction, $hideScopeInAction)) {
        $viewParams = [
            'community' => $community,
            'model' => $model,
            'confirm' => $confirm
        ];

        //TODO check why without register this js the confirmation dialog on delete action (context menu widget) does not make any confirmation popup.
        \yii\web\YiiAsset::register($this);

        if ($community->context == \open20\amos\community\models\Community::className()) {
            $viewScope = 'community_network_scope';
            $viewParams['isLayoutInScope'] = $isLayoutInScope;
            echo $this->render($viewScope, $viewParams);
        } else if (!is_null($organizzazioniModule) && ($community->context == $organizzazioniModule->getOrganizationModelClass())) {
            $viewScope = 'organizzazioni_network_scope';

            /** @var Record|OrganizationsModelInterface $organizationModel */
            $organizationModel = $organizzazioniModule->createModel($organizzazioniModule->getOrganizationModelClass());

            /** @var Record|OrganizationsModelInterface $organization */
            $organization = $organizationModel::findOne(['community_id' => $community->id]);

            $viewParams['organizzazioniModule'] = $organizzazioniModule;
            $viewParams['organization'] = (!is_null($organization) ? $organization : null);

            echo $this->render($viewScope, $viewParams);
        } else {
            if (!is_null($eventsModule) && ($community->context == $eventsModule->model('Event'))) {
                /** @var Event $eventModel */
                $eventModel = $eventsModule->createModel('Event');
                $event = $eventModel::findOne(['community_id' => $community->id]);
                $viewParams['model'] = $event;
                if ($eventsModule->hasProperty('enableNewWizard') && $eventsModule->enableNewWizard) {
                    if (\Yii::$app->controller->module->id != 'events') {
                        echo $this->render('events_network_scope_wizard', $viewParams);
                    }
                } else {
                    echo $this->render('events_network_scope', $viewParams);
                }
            } else if (!is_null(Yii::$app->getModule('challenge')) && $community->context == \amos\challenge\models\ChallengeTeam::className()) {
                $viewScope = 'challenge_network_scope';
                echo $this->render($viewScope, $viewParams);
            } else {
                if (!in_array($community->context, $layoutModule->excludeNetworkView)) {
                    $viewScope = 'community_network_scope';
                    echo $this->render($viewScope, $viewParams);
                }
            }
        }
    }
}
