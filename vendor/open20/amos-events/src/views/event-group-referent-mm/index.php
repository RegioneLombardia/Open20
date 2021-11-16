<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/events/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\layout\utility\AmosIconsBi;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventGroupReferentMmSearch $model
 */
$this->title = Yii::t('amoscore', 'Operatori') . ' "' . $model->eventGroupReferent->denominazione . '"';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = $this->title;

$module = \Yii::$app->getModule('events');
$groupReferentAdministration = $module->groupReferentAdministration;
$enableOperatorCandidate = false;
$roleCadidate = '';
if (!empty($module->operatorCandidate['enabled']) && !empty($module->operatorCandidate['role'])) {
    $enableOperatorCandidate = true;
    $roleCadidate = $module->operatorCandidate['role'];
}
?>
<div class="event-group-referent-mm-index">
    <?php $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]);
    ?>

    <?=
    DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'user.userProfile.nome',
                'user.userProfile.cognome',
                'user.email',
                'ruolo' => [
                    'attribute' => 'ruolo',
                    'value' => function ($model)  {
                        return \open20\amos\events\models\EventGroupReferentMm::getUserRoleString($model);
                    },
                ],
                [
                    'value' => function ($model) use ($courseCompletedUserIds) {
                        if (in_array($model->user_id, $courseCompletedUserIds)) {
                            return 'Completato';
                        } else {
                            return 'Incompleto';
                        }
                    },
                    'label' => AmosEvents::t('amosevents', 'Corso di formazione'),
                    'visible' => $enableOperatorCandidate
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{enableOperator}{update}{delete}',
//                    'template' => '{enableOperator}{update}{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) use ($groupReferentAdministration) {
                            if (!empty($groupReferentAdministration['roleAdmin'])
                                && $model->event_group_referent_id == $groupReferentAdministration['id']) {
                                if (\Yii::$app->user->can($groupReferentAdministration['roleAdmin'])) {
                                    $ok = true;
                                } else {
                                    return '';
                                }
                            }
                            $createUrlParams = [
                                '/events/event-group-referent-mm/delete',
                                'id' => $model['id'],
                                'group_id' => filter_input(INPUT_GET, 'group_id'),
                            ];

                            return Html::a(AmosIconsBi::show('ic_delete', 'danger'), \Yii::$app->urlManager->createUrl($createUrlParams), [
                                'class' => "btn btn-xs btn-icon",
                                'data-toggle' => 'tooltip',
                                'title' => AmosEvents::t('amosevents', 'Elimina')], true);
                        },
                        'update' => function ($url, $model) use ($groupReferentAdministration) {
                            if (!empty($groupReferentAdministration['roleAdmin'])
                                && $model->id == $groupReferentAdministration['id']) {
                                if (\Yii::$app->authManager->checkAccess($model->user_id, $groupReferentAdministration['roleAdmin'])) {
                                    $ok = true;
                                }
                                return '';
                            }
                            $profile = \open20\amos\admin\models\UserProfile::find()
                                ->andWhere(['user_id' => $model->user_id])->one();
                            $createUrlParams = [
                                '/admin/user-profile/update',
                                'id' => $profile->id,
                                'group_id' => filter_input(INPUT_GET, 'group_id'),
                            ];

                            return Html::a(AmosIconsBi::show('ic_edit', 'primary'), \Yii::$app->urlManager->createUrl($createUrlParams), [
                                'class' => "btn btn-xs btn-icon",
                                'data-toggle' => 'tooltip',
                                'title' => AmosEvents::t('amosevents', 'Modifica')]);
                        },
                        'enableOperator' => function ($url, $model) use ($courseCompletedUserIds) {
                            $btn = '';
                            if ($model->status != \open20\amos\events\models\EventGroupReferentMm::STATUS_ACTIVE) {
                                if (in_array($model->user_id, $courseCompletedUserIds)) {
                                    $btn = Html::a(AmosIconsBi::show('ic_check', 'primary'),['/events/event-group-referent-mm/assign-operator-role', 'id' => $model->id], [
                                        'class' => "btn btn-xs btn-icon",
                                        'data-toggle' => 'tooltip',
                                        'title' => AmosEvents::t('amosevents', 'Abilita operatore')]);
//                                    $btn = Html::a(AmosIcons::show(
//                                        'check'), ['/events/event-group-referent-mm/assign-operator-role', 'id' => $model->id],
//                                        ['title' => AmosEvents::t('amosevents', 'Abilita operatore'), 'class' => 'btn btn-tools-secondary']
//                                    );
                                }
                                return $btn;
                            }
                        }
                    ],
                ],
            ],
        ],
        /* 'listView' => [
          'itemView' => '_item',
          'masonry' => FALSE,

          // Se masonry settato a TRUE decommentare e settare i parametri seguenti
          // nel CSS settare i seguenti parametri necessari al funzionamento tipo
          // .grid-sizer, .grid-item {width: 50&;}
          // Per i dettagli recarsi sul sito http://masonry.desandro.com

          //'masonrySelector' => '.grid',
          //'masonryOptions' => [
          //    'itemSelector' => '.grid-item',
          //    'columnWidth' => '.grid-sizer',
          //    'percentPosition' => 'true',
          //    'gutter' => '20'
          //]
          ],
          'iconView' => [
          'itemView' => '_icon'
          ],
          'mapView' => [
          'itemView' => '_map',
          'markerConfig' => [
          'lat' => 'domicilio_lat',
          'lng' => 'domicilio_lon',
          'icon' => 'iconMarker',
          ]
          ],
          'calendarView' => [
          'itemView' => '_calendar',
          'clientOptions' => [
          //'lang'=> 'de'
          ],
          'eventConfig' => [
          //'title' => 'titleEvent',
          //'start' => 'data_inizio',
          //'end' => 'data_fine',
          //'color' => 'colorEvent',
          //'url' => 'urlEvent'
          ],
          'array' => false,//se ci sono più eventi legati al singolo record
          //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
          ] */
    ]);
    ?>

</div>
