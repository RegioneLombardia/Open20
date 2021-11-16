<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/registeredusers/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ViewUtility;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\modules\registeredusers\models\search\OperatorSearch $model
 */

$this->title = Yii::t('amoscore', 'Utenti');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/registeredusers']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['hideCreate'] = true;
?>
<div class="registereduser-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            //'formatter' => ['class' => Yii::$app->formatter::className(), 'nullDisplay' => ''],
            'columns' => [
                'nomeCognome',
                ['attribute' => 'created_at', 'format' => ['datetime', ViewUtility::formatDate()], 'label' => 'Data iscrizione'],
                'active' => [
                    'attribute' => 'attivo',
                    'format' => 'html',
                    'label' => 'Stato',
                    'value' => function ($model) {
                        if ($model->user->status == 10) {
                            return '<span class="am am-check-circle text-success" style="font-size:24px" title="Attivo"></span>';
                        } else {
                            return '<span class="am am-minus-circle-outline text-warning" style="font-size:24px" title="Disattivo"></span>';
                        }

                    }
                ],
                ['attribute' => 'pc_request_delete_date', 'format' => ['datetime', ViewUtility::formatDate()], 'label' => 'Data disiscrizione'],
                [
                    'attribute' => 'preference_origin_system_id',
                    'format' => 'html',
                    'label' => 'Sistema di origine',
                    'value' => function ($model) {
                        return $model->preferenceOriginSystem->name;
                    }

                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn', 'template' => '{update}',
                ],
            ],
        ],

    ]); ?>

</div>
