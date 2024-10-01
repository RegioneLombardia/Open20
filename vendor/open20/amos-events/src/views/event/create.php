<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventInvitationsUpload;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\Event $model
 * @var int $countEventTypes
 * @var bool $enableAgid
 * @var string|null $fid
 * @var string|null $dataField
 * @var open20\amos\cwh\AmosCwh $moduleCwh
 * @var array $scope
 */

$this->title = AmosEvents::t('amosevents', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

/** @var EventInvitationsUpload $eventInvitationUploadModel */
$eventInvitationUploadModel = AmosEvents::instance()->createModel('EventInvitationsUpload');

$formToRender = ($enableAgid ? '_formAgid' : '_form');

?>
<div class="event-create">
    <?= $this->render($formToRender, [
        'model' => $model,
        'countEventTypes' => $countEventTypes,
        'enableAgid' => $enableAgid,
        'upload' => $eventInvitationUploadModel,
        'fid' => null,
        'dataField' => null,
        'dataEntity' => null,
        'moduleCwh' => $moduleCwh,
        'scope' => $scope
    ]); ?>
</div>
