<?php
/**
 * @since 21.12.2015
 * @var array $tasks
 * @var array $methods
 * @var ActiveDataProvider $dataProvider
 */

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use rossmann\cron\components\TaskInterface;
use common\widgets\GridView;

$this->title = Yii::t('cron', 'Task list');
$this->params['breadcrumbs'][] = ['label' => 'Task Manager', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('tasks_template');
?>
<div class="cron-table">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'class' => 'cron-table',
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn', 'cssClass' => 'task_checkbox'],
            'id',
            'time',
            'command',
            [
                'attribute' => 'status',
                'contentOptions' => function ($dataProvider) {
                    return (TaskInterface::TASK_STATUS_ACTIVE == $dataProvider->status) ? [] : ['class' => 'text-danger'];
                }
            ],
            'locked',
            'comments',
            [
                'attribute' => 'ts',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->ts);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'ts_updated',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->ts_updated);
                },
                'format' => 'raw'
            ],
            ['class' => 'yii\grid\ActionColumn', 'headerOptions' => ['style' => 'width:45px'], 'template' => '{update}{show-log}{run}'],
        ],
    ]);
    ?>
</div>
<br/>
<form class="form-inline" action="<?= Url::toRoute(['tasks-update']) ?>">
    <div class="form-group">
        <label for="mode"><?= Yii::t('cron', 'With selected') ?></label>
        <select class="form-control" id="mode" name="mode">
            <option value="Enable"><?= Yii::t('cron', 'Enable') ?></option>
            <option value="Disable"><?= Yii::t('cron', 'Disable') ?></option>
            <option value="Delete"><?= Yii::t('cron', 'Delete') ?></option>
            <option value="Run"><?= Yii::t('cron', 'Run') ?></option>
        </select>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('cron', 'Apply'), [
            'class' => 'btn btn-primary',
            'id' => 'execute_action',
            'title' => Yii::t('cron', 'confirm_changes'),
            'data-toggle' => 'confirmation',
            'data-popout' => 'true',
        ]) ?>
    </div>
</form>
<div id="output_section" style="display: none;">
    <h3><?= Yii::t('cron', 'Task output') ?></h3>
    <pre id="task_output_container"></pre>
</div>
