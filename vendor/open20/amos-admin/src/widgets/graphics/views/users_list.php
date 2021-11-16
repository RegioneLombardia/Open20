<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets\graphics\views
 * @category   CategoryName
 */

/**
 * @var View $this
 * @var ActiveDataProvider $usersList
 * @var WidgetGraphicsUltimeNews $widget
 * @var string $toRefreshSectionId
 * @var \open20\amos\admin\models\UserProfile $model
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\admin\widgets\graphics\WidgetGraphicsUsers;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\forms\WidgetGraphicsActions;

ModuleAdminAsset::register($this);
$moduleAdmin = \Yii::$app->getModule(AmosAdmin::getModuleName());
?>

<div class="box-widget-header">
	<?php
	if (isset($moduleAdmin) && !$moduleAdmin->hideWidgetGraphicsActions) {
		echo WidgetGraphicsActions::widget([
			'widget' => $widget,
			'tClassName' => AmosAdmin::className(),
			'actionRoute' => '/'.AmosAdmin::getModuleName().'/admin/create',
			'toRefreshSectionId' => $toRefreshSectionId
		]);
	} ?>

	<div class="box-widget-wrapper">
		<h2 class="box-widget-title">
			<?= AmosIcons::show('user', ['class' => 'am-2'], AmosIcons::IC); ?>
			<?= AmosAdmin::tHtml('amosadmin', 'Utenti'); ?>
		</h2>
	</div>

	<div class="read-all">
		<?php 
			$textReadAll = AmosAdmin::t('amosadmin', '#showAll') . AmosIcons::show('chevron-right'); 
			$linkReadAll = ['/admin']; 
			echo Html::a($textReadAll, $linkReadAll, ['class' => '']); 
		?>
	</div>
</div>

<div class="box-widget latest-users">
	<section>
	<?php   
		$usersList = $dataProviderViewWidgetConf['dataProvider'];
		if (count($usersList->getModels()) == 0): ?>
			<div class="list-items list-empty">
				<h3>
					<?= AmosAdmin::t('amosadmin', 'Nessun utente') ?>
				</h3>
			</div>
		<?php
		else:  
			if($searchButtons!= null) : ?>
				<div class="search-buttons">
			<?php	foreach($searchButtons as $button) {
						echo $button;
					} 
			?>	</div> 
			<?php	
			endif;
			Pjax::begin(['id' => $toRefreshSectionId, 'timeout' => 10000]);
				$pagination = $usersList->getPagination();
				echo DataProviderView::widget($dataProviderViewWidgetConf);
				echo LinkPager::widget([
					'pagination' => $pagination,
					'maxButtonCount' => $maxButtonCount,
					'options' => [
						'class' => 'pagination shortPager',
					]
				]);
			Pjax::end();
		endif; ?>
	</section>
</div>