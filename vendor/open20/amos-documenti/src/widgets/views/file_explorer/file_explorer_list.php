<?php

use Yii;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\core\forms\ContextMenuWidget;
use yii\widgets\LinkPager;
use yii\data\Pagination;
use open20\amos\core\record\CachedActiveQuery;
use open20\amos\admin\AmosAdmin;

$moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());

if($moduleDocumenti->enableAnnualArchiving){
    $filter_archiving = [];
    foreach($files as $model){
        $archivio = $model->getArchiving();
        $filter_archiving[] = $archivio;
    }
    
    $filter_archiving = array_filter($filter_archiving, function($valore) {
        return $valore !== '';
    });
    
    rsort($filter_archiving);
    $zeroIndex = array_search(0, $filter_archiving);
    if($zeroIndex !== false){
        array_splice($filter_archiving, $zeroIndex, 1);      
        array_unshift($filter_archiving, 0); 
    }
    $filter_archiving = array_unique($filter_archiving);
}

?>

<?php if(count($files) > 0):  ?>
<div class="prelist-content d-flex border-bottom py-4 flexbox <?= !$moduleDocumenti->enableAnnualArchiving ? 'justify-content-end' : '' ?>">
            
    <?php if($moduleDocumenti->enableAnnualArchiving && !empty($filter_archiving)): ?>
    <div class="flexbox archiving-search">
        <b><?= AmosDocumenti::tHtml('amosdocumenti', 'Filtra per:') ?></b>
               
        <?php foreach($filter_archiving as $year): ?>
            <span class="badge badge-light ml-2 mr-2 pl-4 pr-4" onclick="archiveFilter(this,<?= $year ?>)"><?= $year == 0 ? AmosDocumenti::tHtml('amosdocumenti', '#in_corso') : AmosDocumenti::tHtml('amosdocumenti', '#archiviato',['anno'=>$year]) ?></span>
        <?php endforeach; ?>

        <span class="badge badge-danger ml-2 mr-2 pl-4 pr-4" style="display:none" onclick="archiveFilter(this,-1)"><?= AmosDocumenti::tHtml('amosdocumenti', 'Rimuovi filtro') ?></span>
    </div>
    <?php endif; ?>

    <div class="tot-elements">
        <span class="text-normal"><?= AmosDocumenti::tHtml('amosdocumenti','#tot_doc_found',[count($files)]) ?></span>
        <?php if(count($files) > 1): ?>
            <span class="text-singular" style="display:none"><?= AmosDocumenti::tHtml('amosdocumenti','#tot_doc_found',[1]) ?></span>
        <?php endif ?>
    </div>
</div>
<?php endif;  ?>

<div class="files-list">
    
    <div class="document-item-container border-bottom py-4 w-100 no-doc" style="<?= count($files) > 0 ? 'display:none' : '' ?>">
        <?= AmosDocumenti::tHtml('amosdocumenti', 'Nessun documento') ?>
    </div>
  
    <?php foreach($files as $model): 
         
        $relationQuery = $model->getCreatedUserProfile();
        $relationCreated = CachedActiveQuery::instance($relationQuery);
        $relationCreated->cache(60);
        $createdUserProfile = $relationCreated->one();
        
        $document = $model->getDocumentMainFile();
         
        $category = $model->documentiCategorie;
        $nameCategory = !empty($category) ? $category->titolo : null;
    ?>    

    <div class="item" data-archivio="<?= $model->getArchiving() ?>">
    
    <?= $this->render('@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-item',
            [
                'title' => $model->titolo,
                'nameSurname' => $createdUserProfile->nomeCognome,
                'urlAvatar' => '/'.AmosAdmin::getModuleName().'/user-profile/view?id='.$createdUserProfile->id,
                'imageAvatar' => $createdUserProfile->getAvatarUrl('table_small'),
                'url' => '/documenti/documenti/view?id=' . $model->id,
                'fileName' => $document->name,
                'nameFile' =>  $document->name,
                'fileUrl' => $fileUrl,
                'type' => is_null($document) && empty($model->link_document) ? 'doc-text' : $document->type,
                'typeFolder' => $model->is_folder,
                'description' =>  $model->descrizione_breve,
                'size' => round($model->documentMainFile->size / 1024, 0),
                'date' => $model->data_pubblicazione,
                'model' => $model,
                'actionModify' => '/documenti/documenti/update?id='.$model->id,
                'actionDelete' => 'documenti/documenti/delete?id='.$model->id,
                'category' => $nameCategory,
            ]
          );
    ?>
    
   </div>
    
    <?php endforeach; ?>
    
</div>

<?php if(count($files) > 0):  ?>
    <div class="pagination-container d-flex mt-4">
        <ul class="pagination"></ul>     
    </div>
 <?php endif;  ?>  