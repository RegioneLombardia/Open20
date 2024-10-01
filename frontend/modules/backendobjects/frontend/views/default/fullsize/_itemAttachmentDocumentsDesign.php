<?php
use open20\amos\core\utilities\CurrentUser;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\CachedActiveQuery;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$relationQuery = $model->getCreatedUserProfile();
$relationCreated = CachedActiveQuery::instance($relationQuery);
$relationCreated->cache(60);
$createdUserProfile = $relationCreated->one();
//$model->usePrettyUrl = true;

$documentMainFile = $model->getDocumentMainFile();
$fileUrl = '';
if ($documentMainFile != null) {
  $fileUrl = '/attachments/file/download?id=' . $documentMainFile->id . '&hash=' . $documentMainFile->hash;
}
if (!empty($model->link_document)) {
  $fileUrl = $model->link_document;
}

$category = $model->documentiCategorie;
$nameCategory = null;
if(!empty($category)){
  $nameCategory= $category->titolo;
}

$url= '/documenti/documenti/view?id=' . $model->id;
$title = $model->titolo;
$type= $documentMainFile->type;
$size = $model->documentMainFile->size%1024;

$model     = (isset($model) ? $model : null);
$actionModify      ='/documenti/documenti/update?id='.$model->id;
$actionDelete      = '/documenti/documenti/delete?id='.$model->id;
?>

<li class="w-100 position-relative">
	<a target="_blank" href="<?= $url; ?>" title="<?= Yii::t('amosplatform', 'Scarica il file').' '.$title ?>">
		<div class="it-rounded-icon">
			<?php if ((in_array(strtolower($type), ['jpg', 'png', 'jpeg', 'svg']))) : ?>
				<svg class="icon icon-image">
					<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-image"></use>
				</svg>
			<?php elseif ((in_array(strtolower($type), ['pdf']))) : ?>
				<svg class="icon icon-pdf">
					<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-pdf"></use>
				</svg>
			<?php elseif ((in_array(strtolower($type), ['doc', 'docx']))) : ?>
				<svg class="icon icon-word">
					<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-word"></use>
				</svg>
			<?php elseif ((in_array(strtolower($type), ['xls', 'xlsx']))) : ?>
				<svg class="icon icon-excel">
					<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-excel"></use>
				</svg>
			<?php elseif ((in_array(strtolower($type), ['zip', 'rar']))) : ?>
				<svg class="icon icon-secondary">
					<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder-zip"></use>
				</svg>
			<?php elseif ($model->is_folder) : ?>
				<svg class="icon icon-folder icon-sm">
				<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder"></use>
				</svg>
			<?php else : ?>
				<svg class="icon icon-secondary">
					<use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-link"></use>
				</svg>
			<?php endif ?>
		</div>
		<div class="it-right-zone">
			<span class="text">
				<?= $title; ?>
				<?php if(!$model->is_folder):?> 
					(<?= $type;?>) -
					<?= round(($size/1000)).'KB'; ?>	
				<?php endif; ?>								
			</span>
			
			
		</div>
		<div class="other-info-item d-flex align-items-center justify-content-end ml-auto position-absolute z-index-3" style="top:16px; right:0;">
				<?= $this->render(
				'@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
				[
					'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete),
					'model' => $model
				]
				);
				?>
			</div>
	
	</a>
</li>