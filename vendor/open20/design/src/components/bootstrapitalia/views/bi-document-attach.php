<?php

use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

//https://www.php.net/manual/en/datetime.format.php
$lastSyncDrive = '';
if (!empty($dateSyncDrive)) {
    $dateLastSyncDrive = DateUtility::getDate($dateSyncDrive, 'php:d/m/Y');
    $hourLastSyncDrive = DateUtility::getDateHour($dateSyncDrive, 'php:H:i');
    $lastSyncDrive     = Module::t('amosdesign', 'Documento Google Drive').'<br>'.Module::t('amosdesign',
            'aggiornato il').$dateLastSyncDrive.Module::t('amosdesign', 'alle').$hourLastSyncDrive;
}

if(isset($typeFolder)){
    $type = $typeFolder ? 'FOLDER' :$type;
}
$type = strtoupper($type);
if (!empty($date)) {
    $date = DateUtility::getDate($date);
}
$customTooltipInfo = (!empty($customTooltipInfo)) ? $customTooltipInfo.' ('.$type.' - '.$size.')' : '';



$infoDoc     = '<strong>'.Module::t('amosdesign', 'Nome file principale').'</strong>'.' '.$fileName.'<br>';
$infoDoc     = (!empty($nameSurname)) ? $infoDoc.'<strong>'.Module::t('amosdesign', 'Pubblicato da').'</strong>'.' '.$nameSurname.' '.(!empty($date)
        ? Module::t('amosdesign', 'il').' '.$date : '').'<br>' : $infoDoc;
$infoDoc     = (!empty($category)) ? $infoDoc.'<strong>'.Module::t('amosdesign', 'nella categoria').'</strong>'.' '.$category.'<br>'
        : $infoDoc;
$infoDoc     = (!empty($community)) ? $infoDoc.' '.'<strong>'.Module::t('amosdesign', 'in community').'</strong>'.' '.$community
        : $infoDoc;
$widthColumn = (!empty($widthColumn)) ? $widthColumn : 'col-md-4 col-sm-6';
$allegatiNum = (!empty($allegatiNum)) ? Module::t('amosdesign', 'Allegati interni').' '.$allegatiNum : '';

$model        = (!empty($model) ? $model : null);
$actionModify = (!empty($actionModify) ? $actionModify : null);
$actionDelete = (!empty($actionDelete) ? $actionDelete : null);
?>

<li class="w-100 position-relative">
    <?php if ($type == 'FOLDER') : ?>
        <?= \open20\amos\core\helpers\Html::beginTag('a', array_merge($fileUrl, ['class' => '']))?>  
    <?php elseif (!empty($link_document) && ($type != 'FOLDER')) : ?>
        <?= \open20\amos\core\helpers\Html::beginTag('a', array_merge($fileUrl, ['class' => '']))?>  
    <?php else : ?>
        <?=
            \open20\amos\core\helpers\Html::beginTag('a',array_merge($fileUrl,[
                'class' => '',
                'data-toggle' => 'tooltip',
                'title' => Module::t('amosdesign','Scarica il documento principale').' '.$fileName])
            )
        ?>
    <?php endif ?>
				
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
					<?= $size.'KB'; ?>	
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
	
    <?= \open20\amos\core\helpers\Html::endTag('a') ?>

</li>