<?php

/**
 * @var yii\web\View $this
 * @var cornernote\workflow\manager\models\Workflow $model
 */

use cornernote\workflow\manager\models\Transition;
use yii\helpers\Html;
use raoul2000\workflow\view\WorkflowViewWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\jui\Sortable;
use yii\web\JsExpression;
use yii\widgets\DetailView;


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('workflow', 'Workflow'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    table {
        word-break: break-word;
        white-space: normal;
    }
</style>


<div class="workflow-default-view">

    <div class="page-header fake-vendor-wm-page-header">

        <div class="bi-plugin-header">

            <style>
                .page-header {
                    display: none;
                }

                .page-header.fake-vendor-wm-page-header {
                    display: flex;
                }
            </style>

            <div class="flexbox title-heading-plugin">
                <div class="m-r-10">
                    <div class="h2 text-uppercase "><?= $model->id ?></div>
                </div>
            </div>

            <div class="cta-wrapper">
                <div class="flexbox manage-cta-container">
                    <a href="/workflow-manager/default/update?id=<?= $model->id ?>" class="cta link-create-news flexbox align-items-center btn btn-xs btn-primary-outline" title="Aggiorna <?= $model->id ?>" style="width:40px;height:40px;">
                        <span class="am am-edit"></span>
                        <span class="sr-only">Aggiorna</span>
                    </a>
                    <a href="/workflow-manager/default/delete?id=<?= $model->id ?>" class="cta link-create-news flexbox align-items-center btn btn-xs btn-danger" title="Elimina <?= $model->id ?>" data-confirm="Sei sicuro?" method="POST" style="width:40px;height:40px;">
                        <span class="am am-delete"></span>
                        <span class="sr-only">Elimina</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php
            if ($model->statuses) {
                echo WorkflowViewWidget::widget([
                    'workflow' => Yii::$app->workflowSource->getWorkflow($model->id),
                    'containerId' => 'workflowView'
                ]);
                echo '<div id="workflowView" style="height: 400px;border: 5px double #ccc;margin: 15px 0 30px;background: #fefefe;"></div>';
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            $sortables = [];
            foreach ($model->statuses as $status) {
                $actions = [];
                $actions[] = '<span style="width:32px;height:32px;font-size:16px" class="btn btn-outline-secondary am am-arrows sortable-handle" style="cursor: move"></span>';
                if ($model->initial_status_id != $status->id) {
                    $actions[] = Html::a('<span style="width:32px;height:32px;font-size:16px" class="btn btn-outline-secondary am am-star"></span>', ['initial', 'id' => $model->id, 'status_id' => $status->id], ['title' => Yii::t('workflow', 'Set Initial'), 'style' => 'text-decoration:none !important;']);
                }
                $actions[] = Html::a('<span style="width:32px;height:32px;font-size:16px" class="btn btn-outline-secondary am am-edit"></span>', ['status/update', 'id' => $status->id, 'workflow_id' => $status->workflow_id], ['title' => Yii::t('workflow', 'Update'), 'style' => 'text-decoration:none !important;']);
                $actions[] = Html::a('<span style="width:32px;height:32px;font-size:16px" class="btn btn-outline-secondary am am-delete"></span>', ['status/delete', 'id' => $status->id, 'workflow_id' => $status->workflow_id], [
                    'title' => Yii::t('workflow', 'Delete'),
                    'data-confirm' => Yii::t('workflow', 'Are you sure?'),
                    'data-method' => 'post',
                    'style' => 'text-decoration:none !important;'
                ]);
                $transitions = $status->startTransitions ? '<br><small><span class="glyphicon glyphicon-chevron-right"></span>&nbsp; &nbsp;' . implode(', ', ArrayHelper::map($status->startTransitions, 'end_status_id', 'endName')) . '</small>' : '';
                $metadatas = $status->metadatas ? '<br><small><span class="glyphicon glyphicon-tags"></span>&nbsp; &nbsp;' . Json::encode(ArrayHelper::map($status->metadatas, 'key', 'value')) . '</small>' : '';
                $sortables[] = [
                    'content' => '<div class="pull-right">' . implode(' ', $actions) . '</div>' . $status->name . $transitions . $metadatas,
                    'options' => [
                        'id' => 'Status_' . $status->id,
                        'class' => 'list-group-item',
                    ],
                ];
            }
            echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'id',
                        'value' => Html::tag('span', $model->id, ['class' => 'label label-default', 'style' => 'color:#fff;background:' . $model->getColor()]),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'color',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'initial_status_id',
                        'value' => $model->initial_status_id,
                    ],
                    [
                        'label' => Yii::t('workflow', 'Status') . '<br>' . Html::a(Yii::t('workflow', 'Crea Status'), ['status/create', 'workflow_id' => $model->id], ['class' => 'btn btn-success btn-xs']),
                        'value' => Sortable::widget([
                            'items' => $sortables,
                            'options' => [
                                'class' => 'list-group',
                                'style' => 'margin-bottom:0;',
                            ],
                            'clientOptions' => [
                                'axis' => 'y',
                                'update' => new JsExpression("function(event, ui){
                                    $.ajax({
                                        type: 'POST',
                                        url: '" . Url::to(['sort', 'id' => $model->id]) . "',
                                        data: $(event.target).sortable('serialize') + '&_csrf=" . Yii::$app->request->getCsrfToken() . "',
                                        success: function() {
                                            location.reload();
                                        }
                                    });
                                }"),
                            ],
                        ]),
                        'format' => 'raw',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>

    <?php if ($model->statuses) { ?>
        <?= Html::beginForm(); ?>
        <h2 class="m-t-30"><?= Yii::t('workflow', 'Transitions') ?></h2>
        <table class="table table-bordered table-condensed">
            <tr>
                <th colspan="2" rowspan="2"></th>
                <th class="text-center" colspan="<?= count($model->statuses) ?>"><?= Yii::t('workflow', 'End Status') ?></th>
            </tr>
            <tr>
                <?php foreach ($model->statuses as $endStatus) { ?>
                    <th class="text-center">
                        <?= $endStatus->name ?>
                    </th>
                <?php } ?>
            </tr>
            <?php foreach ($model->statuses as $k => $startStatus) { ?>
                <tr>
                    <?php if (!$k) { ?>
                        <th class="text-center" rowspan="<?= count($model->statuses) ?>"><?= Yii::t('workflow', 'Start Status') ?></th>
                    <?php } ?>
                    <th class="text-right"><?= $startStatus->name ?></th>
                    <?php foreach ($model->statuses as $endStatus) { ?>
                        <td class="text-center">
                            <?php
                            $options = ['uncheck' => 0];
                            if ($startStatus->id == $endStatus->id) {
                                unset($options['uncheck']);
                                $options['disabled'] = true;
                            }
                            $transition = Transition::findOne(['workflow_id' => $model->id, 'start_status_id' => $startStatus->id, 'end_status_id' => $endStatus->id]);
                            echo Html::checkbox('Status[' . $startStatus->id . '][' . $endStatus->id . ']', $transition ? true : false, $options);
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
        <?= Html::submitButton(Yii::t('workflow', 'Salva'), ['class' => 'btn btn-primary m-t-30']) ?>
        <?= Html::endForm(); ?>
    <?php } ?>

</div>