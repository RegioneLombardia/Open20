<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\sondaggi\models\search\SondaggiRispostePredefiniteSearch $searchModel
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Risposte predefinite');
$sondaggio = null;
$domanda = null;
if (isset($parametro)) {
    $domanda = SondaggiDomande::findOne(['id' => $parametro]);
    $sondaggio = $domanda->sondaggi;
    $this->title = AmosSondaggi::t('amossondaggi', 'Risposte predefinite alla domanda: ' . SondaggiDomande::findOne(['id' => $parametro])->domanda);
}
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/index']];
if ($url) {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Domande del sondaggio'), 'url' => $url];
}
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];


?>
<div class="sondaggi-risposte-predefinite-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?php
    if (isset($parametro)) :
        echo DataProviderView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    'risposta:ntext',
                    /* 'sondaggi_domande_id' => [
                      'attribute' => 'sondaggi_domande_id',
                      'value' => function ($model){
                      return $model->getSondaggiDomande()->one()['domanda'];
                      }
                      ], */
                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                $url = \yii\helpers\Url::current();
                                if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIRISPOSTEPREDEFINITE_UPDATE', ['model' => $model])) {
                                    return Html::a(AmosIcons::show('edit'), Yii::$app->urlManager->createUrl([
                                        '/' . $this->context->module->id . '/sondaggi-risposte-predefinite/update',
                                        'id' => $model->id,
                                        'url' => $url,
                                    ]), [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Modifica'),
                                        'class' => 'btn btn-tool-secondary'
                                    ]);
                                } else {
                                    return '';
                                }
                            },
                        ]
                    ],
                ],
            ],
            /* 'listView' => [
              'itemView' => '_item'
              ],
              'iconView' => [
              'itemView' => '_icon'
              ],
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
    else :
        echo DataProviderView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    'risposta:ntext',
                    'sondaggi_domande_id' => [
                        'attribute' => 'sondaggi_domande_id',
                        'value' => function ($model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiRispostePredefiniteSearch $model */
                            return $model->getSondaggiDomande()->one()['domanda'];
                        }
                    ],
                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                $url = \yii\helpers\Url::current();
                                if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIRISPOSTEPREDEFINITE_UPDATE', ['model' => $model])) {
                                    return Html::a(AmosIcons::show('edit'), Yii::$app->urlManager->createUrl([
                                        '/sondaggi/sondaggi-risposte-predefinite/update',
                                        'id' => $model->id,
                                        'url' => $url,
                                    ]), [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Modifica'),
                                        'class' => 'btn btn-tool-secondary'
                                    ]);
                                } else {
                                    return '';
                                }
                            },
                        ]
                    ],
                ],
            ],
        ]);
    endif;
    ?>

</div>

<p>
    <!--    --><?php
    //    if (isset($parametro)) :
    //        echo Html::a(AmosSondaggi::t('amossondaggi', 'Aggiungi risposta predefinita'), ['create', 'idDomanda' => $parametro, 'url' => yii\helpers\Url::current()], ['class' => 'btn btn-success']);
    //    endif;
    //    ?>
</p>
<?php
$model = new \open20\amos\sondaggi\models\SondaggiRispostePredefinite();
$model->sondaggi_domande_id = $parametro;
echo $this->render('_modal_import_risposte', ['model' => $model, 'sondaggi_domande_id' => $parametro]);
ModalUtility::createConfirmModal([
    'id' => 'modalDeleteAll',
    'modalDescriptionText' => AmosSondaggi::t('amossondaggi', '#delete-all-risposte-predefinite-modal-message'),
    'confirmBtnLink' => '/sondaggi/dashboard-risposte-predefinite/delete-all?idDomanda=' . $model->sondaggi_domande_id,
    'cancelBtnLabel' => AmosSondaggi::t('amoscore', 'No'),
    'confirmBtnLabel' => AmosSondaggi::t('amoscore', 'Yes'),
    'confirmBtnOptions' => [
        'class' => 'btn btn-navigation-primary confirm-exit-modal-btn',
    ],
]);

?>
