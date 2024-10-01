<?php

use Yii;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\core\forms\ContextMenuWidget;
use open20\design\assets\BootstrapItaliaDesignAsset;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$data_aggiornamento = '';
$latestDate = reset($files)->data_pubblicazione;
foreach($files as $file){
    if (strtotime($file->data_pubblicazione) > strtotime($latestDate)) {
        $latestDate = $file->data_pubblicazione;
    }
}
$data_aggiornamento = $latestDate;

?>
<div class="file-explorer-container">
    
    <div class="loading" style="display:none"><span class="mdi mdi-loading mdi-spin" style="font-size:60px"></span></div>
    
    <?php if(!$params['hideBreadcrumb']): ?>
    <div class="col-xs-12 breadcrumbs">
        <?= $this->render('file_explorer_breadcrumbs',['breadcrumbs'=>$breadcrumbs]); ?>
    </div>
    <?php endif; ?>
    
    <div class="col-xs-12 main-file d-flex">
        <p><h3><?= AmosDocumenti::t('amosdocumenti', 'Sei in:') ?> <b><?= empty($model) ? Yii::t('amosdocumenti','Home') : '<span class="mdi mdi-folder-outline" />'.$model->titolo ?></b></h3></p>     
    </div>
    
    <?php if($params['showUpdateDate'] && !empty($data_aggiornamento)): ?>
    <div class="col-xs-12">
        <p class="h5"><b><?= AmosDocumenti::t('amosdocumenti', 'Contenuti aggiornati al:') ?></b> <?= date('d/m/Y',strtotime($data_aggiornamento)) ?></p>     
    </div>
    <?php endif; ?>
    
    <hr>
    
    <?php if($params['withSearch']): ?>
    <div class="col-xs-12 filter">
        <?= $this->render('file_explorer_filter',['folder'=>$model,'filters'=>$filters,'params'=>$params]); ?>
        <hr>
    </div>
    <?php endif; ?>
    
    <?php if(!$params['hideFolder']): ?>
    <div class="col-xs-12 folders">
        <?= $this->render('file_explorer_folders',['folders'=>$folders, 'defaultFolderColumns' => $defaultFolderColumns]); ?>
    </div>
    <?php endif; ?>

    <div class="col-xs-12 list">
        <?= $this->render('file_explorer_list',['files'=>$files,'params'=>$params]); ?>
    </div>
</div>