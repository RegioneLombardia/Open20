<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operators/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ViewUtility;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\operators\models\search\OperatorSearch $model
 */

$this->title = Yii::t('amoscore', 'Operatori');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['hideCreate'] = true;
?>

<div class="operator-index">

    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?php  // WorkAround per pulsante crea operatore, sparito dal layout inaspettatamente...
    if (Yii::$app->user->can('OPERATOR_CREATE')):
    ?>
    <div class="m-l-5 cta-wrapper" style="float: right;">
        <div class="flexbox manage-cta-container" style="float: right;">
            <a class="cta flexbox align-items-center btn btn-xs btn-primary" href="create"> 
            <span>Crea operatore</span>
            </a>
        </div>
    </div>
    <div class="m-l-5  cta-wrapper" style="float: right;">
        <div class="flexbox manage-cta-container" style="float: right;">
            <a class="cta flexbox align-items-center btn btn-xs btn-primary" href="import-operator">
                <span>Importa operatore</span>
            </a>
        </div>
    </div>
    <?php
    endif
    ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            // 'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
            'columns' => [

                'nomeCognome',
                'preferenceStructure.name' => [
                    'attribute' => 'preferenceStructure.name',
                    'format' => 'html',
                    'label' => 'Struttura',
                ],
                ['attribute' => 'created_at', 'format' => ['datetime', ViewUtility::formatDate()], 'label' => 'Data iscrizione'],

                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn', 'template' => '{update}',
                ],
            ],
        ],

    ]); ?>

</div>