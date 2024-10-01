<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */


/**
 * @var View $this
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;

\open20\amos\documenti\assets\ModuleDocumentiDocumentsExplorerAsset::register($this);

$moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
$moduleL = \Yii::$app->getModule('layout');
if (!empty($moduleL)) {
    \open20\amos\layout\assets\SpinnerWaitAsset::register($this);
} else {
    \open20\amos\core\views\assets\SpinnerWaitAsset::register($this);
}

$exploder = (isset($explorer) && $explorer == true) ? true : false; 
$exploClass = !isset($explorer) ? '' : '-explorer';
$exploSize  = !isset($explorer) ? '8' : '12';

// Importing explorer html parts
echo $this->render('parts/navbar');
echo $this->render('parts/breadcrumb');
echo $this->render('parts/folders');
echo $this->render('parts/files');
echo $this->render('parts/modals/new-folder-modal');
echo $this->render('parts/modals/delete-file-modal');
echo $this->render('parts/modals/delete-folder-modal');
echo $this->render('parts/modals/delete-area-modal');
echo $this->render('parts/modals/delete-stanza-modal');
?>

<div class="loading" id="loader" hidden></div>
    <div class="box-widget-header">
        <div class="box-widget-wrapper">
            <h2 class="box-widget-title">
                <?= AmosIcons::show('news', ['class' => 'am-2'], AmosIcons::IC)?>
                <?= AmosDocumenti::tHTml('amosdocumenti', 'Documenti') ?>
            </h2>
        </div>
    </div>
<div class="box-widget">
    <section>
        <div class="list-items">
            <div id="documents-explorer">
                <?php if (!isset($explorer)) : ?>
                <section id="content-explorer-navbar"></section>
                <div class="col-md-4 col-xs-12 documents-explorer-sidebar-container">
                    <div id="location-title" class="col-xs-12 sidebar-container-header">
                        <h2><?= AmosDocumenti::t('amosdocumenti', 'Aree di condivisione'); ?></h2>
    <!--                        <span id="go-back-room" class="am am-arrow-left" title="Torna indietro"> <!--TODO add class hidden if first layer-->
    <!--                            <span class="sr-only">Indietro</span>-->
    <!--                        </span>-->
    <!--                        <h2 class=""></h2> <!--TODO change with room name-->
                    </div>
                    <div class="col-xs-12 documents-explorer-sidebar">
                        <section id="content-explorer-sidebar">
                            <div class="stanze-list" id="stanze-list">
                            </div>
                        </section>
                    </div>
                </div>
                <?php endif ?>
                <div class="col-md-<?= $exploSize ?> col-xs-12 documents-explorer-items-container">
                    <div class="col-xs-12 items-container-header">
                        <section id="content-explorer-breadcrumb"></section>
                        <!--                        <span id="go-back-files" class="am am-arrow-left" title="Torna indietro"><span class="sr-only">Indietro</span></span>-->
                        <!--                        <h2 class="current-directory">Cartella corrente</h2> <!--TODO change with folder name if subfolder-->
                    </div>
                    <div class="col-xs-12 documents-explorer-items">
                        <section id="content-explorer-folders">
                        </section>
                        <section id="content-explorer-files">
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
