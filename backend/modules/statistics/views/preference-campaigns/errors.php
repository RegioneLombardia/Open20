<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/statistics/views
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\sondaggi\components\GraficiGoogle;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * @var yii\web\View $this
 * @var backend\modules\campains\models\PreferenceCampain $model
 * @var yii\widgets\ActiveForm $form
 */


$this->title = Yii::t('statistics', 'Errori');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/statistics/preference-campains/errors']];
$this->params['breadcrumbs'][] = $this->title;

// TODO da sistemare... il form inietta questo messaggio di errore! un fantasma...
$this->registerCss("
#errore-alert-common {display:none;}
");

?>
<div class="row">
    <div class="col-md-12">
        <?php
        if (!empty($errorsData)) {
            echo \kartik\grid\GridView::widget([
                'dataProvider' => $errorsData,
                // 'showPageSummary' => true,
                'striped' => true,
                'hover' => true,
                'panel' => [
                    'type' => 'info',
                    'heading' => false, // 'Suddivisione di genere'
                ],
                'toggleDataOptions' => [
                    'all' => [
                        'label' => 'Tutto'
                    ],
                ],
                'exportConfig' => [
                    \kartik\grid\GridView::HTML => [],
                    \kartik\grid\GridView::CSV => [],
                    \kartik\grid\GridView::TEXT => [],
                    \kartik\grid\GridView::EXCEL => [],
                    \kartik\grid\GridView::PDF => [],
                ],
                'columns' => [
                    'name' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'name',
                        'label' => 'Nome',
                        'value' => function ($model) {
                            return isset($model['name']) ? $model['name'] : '';
                        }
                    ],
                    'surname' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'surname',
                        'label' => 'Cognome',
                        'value' => function ($model) {
                            return isset($model['surname']) ? $model['surname'] : '';
                        }
                    ],
                    'registration_email' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'registration_email',
                        'label' => 'Email di registrazione',
                        'value' => function ($model) {
                            return isset($model['registration_email']) ? $model['registration_email'] : '';
                        }
                    ], 
                    'email' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'email',
                        'label' => 'Email in errore',
                        'value' => function ($model) {
                            return isset($model['email']) ? $model['email'] : '';
                        }
                    ], 
                    'type' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'type',
                        'label' => 'Tipo errore',
                        'value' => function ($model) {
                            return isset($model['type']) ? $model['type'] : '';
                        }
                    ],
                ]
            ]);
        }
        ?>

    </div>

</div>