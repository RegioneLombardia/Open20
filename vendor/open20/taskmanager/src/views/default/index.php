<?php

use open20\amos\taskmanager\TaskManager;
use open20\amos\taskmanager\models\TaskManagerJob;
use open20\amos\core\icons\AmosIcons;
use kartik\form\ActiveForm;
use open20\amos\core\forms\editors\Select;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title                   = TaskManager::t('amostaskmanager', 'commands');
$this->params['breadcrumbs'][] = $this->title;

Url::remember();
?>

<div class="taskmanager-index">

    <div class="row search-area">
        <div class="col-xs-12">
            <h3><?= TaskManager::t('amostaskmanager', 'commands_search_filter') ?></h3>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <?php
                $form        = ActiveForm::begin();
                ?>
                <div class="col-md-6">
                    <label for="status"><?= TaskManager::t('amostaskmanager', 'status') ?></label>
                    <?=
                    Html::dropDownList('status', $filter['status'], $statuses, ['class' => 'form-control']);
                    ?>
                </div>
                <div class="col-md-6">
                    <label for="status"><?= TaskManager::t('amostaskmanager', 'module') ?></label>
                    <?=
                    Html::dropDownList('module', $filter['module'], $modules, ['class' => 'form-control']);
                    ?>
                </div>
                <div class="col-xs-12 m-t-30">
                    <?=
                    Html::submitButton(TaskManager::t('amostaskmanager', 'filter'),
                        ['class' => 'btn btn-primary search-filter'])
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="row m-t-30">
        <div class="col-xs-12">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'status' => [
                        'format' => 'html',
                        'label' => TaskManager::t('amostaskmanager', 'status'),
                        'value' => function ($model) {
                            $type = 'info';
                            switch ($model->status) {
                                case TaskManagerJob::STATUS_COMPLETED:
                                    $type = 'success';
                                    break;
                                case TaskManagerJob::STATUS_FAILED:
                                    $type = 'error';
                            }
                            return '<span class="text-'.$type.'">'.TaskManager::t('amostaskmanager',
                                TaskManagerJob::STATUSES[$model->status]).'</span>';
                        },
                    ],
                    'created_at' => [
                        'label' => TaskManager::t('amostaskmanager', 'created_at'),
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model->created_at, 'humanalwaysdatetime');
                        }
                    ],
                    'updated_at' => [
                        'label' => TaskManager::t('amostaskmanager', 'updated_at'),
                        'value' => function ($model) {
                            if ($model->status != TaskManagerJob::STATUS_SCHEDULED) {
                                return Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i');
                            } else {
                                return TaskManager::t('amostaskmanager', 'never_executed');
                            }
                        }
                    ],
                    'module' => [
                        'label' => TaskManager::t('amostaskmanager', 'module'),
                        'value' => 'module'
                    ],
                    'created_by' => [
                        'label' => TaskManager::t('amostaskmanager', 'created_by'),
                        'value' => function ($model) {
                            if ($model->created_by == \Yii::$app->getUser()->getId()) {
                                return TaskManager::t('amostaskmanager', 'me');
                            } else {
                                return \open20\amos\core\user\User::findOne($model->created_by)->profile->nomeCognome;
                            }
                        }
                    ],
                    'output' => [
                        'label' => TaskManager::t('amostaskmanager', 'filename'),
                        'value' => function ($model) {
                            if ($model->output) {
                                return $model->output->name.'.'.$model->output->type;
                            }
                            return '';
                        }
                    ],
                    'download_count' => [
                        'label' => TaskManager::t('amostaskmanager', 'download_count'),
                        'value' => function ($model) {
                            if ($model->output) {
                                return $model->output->numDownloads;
                            }
                            return '';
                        }
                    ],
                    'actions' => [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{download}{delete}',
                        'visibleButtons' => [
                            'delete' => function ($model, $key, $index) {
                                return \Yii::$app->getUser()->can('TASKMANAGER_DELETE', $model);
                            }
                        ],
                        'buttons' => [
                            'download' => function ($url, $model, $key) {
                                if ($model->output && $model->status == TaskManagerJob::STATUS_COMPLETED) {
                                    return Html::a('<span class="glyphicon glyphicon-download"></span>',
                                            [$model->output->getUrl()]);
                                }
                                return '';
                            },
// 							'delete' => function ($url, $model, $key) {
// 								/** @var ActiveRecord $model */
//
// 								// The base class name
// 								$baseClassName = \yii\helpers\StringHelper::basename($this->model->className());
//
// 								// Update permission name
// 								$updatePermission = strtoupper($baseClassName . '_UPDATE');
//
// 								$btn = '';
// 								if (\Yii::$app->user->can($updatePermission, ['model' => $this->model])) {
// 									$btn = Html::a('<span class="glyphicon glyphicon-trash"></span>',
// 										[
// 											'/' . FileModule::getModuleName() . '/file/delete',
// 											'id' => $model->id,
// 											'item_id' => $this->model->id,
// 											'model' => $this->getModule()->getClass($this->model),
// 											'attribute' => $this->attribute
// 										],
// 										[
// 											'title' => FileModule::t('amosattachments', 'Delete'),
// 											'data-confirm' => FileModule::t('amosattachments', 'Are you sure you want to delete this item?'),
// 											'data-method' => 'post'
// 										]
// 									);
// 								}
// 								return $btn;
// 							}
                        ]
                    ]
                ],
            ]);
            ?>
        </div>
    </div>
</div>
