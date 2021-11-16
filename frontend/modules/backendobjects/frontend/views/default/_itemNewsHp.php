<?php
use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;

$image = (!is_null($model->newsImage)) ? $model->newsImage->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';

$hideCategory = false;
$newsCategories = NewsUtility::getNewsCategories();
if (count($newsCategories) == 1) {
  $hideCategory = true;
} else {
  $category = $model->newsCategorie->titolo;
}
?>


<?=
  $this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
    [
      'category' => $category,
      'hideCategory' => $hideCategory,
      'image' => $image,
      'date' => $model->data_pubblicazione,
      'title' => $model->getTitle(),
      'subtitle' => $model->sottotitolo,
      'description' => $model->descrizione_breve,
      'url' => Yii::$app->getModule('backendobjects')::getSeoUrl($model->getPrettyUrl(), $blockItemId),
      'nameSurname' => $model->createdUserProfile->nomeCognome,
      'widthColumn' =>'',
      'removeLink' => CurrentUser::isPlatformGuest(),
      'showAvatar' => false
    ]
  );
?>