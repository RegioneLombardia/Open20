<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\Html;
use yii\widgets\Pjax;

$js = <<<JS
  $('#pubblicazione-view').on('click', '.assign-compiler-menu', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#modal_users_list').modal('show');
    $('#modal_users_content').html('');
    $.ajax({
        url: '/sondaggi/pubblicazione/load-users?id='+id,
        type: 'get',
        success: function (data) {
            $('#modal_users_content').html(data);
        }
    });
  });
JS;
$this->registerJs($js);

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */
?>
<div class="sondaggi-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>

    <p>
        <?php /* echo         Html::a(AmosSondaggi::t('amossondaggi', 'Nuovo {modelClass}', [
          'modelClass' => 'Sondaggi',
          ])        , ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>

    <?php
    Pjax::begin(['id'=>'pubblicazione-view']);
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
                        /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                        $mediafile = \pendalf89\filemanager\models\Mediafile::findOne($model->filemanager_mediafile_id);
                        $url       = '/img/img_default.jpg';
                        if ($mediafile) {
                            $url = $model->getAvatarUrl('medium');
                        }
                        return Html::img($url,
                                [
                                'class' => 'gridview-image'
                        ]);
                    }
                ],
                'titolo:ntext',
                'descrizione:ntext',
                'publish_date:date',
                'close_date:date',
                'lastSondaggiRisposteSessioniByEntity.updated_at:date',
                'status' => [
                  'label' => AmosSondaggi::t('amossondaggi', '#compilazioniStatus'),
                  'value' => function($model, $id) {
                    return AmosSondaggi::t('amossondaggi', $model->getSondaggiRisposteSessionisByEntity()->one()->status);
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
                    'template' => '{compila} {visualizza}',
                    'buttons' => [
                        'anteprima' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGI_READ',
                                    ['model' => $model])) {
                                return Html::a(AmosIcons::show('eye'),
                                        Yii::$app->urlManager->createUrl([
                                            '/'.$this->context->module->id.'/sondaggi/dashboard/view',
                                            'id' => $model->id,
                                            'url' => $url,
                                        ]),
                                        [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Visualizza anteprima'),
                                        'class' => 'btn btn-tool-secondary'
                                ]);
                            } else {
                                return '';
                            }
                        },
                        'compila' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                            $url = \yii\helpers\Url::current();
                            //if (\Yii::$app->getUser()->can('PARTECIPANTE') || TRUE) {

                            if (!$model->hasCompilazioniSuperate()) {
                                $compilazioni = $model->getNumeroPartecipazioni(1);
                                $module       = AmosSondaggi::instance();
                                if ($compilazioni > 0 && $module->enableSingleCompilation == true && $module->enableRecompile
                                    == true) {
                                    $stato = $model->getSondaggiRisposteSessionis()->one()->status;

                                    return Html::a(AmosIcons::show('spellcheck'),
                                            Yii::$app->urlManager->createUrl([
                                                '/'.$this->context->module->id.'/pubblicazione/compila',
                                                'id' => $model->id,
                                                'url' => $url
                                            ]),
                                            [
                                            'data-confirm' => (($stato != \open20\amos\sondaggi\models\SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA
                                            && $stato != null) ? AmosSondaggi::t('amossondaggi',
                                                'Attenzione! La ri-compilazione rimetterà il questionario in stato Bozza')
                                                : null),
                                            'title' => AmosSondaggi::t('amossondaggi', 'Ricompila sondaggio'),
                                            'class' => 'btn btn-tool-secondary'
                                    ]);
                                } else {
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
                                }
                            } else {
                                return '';
                            }
                        },
                        'visualizza' => function ($url, $model) {
                          /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                          $url = \yii\helpers\Url::current();
                          //if (\Yii::$app->getUser()->can('PARTECIPANTE') || TRUE) {

                          if (!$model->getNumeroPartecipazioni(1) > 0) {
                                  return Html::a(AmosIcons::show('file'),
                                          Yii::$app->urlManager->createUrl([
                                              '/'.$this->context->module->id.'/pubblicazione/compila',
                                              'id' => $model->id,
                                              'url' => $url,
                                              'read' => true
                                          ]),
                                          [
                                          'title' => AmosSondaggi::t('amossondaggi', '#view_compilation'),
                                          'class' => 'btn btn-tool-secondary'
                                  ]);
                          }
                      }
                    ]
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_item',
            'viewParams' => [
                'hideStatusPoll' => true,
                'hideDateEnd' => true
            ],
        ], /*
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
    Pjax::end();
    ?>
</div>
<?php
echo ModalUtility::amosModal([
    'id' => 'modal_users_list',
    'headerClass' => 'modal-utility-confirm',
    'headerText' => AmosIcons::show('user').AmosSondaggi::t('AmosSondaggi', '#assign_compiler'),
    'modalBodyContent' => '<div id="modal_users_content"></div>',
    'modalClassSize' => 'modal-lg'
]);
?>
