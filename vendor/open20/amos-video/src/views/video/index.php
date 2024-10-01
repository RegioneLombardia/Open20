<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione/views
 */

use amos\sitemanagement\utility\EmbeddedUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\video\Module;
use open20\amos\core\icons\AmosIcons;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var frontend\modules\formazione\models\search\FormazioneSearch $model
 */


 
$this->title = 'Gestisci Video';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/video']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['forceBreadcrumbs'][] = ['label' => $this->params['titleSection']];

?>
<div class="formazione-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'title',
                'url' => [
                    'attribute' => 'url',
                    'value' => function($model) {
                        return EmbeddedUtility::getUrlEmbeddedVideo($model->url);
                    }
                ],
                'in_evidenza' => [
                    'attribute' => 'in_evidenza',
                    'value' => function($model) {
                        /** @var \frontend\modules\formazione\models\search\FormazioneSearch $model */
                        return $model->in_evidenza ? Module::t('amosvideo', 'Yes') : Module::t('amosvideo', 'No');
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $options = ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Cancella'), 'data-pjax' => '0', 'data-confirm' => Module::t('amosvideo', '#VIDEO_CONFIRM_DELETE')];
                            return Html::a(\open20\amos\core\icons\AmosIcons::show('am am-file'), ['video/delete', 'id' => $model->id], $options);
                        }
                    ]
                ],
            ],
        ],
        'iconView' => [
            'itemView' => '_icon'
        ],
    ]); ?>

</div>
