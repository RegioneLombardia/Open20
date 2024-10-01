<?php
use open20\gamification\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use kartik\export\ExportMenu;
use open20\amos\core\icons\AmosIcons;
use open20\gamification\models\PunteggiTotUtenti;

$punteggiTotDataAgg = PunteggiTotUtenti::findOne(['gamification_id' => $model->id]);
if (!is_null($punteggiTotDataAgg)){
    $dataOra = DateTime::createFromFormat('Y-m-d H:i:s', $punteggiTotDataAgg->updated_at);
    $formattedDate = $dataOra->format('d/m/Y H:i:s');
} 


$js = <<<JS

if($( ".alert-success" ).html()){
    $( ".gamification-current-scores .ui-accordion" ).trigger( "click" );
}
  

JS;

$this->registerJs($js);
?>
<div class="row">
    <div class="col-xs-12">
        <form id="ricalcola-section" action="/gamification/gamification/ricalcola" method="get">
            <div class="gamification-actual-scores-cta">

                <div>
                    <input type="hidden" name="gamif_id" value="<?= Html::encode($model->id) ?>"> 
                    <?php if(($punteggiTotDataAgg)): ?>      
                        <p class="m-b-0"><?=Module::t('gamification', 'I risultati sono aggiornati al ')?><?= $formattedDate;?> </p> 
                        <p class="m-b-0"><?=Module::t('gamification', 'Premi il pulsante "RICALCOLA" per aggiornare la classifica ')?></p> 
                    <?php else: ?>
                        <p class="m-b-0"><?=Module::t('gamification', 'Non sono ancora stati calcolati i punteggi. Premi il pulsante “RICALCOLA” per creare la classifica parziale.')?></p>
                    <?php endif; ?>
                </div>
                
                <div class="pull-right">
                    <?= Html::submitButton(Module::t('gamification', 'Ricalcola'), ['class' => 'btn btn-navigation-primary', 'id' => 'ricalcola_button']) ?>

                    <?php
                        $gridColumns = [
                            [
                                'attribute' => 'posizione',
                                'label' => 'Posizione',
                            ],
                            [
                                'attribute' => 'nome_cognome',
                                'label' => 'Nome e Cognome',
                            ],
                            [
                                'attribute' => 'totale',
                                'label' => 'Totale',
                            ],
                            [
                                'attribute' => 'profilo',
                                'label' => 'Profilo',
                            ],
                            [
                                'attribute' => 'community',
                                'label' => 'Community',
                            ],
                            [
                                'attribute' => 'notizie',
                                'label' => 'Notizie',
                            ],
                            [
                                'attribute' => 'documenti',
                                'label' => 'Documenti',
                            ],
                            [
                                'attribute' => 'discussioni',
                                'label' => 'Discussioni',
                            ],
                            [
                                'attribute' => 'sondaggi',
                                'label' => 'Sondaggi',
                            ],
                        ];

                        echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $gridColumns,
                            'showColumnSelector' => false,
                            'showConfirmAlert' => false,
                            'filename' => Yii::$app->view->title,
                            'clearBuffers' => true,
                            'exportConfig' => [
                                \open20\amos\core\forms\editors\ExportMenu::FORMAT_HTML => false,
                                ExportMenu::FORMAT_CSV => false,
                                ExportMenu::FORMAT_TEXT => false,
                                ExportMenu::FORMAT_PDF => false
                            ],
                            'dropdownOptions' => [
                                'class' => 'btn btn-tools-secondary',
                                'icon' => AmosIcons::show('download')
                            ],
                        ]);
                    ?>
                </div>    
            </div>

                
        </form>

        <?=
            DataProviderView::widget([
                'dataProvider' => $dataProvider,
                'currentView' => $currentView,
                'gridView' => [
                    'columns' => [
                        [
                            'attribute' => 'posizione',
                            'label' => 'Posizione',
                        ],
                        [
                            'attribute' => 'nome_cognome',
                            'label' => 'Nome e Cognome',
                        ],
                        [
                            'attribute' => 'totale',
                            'label' => 'Totale',
                        ],
                        [
                            'attribute' => 'profilo',
                            'label' => 'Profilo',
                        ],
                        [
                            'attribute' => 'community',
                            'label' => 'Community',
                        ],
                        [
                            'attribute' => 'notizie',
                            'label' => 'Notizie',
                        ],
                        [
                            'attribute' => 'documenti',
                            'label' => 'Documenti',
                        ],
                        [
                            'attribute' => 'discussioni',
                            'label' => 'Discussioni',
                        ],
                        [
                            'attribute' => 'sondaggi',
                            'label' => 'Sondaggi',
                        ],
                        [
                            'class' => 'open20\amos\core\views\grid\ActionColumn',
                            'template' => '{download}',
                            'buttons' => [
                                'download' => function($url, $model){
                                    return Html::a(
                                        AmosIcons::show('download'),
                                        [
                                            '/gamification/gamification/detail-user-export',
                                            'id' => $model->user_id,
                                            'gamification' => Yii::$app->request->get('id')
                                        ],
                                        [
                                            'class' => 'btn btn-tools-secondary',
                                            'title' => Yii::t('gamification', 'Download'),
                                        ]
                                    );
                                },
                            ],
                        ],
                    ],
                    'enableExport' => true,
                ],
                'exportConfig' => [
                    'exportEnabled' => true,
                ],
            ]);
        ?>
    </div>
</div>