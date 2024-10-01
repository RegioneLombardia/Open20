<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\agid\dataset\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\AgidDatasetSearch $model
 */

$this->title = Yii::t('amoscore', 'Agid Dataset');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="agid-dataset-index">

	<?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

	<?=
		DataProviderView::widget([
			'dataProvider' => $dataProvider,
			//'filterModel' => $model,
			'currentView' => $currentView,
			'gridView' => [
				'columns' => [
					// ['class' => 'yii\grid\SerialColumn'],

					'title' => [
						'attribute' => 'title',
						'label' => Module::t('amosdataset', '#title'),
					],

					'agidDatasetType' => [
						'attribute' => 'agidDatasetType.name',
						'format' => 'html',
						'value' => function ($model) {
							return strip_tags($model->agidDatasetType->name);
						},
						'label' => Module::t('amosdataset', '#type_of_dataset'),
					],

					'status' => [
						'attribute' => 'status',
						'value' => function($model){
							// return $model->getWorkflowStatus()->getLabel();
							return Module::t('amosdataset', $model->status);
						},
						'label' => Module::t('amosdataset', '#status'),
					],

					[
						'class' => 'open20\amos\core\views\grid\ActionColumn',
					]
				]
			]
		]);
	?>
</div>
