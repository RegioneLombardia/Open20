<?php
use open20\amos\core\utilities\CurrentUser;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\CachedActiveQuery;

$relationQuery = $model->getCreatedUserProfile();
$relationCreated = CachedActiveQuery::instance($relationQuery);
$relationCreated->cache(60);
$createdUserProfile = $relationCreated->one();
//$model->usePrettyUrl = true;

$documentMainFile = $model->getDocumentMainFile();
$fileUrl = '';
if ($documentMainFile != null) {
  //$fileUrl = '/attachments/file/download?id=' . $documentMainFile->id . '&hash=' . $documentMainFile->hash;
  $fileUrl =  $model->documentMainFile->getUrl();
}
if (!empty($model->link_document)) {
  $fileUrl = $model->link_document;
}

$category = $model->documentiCategorie;
$nameCategory = null;
if(!empty($category)){
  $nameCategory= $category->titolo;
}
?>


<?=
  $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-item',
    [
      'title' => $model->titolo,
      'nameSurname' => $createdUserProfile->nomeCognome,
      'urlAvatar' => '/'.AmosAdmin::getModuleName().'/user-profile/view?id='.$createdUserProfile->id,
      'imageAvatar' => $createdUserProfile->getAvatarUrl('table_small'),
      'url' => '/documenti/documenti/view?id=' . $model->id,
      'fileName' => $documentMainFile->name,
      'nameFile' =>  $documentMainFile->name,
      'fileUrl' => $fileUrl,
      'type' => $documentMainFile->type,
      'typeFolder' => $model->is_folder,
      'description' =>  $model->descrizione_breve,
      'size' => round($model->documentMainFile->size / 1024, 0),
      'date' => $model->created_at,
      'model' => $model,
      'actionModify' => '/documenti/documenti/update?id='.$model->id,
      'actionDelete' => 'documenti/documenti/delete?id='.$model->id,
      'category' => $nameCategory,
    ]
  );
?>