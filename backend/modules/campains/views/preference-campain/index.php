<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/compains/views
 */

use yii\helpers\Url;
use kartik\grid\GridView;
use yii\base\DynamicModel;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\layout\utility\AmosIconsBi;
use open20\amos\core\views\DataProviderView;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\models\PreferenceCampainCopy;
use open20\amos\core\utilities\WorkflowTransitionWidgetUtility;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\campains\models\search\PreferenceCampainSearch $model
 */

$this->title = Yii::t('amoscore', 'Campagne');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="preference-campain-index">

    <?=$this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]);?>

    <?= GridView::widget([
        'id' => 'campains-list-id',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        'columns' => [
            [
                'class' => '\kartik\grid\ExpandRowColumn',
                'header' => '',
                'width' => '30px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                // show row expanded for even numbered keys
                'detailUrl' => Url::to(['list-campain-detail']),
                'value' => function ($model, $key, $index) {
                    return GridView::ROW_COLLAPSED;
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'] ,
                'expandOneOnly' => true
            ],
            'titolo',
            [
                'attribute' => 'Creato da',
                'value' =>function ($model, $key, $index, $widget) { 
                    return $model->createdUserProfile;
                },
            ],
            [
                'attribute' => 'Struttura',
                'value' =>function ($model, $key, $index, $widget) { 
                    return $model->structure->name;
                },
            ],
            [
                'attribute' => 'Data creazione',
                'value' =>function ($model, $key, $index, $widget) { 
                    return Yii::$app->formatter->asDatetime($model->created_at);
                },
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete} {view} {copy_campain}',
                'header' => '',
                'width' => '140px',
                'dropdownOptions' => ['class' => 'float-middle'],
                // 'urlCreator' => function($action, $model, $key, $index) { return '#'; },
                'buttons' => [
                    'update' => function ($url, $model, $key)  {
                         if ($model->status!=PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_SENT) {
                            return  Html::a(AmosIconsBi::show('ic_edit'), ['update-recipients', 'id' => $model->id], ['class' => 'btn btn-xs btn-icon', 'data-toggle'=>'tooltip', 'title'=>'Modifica']);
                         } else {
                            return '';
                         }
                         
                    },
                    'view' => function ($url, $model, $key)  {
                        if ($model->status==PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_SENT) {
                           return  Html::a(AmosIconsBi::show('ic_visibility'), ['update-sending-tracking', 'id' => $model->id], ['class' => 'btn btn-xs btn-icon', 'data-toggle'=>'tooltip', 
                           'title'=>'Visualizza']);
                        } else {
                           return '';
                        }
                   },

                   'copy_campain' => function ($url, $model, $key)  {
                    
                        return Html::button(AmosIconsBi::show('ic_content_copy'),
                            [
                                'class' => 'btn btn-xs btn-icon btn-copy-campain',
                                'data-toggle' => "modal",
                                'data-target' => "#modal_copy_campain-" . $model->id,
                                'data-preference-campain-id' => $model->id,
                                'title' => "Copia"
                            ]
                        );
                    },

                    'delete' => function ($url, $model, $key)  {
                        if (Yii::$app->user->can('PREFERENCECAMPAIN_DELETE', ['model' => $model])) {
                            return  Html::a(AmosIconsBi::show('ic_close', 'danger')
                                , ['delete', 'id' => $model->id]
                                , [
                                    'class' => 'btn btn-xs btn-icon',
                                    'data-toggle'=>'tooltip',
                                    'title'=>'Elimina',
                                    'data-confirm'=>'Sei sicuro di voler eliminare la campagna?',
                                ]);
                        } else {
                            return '';
                        }
                         
                    },
                    
                ],
               // 'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],

            [
                'attribute' => 'status',
                'header' => 'Stato',
                'value' =>function ($model, $key, $index, $widget) { 
                    // echo $model::classname(); die;
                    return WorkflowTransitionWidgetUtility::getLabelStatus($model);
                },
            ],

        ], // check the configuration for grid columns by clicking button above
        'pjax' => true, // pjax is set to always true for this demo

        'responsive'=>true,
        'hover'=>true,
        
    ]);
    ?>
</div>


<?php foreach ($dataProvider->getModels() as $key => $campain) : ?>

    <!-- START Modal COPY CAMPAIN  -->
    <div class="modal fade" tabindex="-1" role="dialog" id="<?= 'modal_copy_campain-' . $campain->id ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Copia Campagna - <?= $campain->titolo ?></h5>

                    <?= Html::button(
                        '<svg class="icon">
                            <use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_close"></use>
                        </svg>',
                        [
                            'class' => 'close',
                            'data-dismiss' => "modal",
                            'aria-label' => "Close"
                        ])
                    ?>
                </div>

                <!-- FORM SECTION -->
                <?php 
                    $form = ActiveForm::begin([
                        'options' => ['class' => 'needs-validation'],
                        'action' =>['/campains/preference-campain/copy-preference-campain']
                    ]); 
                ?>  

                    <div class="modal-body">
                        <!-- CONTENT FORM -->
                                    
                        <?php 
                            $model_preference_campain_copy = new PreferenceCampainCopy(['scenario' => PreferenceCampainCopy::SCENARIO_COPY_CAMPAIN]);
                        ?>
                        <?= 
                            $form->field($model_preference_campain_copy, 'preference_campain_id')->hiddenInput([
                                'value'=> $campain->id
                            ])->label(false); 
                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model_preference_campain_copy, 'titolo')->textInput(['value' => $campain->titolo . " - COPIA"])->label("Nome nuova campagna:") ?>
                            </div>
                        </div>

                        <div class="row mt-5">
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<div class="progress-spinner progress-spinner-active" hidden>
									<span class="sr-only">Caricamento...</span>
								</div>
							</div>
						</div>

                        <div class="row">
                            <div class="col-md-12">
                                <?php 
                                    $model_preference_campain_copy->type_copy = "solo_struttura";
                                ?>
                                <?= 
                                    $form->field($model_preference_campain_copy, 'type_copy')->radioList(
                                        [
                                            'solo_struttura' => 'Solo struttura',
                                            'struttura_e_contenuti' => 'Struttura e contenuti'
                                        ], 
                                    ['class' => "content_to_insert" ] )->label("Scegli contenuto da inserire") 
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary" data-dismiss="modal" type="button">Annulla</button>
                                <?= Html::submitButton('Copia', ['class' => 'btn btn-primary btn-modal-copy-campain'])?>
                            </div>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>
                <!-- END FORM SECTION  -->

            </div>
        </div>
    </div>
    <!-- END Modal COPY CAMPAIN  -->

<?php endforeach ?>


<?php

/** PAGE SCRIPTS */
$script = <<< JS


    $(".btn-modal-copy-campain").ready(function(){
        $(".btn-modal-copy-campain").click(function(){
            $(".btn-modal-copy-campain").addClass('disabled')
		    $('.progress-spinner').removeAttr('hidden');
        });
    });

JS;
$this->registerJs($script);
?>