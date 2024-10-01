<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views\hierarchical-befe
 * @category   CategoryName
 */

use open20\amos\attachments\models\File;
use open20\amos\core\record\CachedActiveQuery;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiHierarchyBefeAsset;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity;
use open20\amos\documenti\widgets\ItemDocumentCardWidget;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicsHierarchicalDocumentsBefeCommunity $widget
 * @var string $currentView
 * @var string $toRefreshSectionId
 * @var yii\data\ActiveDataProvider $dataProviderFolders
 * @var yii\data\ActiveDataProvider $dataProviderDocuments
 * @var array $availableViews
 * @var open20\amos\documenti\models\search\DocumentiSearch $filter
 */

ModuleDocumentiHierarchyBefeAsset::register($this);

?>
<?php
$redirectLink = '';
if (!Yii::$app->getUser()->can('DocumentValidate'))
    $redirectLink = '/documenti/documenti/own-documents?download=1%26currentView=grid';

$titleSection = AmosDocumenti::t('amosdocumenti', 'Esplora documenti');
$urlLinkAll = '/documenti/documenti/all-documents';
$labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
$titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');

$labelCreateDocument = AmosDocumenti::t('amosdocumenti', 'Nuovo');
$titleCreateDocument = AmosDocumenti::t('amosdocumenti', 'Crea un nuovo documento');
$labelCreateFolder = AmosDocumenti::t('amosdocumenti', '#create_new_folder_label');
$titleCreateFolder = AmosDocumenti::t('amosdocumenti', '#create_new_folder_title');
$labelManage = AmosDocumenti::t('amosdocumenti', 'Gestisci');
$titleManage = AmosDocumenti::t('amosdocumenti', 'Gestisci i documenti');
$urlCreate = '/documenti/documenti/create?to=' . $redirectLink;

$manageLinks = [];
$controller = \open20\amos\documenti\controllers\DocumentiController::class;
if (method_exists($controller, 'getManageLinks')) {
    $manageLinks = $controller::getManageLinks();
}

?>
<div class="widget-graphic-cms-bi-less widget-graphic-cms-bi-less-hierarchical-documents-community card-documenti container">
    <?php

    if (strpos(Url::current(), '/documenti/explore-documents') === false) {
        ?>
        <div class="m-r-10">
            <div class="h2 text-uppercase "><?= $titleSection ?></div>
        </div>
        <?php
    }

    $categories = (!empty($widget->categories) ? '&categories=' . $widget->categories : '');
    Pjax::begin(['id' => $toRefreshSectionId, 'enablePushState' => false, 'timeout' => 15000]);
    $parentId = $widget->parentId;

    $addParentId = '';
    if ($parentId) {
        $addParentId = 'parentId=' . $parentId;
    }
    ?>

    <div class="bi-plugin-header">
        <div class="cta-wrapper flexbox m-b-10">
            <?php if (DocumentsUtility::canCreateForExplorer()): ?>
                <a class="cta link-create-documenti flexbox align-items-center btn btn-xs btn-primary m-r-10" data-pjax=0
                   href="<?= $urlCreate ?><?= !empty($addParentId) ? '&' . $addParentId : '' ?>" title="<?= $titleCreateDocument; ?>">
                    <span class="am am-plus-circle-o"></span>
                    <span><?= $labelCreateDocument; ?></span>
                </a>
                <a class="cta link-action-documenti flexbox align-items-center btn btn-xs btn-primary" data-pjax=0
                   href="<?= $urlCreate ?>&isFolder=1<?= !empty($addParentId) ? '&' . $addParentId : '' ?>" title="<?= $titleCreateFolder; ?>">
                    <span class="am am-plus-circle-o"> </span>
                    <span><?= $labelCreateFolder; ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="list-view bg-100 p-4">
        <?php

        //        if (true || empty($parentId)) {
        $this->registerJs("
            var url = '/documenti/hierarchical-documents/render-hierarchical-documents-widget-befe-community?search=';
            $('#search').on('keyup', function(event){

                if(event.keyCode === 13){
                    $.pjax.reload({
                        container: '#$toRefreshSectionId',
                        url: url + $(this).val() + '$categories',
                        timeout: 15000,
                        push: false,
                        replace: false
                    });
                }
            });".'$(document).on("ready pjax:success", function(){$("[data-toggle=\'tooltip\']").tooltip()})', View::POS_READY);
        //        }
        //        else {
        //            $this->registerJs("
        //            var url = '/documenti/hierarchical-documents/render-hierarchical-documents-widget-befe?parentId=' + $parentId + '&search=' + $('#search').val();
        //            $('#search').on('keyup', function(event){
        //            console.log(event.keyCode);
        //                if(event.keyCode === 13){
        //                    $.pjax.reload({
        //                        container: '#$toRefreshSectionId',
        //                        url: url + $(this).val(),
        //                        enablePushState: false,
        //                        timeout: 15000,
        //                    });
        //                }
        //            });", \yii\web\View::POS_READY);
        //        }

        ?>

        <div class="row mb-3">
            <div class="col-xs-12 hierarchical-documents-community-filter">
                <input type="search" id="search" name="search" value="<?= $widget->search ?>" placeholder="<?=
                AmosDocumenti::t('amosdocumenti', 'Filtra documenti e cartelle')
                ?>">
            </div>
        </div>
        <div class="row hierarchical-widget">
            <?php if ($widget->enableSideBar == true) { ?>
            <div id="hierarchical-widget-address-bar-id" class="hierarchical-widget-address-bar col-sm-4 col-xs-12 mx-2 mx-md-0">
                <?php } else { ?>
                <div id="hierarchical-widget-address-bar-id" class="hierarchical-widget-address-bar col-xs-12 mb-3">
                    <?php } ?>
                    <?php echo $widget->getNavBar() ?>
                </div>
                <?php if ($widget->enableSideBar == true) { ?>
                <div id="hierarchical-widget-list-id" class="col-sm-8 col-xs-12 hierarchical-widget-list">
                    <?php } else { ?>
                    <div id="hierarchical-widget-list-id" class="col-xs-12 hierarchical-widget-list">
                        <?php } ?>
                        <div class="row" role="listbox" data-role="list-view">
                            <?php if ($dataProviderFolders->count > 0) {
                                ?>
                                <?php foreach ($dataProviderFolders->getModels() as $modelF) {
                                    $mainDocument = $modelF->documentMainFile;
                                    $relationQuery = $modelF->getCreatedUserProfile();
                                    $relationCreated = CachedActiveQuery::instance($relationQuery);
                                    $relationCreated->cache(60);
                                    $createdUserProfile = $relationCreated->one(); ?>

                                    <?=
                                    ItemDocumentCardWidget::widget(
                                        [
                                            'model' => $modelF,
                                            'type' => 'folder',
                                            'date' => $modelF->data_pubblicazione,
                                            'nameSurname' => $createdUserProfile->nomeCognome,
                                            'actionModify' => '/documenti/documenti/update?id=' . $modelF->id,
                                            'title' => $modelF->titolo,
                                            'fileUrl' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::getLinkOptions($modelF),
                                            'actionView' => '/documenti/documenti/view?id=' . $modelF->id,
                                            'widthColumn' => ($widget->enableSideBar == true) ? 'col-sm-6 col-xs-12' : 'col-md-4 col-sm-6',
                                        ])
                                    ?>
                                <?php }
                                ?>
                            <?php } ?>
                            <?php if ($dataProviderDocuments->count > 0) { ?>
                                <?php
                                foreach ($dataProviderDocuments->getModels() as $modelD) {
                                    /** @var Documenti $modelD */
                                    /** @var File $mainDocument */
                                    $mainDocument = $modelD->documentMainFile;
                                    $relationQuery = $modelD->getCreatedUserProfile();
                                    $relationCreated = CachedActiveQuery::instance($relationQuery);
                                    $relationCreated->cache(60);
                                    $createdUserProfile = $relationCreated->one();
                                    ?>
                                    <?=
                                    ItemDocumentCardWidget::widget(
                                        [
                                            'model' => $modelD,
                                            'type' => (!empty($mainDocument) ? $mainDocument->type : null),
                                            'size' => (!empty($mainDocument) ? $mainDocument->formattedSize : null),
                                            'actionModify' => '/documenti/documenti/update?id=' . $modelD->id,
                                            'date' => $modelD->data_pubblicazione,
                                            'nameSurname' => $createdUserProfile->nomeCognome,
                                            'fileName' => (!empty($mainDocument) ? $mainDocument->name : ''),
                                            'allegatiNum' => $modelD->getFilesByAttributeName('documentAttachments')->count(),
                                            'title' => $modelD->titolo,
                                            'actionView' => '/documenti/documenti/view?id=' . $modelD->id,
                                            'fileUrl' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::getLinkOptions($modelD),
                                            'link_document' => $modelD->link_document,
                                            'widthColumn' => ($widget->enableSideBar == true) ? 'col-sm-6 col-xs-12' : 'col-md-4 col-sm-6',
                                        ])
                                    ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
