<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\utility\SondaggiUtility;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */

$this->params['breadcrumbs'][] = $this->params['titleSection'];

if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-index">
    <?php echo $this->render('_search', ['model' => $model]);   ?>

    <p>
        <?php /* echo         Html::a(AmosSondaggi::t('amossondaggi', 'Nuovo {modelClass}', [
          'modelClass' => 'Sondaggi',
          ])        , ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>

    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                'filemanager_mediafile_id' => [
                    'label' => AmosSondaggi::t('amossondaggi', 'Immagine'),
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var SondaggiSearch $model */
                        $url       = '/img/img_default.jpg';
                        if ($model->file) {
                            $url = $model->file->getUrl('table_small');
                        }
                        return Html::img($url,
                                [
                                'class' => 'gridview-image'
                        ]);
                    }
                ],
                'titolo:ntext',
                [
                    'attribute' => 'status',
                    'value' => function($model) {
                        $statusLabel = '--';
                        if ($model->hasWorkflowStatus()) {
                            if ($model->getWorkflowStatus()->id == Sondaggi::WORKFLOW_STATUS_VALIDATO && SondaggiUtility::isTerminated($model)) {
                                $statusLabel = AmosSondaggi::t('amossondaggi', 'Concluso');
                            } else {
                                $statusLabel = AmosSondaggi::t('amossondaggi', $model->getWorkflowStatus()->getLabel());
                            }
                        }
                        return $statusLabel;
                    }
                ],
                [
                    'label' => AmosSondaggi::t('amossondaggi', 'Data di pubblicazione'),
                    'attribute' => 'publish_date',
                    'value' => function($model) {
                        if ($model->publish_date) {
                            return \Yii::$app->formatter->asDate($model->publish_date);
                        }
                        return '';
                    }
                ],
                [
                    'label' => AmosSondaggi::t('amossondaggi', 'Data di chiusura'),
                    'attribute' => 'close_date',
                    'value' => function($model) {
                        if ($model->close_date) {
                            return \Yii::$app->formatter->asDate($model->close_date);
                        }
                        return '';
                    }
                ],
                 'compilazioni' => [
                     'label' => AmosSondaggi::t('amossondaggi', 'Partecipanti'),
                     'value' => function ($model) {
                         if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) {
                             /** @var SondaggiSearch $model */
                             return $model->getNumeroPartecipazioni();
                         }
                         return '';
                     }
                 ],
                'contesto' => [
                    'label' => AmosSondaggi::t('amossondaggi', 'Contesto'),
                    'value' => function ($model) {
                        /** @var Sondaggi $model */
                        if ($model->isCommunitySurvey()) {
                            /** @var Community $community */
                            $community = Community::findOne($model->community_id);
                            if ($community) {
                                return AmosSondaggi::t('amossondaggi', 'Community') . ' ' . $community->name;
                            }
                        }
                        return AmosSondaggi::t('amossondaggi', 'Piattaforma');
                    },
                ],
                //['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            'created_by',
//            'updated_by',
//            'deleted_by',
//            'version',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{anteprima}{compila}{update}{delete}',
                    'buttons' => [
                        'compila' => function ($url, $model) {
                            /** @var SondaggiSearch $model */
                            $url = \yii\helpers\Url::current();
                            //if (\Yii::$app->getUser()->can('PARTECIPANTE') || TRUE) {
                            if ($model->isCompilable() && \Yii::$app->user->can('COMPILA_SONDAGGIO', ['model' => $model])) {
                                return Html::a(AmosIcons::show('spellcheck'),
                                        Yii::$app->urlManager->createUrl([
                                            '/'.$this->context->module->id.'/pubblicazione/compila',
                                            'id' => $model->id,
                                            'url' => $url
                                        ]),
                                        [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Compila sondaggio'),
                                        'class' => 'btn btn-tool-secondary'
                                ]);
                            } else {
                                return '';
                            }
                        }
                    ]
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_item'
        ],
        'iconView' => [
          'itemView' => '_icon'
        ],
        /*
          'mapView' => [
          'itemView' => '_map',
          'markerConfig' => [
          'lat' => 'domicilio_lat',
          'lng' => 'domicilio_lon',
          ]
          ],
          'calendarView' => [
          'itemView' => '_calendar',
          'clientOptions' => [
          //'lang'=> 'de'
          ],
          'eventConfig' => [
          //'title' => 'titoloEvento',
          //'start' => 'data_inizio',
          //'end' => 'data_fine',
          //'color' => 'coloreEvento',
          //'url' => 'urlEvento'
          ],
          ] */
    ]);
    ?>
</div>
