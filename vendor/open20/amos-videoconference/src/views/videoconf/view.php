<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\videoconference\models\Videoconf;

/**
 * @var yii\web\View $this
 * @var open20\amos\videoconference\models\Videoconf $model
 */
$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Videoconferenza'), 'url' => ['index']];
$this->params['breadcrumbs'] []= '';
?>
<div class="videoconf-view col-xs-12">
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description:html',
            [
                'label' => 'Partecipanti',
                'value' => function ($model) {
                    return count($model->videoconfUsersParticipants);
                }
            ],
            [
                'attribute' => 'begin_date_hour',
                'format' => [
                    'date',
                    (isset(Yii::$app->modules['datecontrol']->displaySettings['datetime'])) ? Yii::$app->modules['datecontrol']->displaySettings['datetime'] : 'php:d-m-Y H:i'
                ],
                'visible' => ($model->status == Videoconf::STATUS_FUTURE),
            ],
            [
                'attribute' => 'end_date_hour',
                'format' => ['date', (isset(Yii::$app->modules['datecontrol']->displaySettings['datetime'])) ? Yii::$app->modules['datecontrol']->displaySettings['datetime'] : 'd-m-Y H:i:s A'],
                'visible' => ($model->status == Videoconf::STATUS_FUTURE),
            ],
            [
                'attribute' => 'notification_before_conference',
                'visible' => ($model->status == Videoconf::STATUS_FUTURE),
            ],
          
            [
                'label' => \Yii::t('app', 'Utenti partecipanti'),
                'format' => 'html',
                'value' => function($model) {
                            $participants = "";
                            foreach ($model->videoconfUsersParticipants as $user) {
                                $participants .= $user->userProfile . "<br>";
                            }
                            return $participants;
                        }
            ],
        ],
    ])
    ?>

    <div class="btnViewContainer pull-right">
<?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
