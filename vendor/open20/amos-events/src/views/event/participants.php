<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\widgets\CommunityMembersWidget;
use yii\widgets\Pjax;

$this->title = \Yii::t('amoscommunity', 'Participants');
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscommunity', 'Community'), 'url' => ['join', 'id' => $model->community_id]];
$this->params['breadcrumbs'][] = $this->title;

$sendTicketsLabel = \open20\amos\events\AmosEvents::txt('Send tickets');

$this->registerJs(<<<JS
    $(document).on("pjax:timeout", function(event) {

    // Prevent default timeout redirection behavior

    event.preventDefault()

});
JS
    , \yii\web\View::POS_LOAD);

Pjax::begin(['timeout' => 10000, 'id' => 'pjax-participants-widget']);

echo \open20\amos\events\widgets\CommunityEventMembersWidget::widget([
    'model' => $model,
    'targetUrlParams' => [
        'viewM2MWidgetGenericSearch' => true,
    ],
    'checkManagerRole' => true,
    'pjaxId' => 'pjax-participants-widget',
    'pageUrl' => '/events/event/participants?communityId=' . $model->community_id,
    'showAdditionalAssociateButton' => $model->has_tickets,
    'enableAdditionalButtons' => true,
]);

Pjax::end();