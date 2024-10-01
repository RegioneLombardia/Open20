<?php

use frontend\modules\metrics\models\Operators;

/**
 * @var $model \frontend\modules\metrics\models\OperatorContent
 * @var $userprofile \open20\amos\admin\models\UserProfile
 * @var $stringSearch array
 */


$this->title = 'Report';
$this->params['breadcrumbs'][] = $this->title;

$contentExportColumns = [
    'type' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'type',
        'label' => 'Tipo contenuto',
    ],
    'created_at' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created_at',
        'label' => 'Creato il',
        'value' => function ($model) {
            return Yii::$app->formatter->asDatetime($model['created_at'], 'php:d/m/Y h:i:s');
        }
    ],
    'nominativo' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nominativo',
        'label' => 'Nominativo',
        'value' => function ($model) {
            $up = \open20\amos\admin\models\UserProfile::findOne(['user_id' => $model['created_by']]);
            return $up->getNomeCognome();
        }
    ],
    'title' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'title',
        'label' => 'Titolo',
    ],
];

?>

<?php
if (!empty($model->startDate) || (!empty($model->endDate))) :
    $labelFrom = '';
    if (!empty($model->startDate)) {
        $labelFrom = 'da ' . Yii::$app->formatter->asDate($model->startDate, 'php:d/m/Y');
    }
    $labelTo = '';
    if (!empty($model->endDate)) {
        $labelTo = 'a ' . Yii::$app->formatter->asDate($model->endDate, 'php:d/m/Y');
    }
?>
    <div>Report <?= $labelFrom ?> <?= $labelTo ?> </div>
<?php
endif;
?>
<?php
if ($stringSearch['contents'] || $stringSearch['operators']) :
?>
    <div class="alert alert-info" role="alert">
        <div>Report basato sulla ricerca:</div>
        <?php
        if ($stringSearch['contents']) :
            echo '<p class="m-t-10">' . $stringSearch['contents'] . '</p>';
        endif;
        ?>

        <?php
        if ($stringSearch['operators']) :
            echo '<p class="m-t-10">' . $stringSearch['operators'] . '</p>';
        endif;
        ?>
    </div>
<?php
else :
?>
    <div class="alert alert-info" role="alert">
        <div>Nessun parametro di ricerca impostato. I dati mostrati sono tutti quelli creati dall'utente</div>
    </div>
<?php
endif;
?>

<div class="widget-report widget-detail m-t-35">
    <div class="user-report pull-left">
        <?php
        echo \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $userProfile, 'avatarDimension' => 'table_small'])
        ?>
        <span class="h3 m-b-0">
            <?php
            echo $userProfile->getNomeCognome();
            ?>
        </span>



    </div>
    <div class="pull-right">
        <?php
        echo \kartik\export\ExportMenu::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $dataExport,
                'pagination' => false,
            ]),
            'columns' => $contentExportColumns,
            'filename' => 'export_contenuti_' . strtolower(str_replace(' ', '_', $userProfile->getNomeCognome())),
        ]);
        ?>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-4">
            <div class="item-list-report">

            </div>
            <?php
            $dataGraph[] = ['Contenuto', 'Numero'];
            foreach (array_keys(Operators::TYPES_LABELING) as $type) :
                $val = (isset($data[$type]) && !empty($data[$type])) ? intval($data[$type . '_number']) : 0;
                $label = ($val > 0) ? Operators::TYPES_LABELING[$type]['plural'] : Operators::TYPES_LABELING[$type]['singular'];
                $dataGraph[] = [$label, $val];
            ?>
                <div>
                    <strong><?= $label ?>:</strong> <?= $val ?>
                </div>
            <?php
            endforeach;
            ?>
        </div>
        <div class="col-md-8">
            <?php
            echo \open20\amos\sondaggi\components\GraficiGoogle::widget([
                'visualization' => 'PieChart',
                'data' => $dataGraph,
                'options' => [
                    'is3D' => false,
                    'title' => null,
                    'sliceVisibilityThreshold' => 0,
                    'showTip' => true,
                    'legend' => [
                        'position' => 'top',
                        'maxLines' => 2,
                        'alignment' => 'center',
                    ],
                    'tooltip' => [
                        'trigger' => 'selection',
                    ],
                    'height' => 500,
                ]
            ]);
            ?>
        </div>
    </div>

</div>
<button onclick="history.back()" class="btn btn-link" title="Torna alla ricerca"><span class="mdi mdi-arrow-left"></span> Torna alla ricerca</a>