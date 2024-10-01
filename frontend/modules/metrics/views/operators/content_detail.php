<?php

use frontend\modules\metrics\models\Operators;

/**
 * @var $this \yii\web\View
 * @var $content string
 * @var $result array
 * @var $resultExport array
 * @var $stringSearch array
 * @var $model \frontend\modules\metrics\models\ContentDetails
 *
 */

$this->title = 'Report';
$this->params['breadcrumbs'][] = $this->title;

$usersExportColumns = [
    'matricola' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'matricola',
        'label' => 'Matricola',
    ],
    'nome' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nome',
        'label' => 'Nome',
    ],
    'cognome' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cognome',
        'label' => 'Cognome',
    ],
    'codice_fiscale' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'codice_fiscale',
        'label' => 'Codice fiscale',
    ],
    'profile' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'profile',
        'label' => 'Ruolo',
    ],
    'email' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'email',
        'label' => 'Email',
    ],
    'ultimo_accesso' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ultimo_accesso',
        'label' => 'Ultimo login',
        'value' => function ($model) {
            return Yii::$app->formatter->asDatetime($model['ultimo_accesso'], 'php:d/m/Y h:i:s');
        }
    ],
    'data_inizio_servizio' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'data_inizio_servizio',
        'label' => 'Inizio servizio',
        'value' => function ($model) {
            if (empty($model['data_inizio_servizio'])) return '';
            return Yii::$app->formatter->asDate($model['data_inizio_servizio'], 'php:d/m/Y');
        }

    ],
    'data_fine_servizio' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'data_fine_servizio',
        'label' => 'Fine servizio',
        'value' => function ($model) {
            if (empty($model['data_fine_servizio'])) return '';
            return Yii::$app->formatter->asDate($model['data_fine_servizio'], 'php:d/m/Y');
        }
    ],

    'content_type' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'content_type',
        'label' => 'Tipo contenuto',
        'value' => function ($model) {
            return $model['content_type'];
        }
    ],
    'content_title' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'content_title',
        'label' => 'Titolo contenuto',
        'value' => function ($model) {
            return $model['content_title'];
        }
    ],
    'content_creation' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'content_creation',
        'label' => 'Contenuto creato il',
        'value' => function ($model) {
            return Yii::$app->formatter->asDatetime($model['content_creation'], 'php:d/m/Y h:i:s');
        }
    ],
];

?>
<?php
if ($stringSearch['contents'] || $stringSearch['oeprators']) :
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
        <div>Nessun parametro di ricerca impostato. I dati mostrati sono tutti quelli pubblicati a sistema</div>
    </div>
<?php
endif;
?>
<div class="widget-report widget-detail m-t-35">


    <div class="pull-left">
        <h3><?= \frontend\modules\metrics\models\Operators::TYPES_LABELING[$content]['plural'] ?></h3>
    </div>
    <div class="pull-right">
        <?php
        echo \kartik\export\ExportMenu::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $resultExport,
                'sort' => [
                    'attributes' => ['active', 'nome', 'cognome'],
                ],
                'pagination' => false,
            ]),
            'columns' => $usersExportColumns,
            'filename' => 'export_utenti',
        ]);
        ?>
    </div>
    <div class="clearfix"></div>
    <div class="row m-t-35">
        <div class="col-md-6">
            <?php
            $reportData[] = ['Nominativo', 'Numero contenuti creati'];
            foreach ($result as $userArray) :
                $up = \open20\amos\admin\models\UserProfile::findOne(['user_id' => $userArray['user_id']]);
                $reportData[] = [$userArray['nome'] . ' ' . $userArray['cognome'], intval($userArray[$content . '_number'])];
            ?>
                <div class="item-list-report border-dashed ">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="user-report">
                                <?php
                                echo \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $up, 'avatarDimension' => 'table_small', 'avatarXS' => true])
                                ?>
                                <div>
                                    <strong><?= $userArray['nome'] . ' ' . $userArray['cognome']; ?></strong>
                                    <div><span><?= $userArray['profile'] ?></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <b><?= $userArray[$content . '_number'] ?? 0; ?></b> <?= ($userArray[$content . '_number'] != 1) ? Operators::TYPES_LABELING[$content]['pluralCreationLabel'] :  Operators::TYPES_LABELING[$content]['singularCreationLabel']; ?>
                        </div>
                    </div>
                </div>
            <?php

            endforeach;
            ?>
        </div>

        <div class="col-md-6">
            <?php
            echo \open20\amos\sondaggi\components\GraficiGoogle::widget([
                'visualization' => 'PieChart',
                'data' => $reportData,
                'options' => [
                    'is3D' => false,
                    'title' => null,
                    'sliceVisibilityThreshold' => 0,
                    'showTip' => true,
                    'legend' => [
                        'position' => 'top',
                        'maxLines' => 20,
                        'alignment' => 'center',
                    ],
                    'tooltip' => [
                        'trigger' => 'selection',
                    ],
//                    'width' => '100%',
                    'height' => 500
                ]
            ]);
            ?>
        </div>
    </div>
</div>
<button onclick="history.back()" class="btn btn-link" title="Torna alla ricerca"><span class="mdi mdi-arrow-left"></span> Torna alla ricerca</a>