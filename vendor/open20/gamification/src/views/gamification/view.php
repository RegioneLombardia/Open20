<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\gamification\Module;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\AccordionWidget;



/* @var yii\web\View $this
 * @var open20\gamification\models\Gamification $model
 * @var string $currentView
 * @var string $indirizzoCompleto
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title =  $model->titolo;
$this->params['breadcrumbs'][] = $model->titolo;

?>



<div class="gamification-view">
    
    <div class="container">
        <!--PARTE INTRODUTTIVA-->
        <div class="gamification-intro">
                <div class="row">
                    <div class="col-xs-12 header-widget">
                        <?= ItemAndCardHeaderWidget::widget([
                                'model' => $model,
                                'publicationDateField' => 'created_at',
                                'showPrevalentPartnershipAndTargets' => true,
                            ]
                        ) ?>
                        <?= ContextMenuWidget::widget([
                                'model' => $model,
                                'actionModify' => "/gamification/gamification/update?id=" . $model->id,
                                'actionDelete' => "/gamification/gamification/delete?id=" . $model->id,
                                'labelDeleteConfirm' => Module::t('gamification', 'Sei sicuro di voler cancellare questa gamification?'),
                        ]) ?>
                        
                    </div>

                    <div class="col-xs-12">
                        <?php if (!empty($model->descrizione)) { ?>
                            <div class="descrizione lead">
                                <?= $model->descrizione; ?>
                            </div>
                            
                        <?php } ?>
                    </div>
                </div>
        </div>

        <div class="gamification-current-scores">
            <?= AccordionWidget::widget([
                        'items' => [
                            [
                                'header' => 'Punteggi attuali',
                                'content' => $this->render('sections/_current-scores', ['model' => $model,'form' => $form, 'dataProvider' =>$dataProvider, 'currentView' => $currentView])
                            ]
                        ],
                        'headerOptions' => ['tag' => 'h2'],
                        'clientOptions' => [
                            'collapsible' => true,
                           // 'active' => 'false',
                            'icons' => [
                                'header' => 'ui-icon-amos am am-plus-square',
                                'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ]
                        ],
                    ]);
            ?>
        </div>
        
        <div class="gamification-date">
            <?= AccordionWidget::widget([
                        'items' => [
                            [
                                'header' => 'Date della campagna',
                                'content' => $this->render('sections/_dates', ['model' => $model,'form' => $form])
                            ]
                        ],
                        'headerOptions' => ['tag' => 'h2'],
                        'clientOptions' => [
                            'collapsible' => true,
                            'active' => 'false',
                            'icons' => [
                                'header' => 'ui-icon-amos am am-plus-square',
                                'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ]
                        ],
                    ]);
            ?>
        </div>
                
        
        
        <div class="gamification-scores">
            <?= AccordionWidget::widget([
                        'items' => [
                            [
                                'header' => 'Assegnazione dei punteggi',
                                'content' => $this->render('sections/_scores', ['model' => $model,'form' => $form])
                            ]
                        ],
                        'headerOptions' => ['tag' => 'h2'],
                        'clientOptions' => [
                            'collapsible' => true,
                            'active' => 'false',
                            'icons' => [
                                'header' => 'ui-icon-amos am am-plus-square',
                                'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ]
                        ],
                    ]);
            ?>
        </div>




        

       
    </div>


</div>