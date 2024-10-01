<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\record\CachedActiveQuery;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiHierarchyBefeAsset;
use open20\design\components\ItemDocumentCardWidget;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefe $widget
 * @var string $currentView
 * @var string $toRefreshSectionId
 * @var yii\data\ActiveDataProvider $dataProviderFolders
 * @var yii\data\ActiveDataProvider $dataProviderDocuments
 * @var array $availableViews
 * @var open20\amos\documenti\models\search\DocumentiSearch $filter
 */

ModuleDocumentiHierarchyBefeAsset::register($this);

/** @var AmosDocumenti $moduleDocumenti */
$moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());

?>
<?php
$modelLabel = 'documenti';

$titleSection = AmosDocumenti::t('amosdocumenti', 'Esplora documenti');
$urlLinkAll = '/documenti/documenti/all-documents';
$labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
$titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');

$labelCreate = AmosDocumenti::t('amosdocumenti', 'Nuovo');
$titleCreate = AmosDocumenti::t('amosdocumenti', 'Crea un nuovo documento');
$labelManage = AmosDocumenti::t('amosdocumenti', 'Gestisci');
$titleManage = AmosDocumenti::t('amosdocumenti', 'Gestisci i documenti');
$urlCreate = '/documenti/documenti/create';

$manageLinks = [];
$controller = \open20\amos\documenti\controllers\DocumentiController::class;
if (method_exists($controller, 'getManageLinks')) {
    $manageLinks = $controller::getManageLinks();
}

$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
}
?>
<div class="widget-graphic-cms-bi-less widget-graphic-cms-bi-less-fe card-<?= $modelLabel ?> container">

    <div class="m-r-10">
        <div class="h2 text-uppercase "><?= $titleSection ?></div>
    </div>

    <div class="list-view bg-100 p-4">
        <?php
        //$categories = (!empty($widget->categories) ? '&categories=' . $widget->categories : '');
        Pjax::begin(['id' => $toRefreshSectionId, 'enablePushState' => false, 'timeout' => 15000]);
        $parentId = $widget->parentId;
        //        if (true || empty($parentId)) {

        $href = [
            '/documenti/hierarchical-documents/render-hierarchical-documents-widget-befe'
        ];
        $getJoin = '?';
        if (!empty($widget->categories)) {
            $href['categories'] = $widget->categories;
            $getJoin = '&';
        }
        if (!empty($widget->folder_id)) {
            $href['folder_id'] = $widget->folder_id;
            $getJoin = '&';
        }
        $urlSearch = \Yii::$app->urlManager->createUrl($href);

        $this->registerJs("
            $('[data-toggle=\"tooltip\"]').tooltip();

            var url = '{$urlSearch}{$getJoin}search=';
            $('#search').on('keyup', function(event){

                if(event.keyCode === 13){
                    $.pjax.reload({
                        container: '#$toRefreshSectionId',
                        url: url + $(this).val(),
                        push: false,
                        replace: false,
                        timeout: 15000,
                    });
                }
            });
            
            $('.hierarchical-link').css('cursor', 'pointer');            
            $('.hierarchical-link').on('click', function(event){
                event.preventDefault();
                $.pjax.reload({
                    container: '#$toRefreshSectionId',
                    url: $(this).attr('data-url'),                    
                    push: false,
                    replace: false,
                    timeout: 15000,
                });
            });
            
            $(document).on('pjax:send', function() {
                $('.hierarchical-link').css('cursor', 'wait');  
            });
            $(document).on('pjax:complete', function() {
                $('.hierarchical-link').css('cursor', 'pointer');  
            });
            ", \yii\web\View::POS_READY);
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
            <div class="col-12">

                <input type="search" id="search" name="search" value="<?= $widget->search ?>" placeholder="<?=
                AmosDocumenti::t('amosdocumenti', 'Filtra documenti e cartelle')
                ?>">
            </div>
        </div>
        <div class="row hierarchical-widget">


            <?php if ($widget->enableSideBar == true) { ?>
            <div id="hierarchical-widget-address-bar-id" class="hierarchical-widget-address-bar col-md-4 col-xs-12 mx-2 mx-md-0">
                <?php } else { ?>
                <div id="hierarchical-widget-address-bar-id" class="hierarchical-widget-address-bar col-12 mb-3">
                    <?php } ?>
                    <?php echo $widget->getNavBar() ?>
                </div>
                <?php if ($widget->enableSideBar == true) { ?>
                <div id="hierarchical-widget-list-id" class="col-md-8 col-xs-12 hierarchical-widget-list">
                    <?php } else { ?>
                    <div id="hierarchical-widget-list-id" class="col-12 hierarchical-widget-list">
                        <?php } ?>
                        <div class="row" role="listbox" data-role="list-view">
                            <?php if (($dataProviderFolders->count == 0) && ($dataProviderDocuments->count == 0)) {
                                echo '<div class="col-12">' . AmosDocumenti::t('amosdocumenti', 'Nessun documento') . '</div>';
                            } ?>

                            <?php if ($dataProviderFolders->count > 0) {
                                ?>
                                <?php foreach ($dataProviderFolders->getModels() as $modelF) {
                                    $mainDocument = $modelF->documentMainFile;
                                    $relationQuery = $modelF->getCreatedUserProfile();
                                    $relationCreated = CachedActiveQuery::instance($relationQuery);
                                    $relationCreated->cache(60);
                                    $createdUserProfile = $relationCreated->one();

                                    $actionUrl = \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefe::getLinkOptions($modelF, $widget->categories, $widget->folder_id);
                                    ?>

                                    <?=
                                    ItemDocumentCardWidget::widget(
                                        [
                                            'model' => $modelF,
                                            'type' => 'folder',
                                            'date' => $modelF->data_pubblicazione,
                                            'nameSurname' => $createdUserProfile->nomeCognome,
                                            'actionModify' => '/documenti/documenti/update?id=' . $modelF->id,
                                            'title' => $modelF->titolo,
                                            'fileUrl' => $actionUrl,
                                            'actionView' => isset($actionUrl['href']) ? $actionUrl['href'] : '/documenti/documenti/view?id=' . $modelF->id,
                                            'widthColumn' => ($widget->enableSideBar == true) ? 'col-sm-6 col-12' : 'col-md-4 col-sm-6',
                                        ])
                                    ?>
                                <?php }
                                ?>
                            <?php } ?>
                            <?php
                            if ($dataProviderDocuments->count > 0) { ?>
                                <?php
                                foreach ($dataProviderDocuments->getModels() as $modelD) {
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
                                            'fileUrl' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefe::getLinkOptions($modelD),
                                            'link_document' => $modelD->link_document,
                                            'widthColumn' => ($widget->enableSideBar == true) ? 'col-sm-6 col-12' : 'col-md-4 col-sm-6',
                                        ])
                                    ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
