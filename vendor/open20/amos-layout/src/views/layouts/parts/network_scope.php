<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */
$moduleCwh       = Yii::$app->getModule('cwh');
$moduleCommunity = Yii::$app->getModule('community');
$eventsModule    = Yii::$app->getModule('events');
$layoutModule    = Yii::$app->getModule('layout');

$scope = null;
if (!empty($moduleCwh)) {
    /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
    $scope = $moduleCwh->getCwhScope();
}
if (!empty($scope)) {
    if (isset($scope['community'])) {
        $communityId = $scope['community'];
        $community   = \open20\amos\community\models\Community::findOne($communityId);
    }
}
$controller     = Yii::$app->controller;
$isActionUpdate = ($controller->action->id == 'update');
$confirm        = $isActionUpdate ? [
    'confirm' => \open20\amos\core\module\BaseAmosModule::t('amoscore', '#confirm_exit_without_saving')
    ] : null;

$model = null;

/*
 * Commentato per non mostrare la fascia relativa alla community anche quando si arriva da un plugin dalla dashboard.
 */
//if ($controller->hasProperty('model')) {
//    $model = $controller->model;
//    if ($model->hasProperty('community_id')) {
//        $communityId = $model->community_id;
//        $community = \open20\amos\community\models\Community::findOne($communityId);
//    }
//}

if (isset($community)) {

    $viewParams = [
        'community' => $community,
        'model' => $model,
        'confirm' => $confirm
    ];

    //TODO check why without register this js the confirmation dialog on delete action (context menu widget) does not make any confirmation popup.
    \yii\web\YiiAsset::register($this);

    if (!is_null($eventsModule) && ($community->context == $eventsModule->model('Event'))) {
        echo $this->render('events_network_scope', $viewParams);
    } else if ($community->context == \open20\amos\community\models\Community::className()) {
        $viewScope = 'community_network_scope';
        echo $this->render($viewScope, $viewParams);
    } else if (!is_null(Yii::$app->getModule('challenge')) && $community->context == \amos\challenge\models\ChallengeTeam::className()) {
        $viewScope = 'community_network_scope';
        echo $this->render($viewScope, $viewParams);
    } else {
        if (!in_array($community->context, $layoutModule->excludeNetworkView)) {
            $viewScope = 'community_network_scope';
            echo $this->render($viewScope, $viewParams);
        }
    }
}