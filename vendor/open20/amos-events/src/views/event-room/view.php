<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-room
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\EventRoom $model
 */

$this->title = $model->room_name;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="event-room-view col-xs-12 m-t-5">
    <div class="row">
        <div class="col-xs-12 m-b-5">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'room_name',
                    'available_seats'
                ],
            ]) ?>
        </div>
    </div>
    <div class="btnViewContainer pull-right">
        <?= Html::a(AmosEvents::t('amosevents', 'Close'), Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey()), ['class' => 'btn btn-secondary']); ?>
    </div>
</div>
