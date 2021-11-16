<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\views\agid-service
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;
use open20\amos\admin\models\base\UserProfile;
use open20\amos\core\utilities\WorkflowTransitionWidgetUtility;
use open20\agid\service\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\agid\service\models\search\AgidServiceSearch $model
 */

?>

<div class="agid-service-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= 
        DataProviderView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    
                    'id' => [
						'attribute' => 'id',
						'value' => 'id',
						'label' => '#ID'
					],

                    'agidServiceType' => [
                        'attribute' => 'agidServiceType.name',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->agidServiceType->name ?? '';
                        },
                        'label' => Module::t('amosservice','#agidServiceType') 
                    ],
                    
                    'name',

                    'agidServiceStatus' => [
                        'attribute' => 'agidServiceStatus.name',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->agidServiceStatus->name ?? '';
                        },
                        'label' => Module::t('amosservice','#agidServiceStatus') 
                    ],

                    // 'agidUoManager' => [
                    //     'attribute' => 'agidUoManager.name',
                    //     'format' => 'html',
                    //     'value' => function ($model) {
                    //         return $model->agidUoManager->name ?? '';
                    //     },
                    //     'label' => Module::t('amosservice','#agidUoManager') 
                    // ],

                    'agidUoArea' => [
                        'attribute' => 'agidUoArea.name',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->agidUoArea->name ?? '';
                        },
                        'label' => Module::t('amosservice','#agidUoArea') 
                    ],
                            
                    // 'service_status_motivation:striptags',
                    					
					'updated_by' => [
						'attribute' => 'updated_by',
						'value' => function ($model) {
							if( $user_profile = UserProfile::find()->andWhere(['user_id' => $model->updated_by])->one() ){
								return $user_profile->nome . " " . $user_profile->cognome;
							}
							return;
						},
                        'label' => Module::t('amosservice','#updated_by')
					],

                    'updated_at:datetime' => [
						'attribute' => 'updated_at',
						'value' => 'updated_at',
                        'format' => ['date', 'php:d/m/Y H:i:s'],
                        'label' => Module::t('amosservice','#updated_at')
					],

                    'status' => [
						'attribute' => 'status',
						'value' => function ($model) {
							// return WorkflowTransitionWidgetUtility::getLabelStatus($model);
                            return Module::t('amosservice', $model->status);
						},
                        'label' => Module::t('amosservice','#status')
					],

                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                    ],
                ],
            ],
            'exportConfig' => [
                'exportEnabled' => true,
                'exportColumns' => $exportColumns
            ],
    
    ]); ?>

</div>
