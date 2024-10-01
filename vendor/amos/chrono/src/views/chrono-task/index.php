<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */

use amos\chrono\base\ChronoTaskStatus;
use amos\chrono\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var amos\userimporter\models\search\UserImporterTaskSearch $model
 */
$this->title                   = Yii::t('amoschrono', '#chrono_task');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/chrono']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-import-task-index">
    <?=
    '';
//    $this->render('_search',
//        ['model' => $model, 'originAction' => Yii::$app->controller->action->id]);
    ?>

    <?=
    DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'name',
                'description',
                'schedule',
                'command',
                ['attribute' => 'started_at', 'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']->displaySettings->datetime))
                                ? Yii::$app->modules['datecontrol']->displaySettings->datetime
                                : 'd-m-Y H:i:s A']],
                ['attribute' => 'last_run', 'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']->displaySettings->datetime))
                                ? Yii::$app->modules['datecontrol']->displaySettings->datetime
                                : 'd-m-Y H:i:s A']],
                ['attribute' => 'next_run', 'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']->displaySettings->datetime))
                                ? Yii::$app->modules['datecontrol']->displaySettings->datetime
                                : 'd-m-Y H:i:s A']],
                'status' => [
                    'attribute' => 'status',
                    'format' => 'html',
                    'label' => Module::t('amoschrono', 'Stato'),
                    'value' => function($model) {
                        return ChronoTaskStatus::getStateLabel($model->status);
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{update}{delete}',
                ],
            ],
        ],
    ]);
    ?>

</div>
