<?php

use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\CachedActiveQuery;
use Yii;

$model->usePrettyUrl = true;

$relationQuery      = $model->getCreatedUserProfile();
$relationCreated    = CachedActiveQuery::instance($relationQuery);
$relationCreated->cache(60);
$createdUserProfile = $relationCreated->one();

$image = null;
if(!empty($viewFields)){
    foreach ($viewFields as $field) {
        if ($field->type == 'IMAGE') {
            $image = (!is_null($model[$field->name])) ? $model[$field->name]->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';
        } else if (!empty($field['type']) && $field['type'] == 'IMAGE') {
            $image = (!is_null($model[$field['name']])) ? $model[$field['name']]->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';
        }
    }
}else{
    /**PER COMPONENTI CHE NON SONO MODULI  BACKEND*/
    if(!empty($model->newsImage)){
        $image = $model->newsImage->getWebUrl('square_medium', false, true);
    }else{
        $image = '/img/img_default.jpg';
    }

}

$hideCategory        = false;
$hasMany             = NewsUtility::getAllNewsCategories();
$hasManyCache        = CachedActiveQuery::instance($hasMany);
$hasManyCache->cache(60);
$newsCategoriesCount = $hasManyCache->count();
if ($newsCategoriesCount == 1) {
    $hideCategory = true;
} else {
    $hasOne              = $model->getNewsCategorie();
    $hasOneCache         = CachedActiveQuery::instance($hasOne);
    $hasOneCache->cache(60);
    $categoryCache       = $hasOneCache->one();
    $category            = $categoryCache->titolo;
    $customCategoryClass = 'mb-1 px-1'.' '.'custom-category-bg-'.str_replace(' ', '-', strtolower($category));
    $colorBgCategory     = $categoryCache->color_background;
    $colorTextCategory   = $categoryCache->color_text;
}
$url = '';
if ($detailPage) {
    $url = Yii::$app->getModule('backendobjects')::getSeoUrl($model->getPrettyUrl(), $blockItemId);
} else {
    $url = $model->getFullViewUrl();
}
?>

<?=
$this->render(
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news-item-list',
    [
    'category' => $category,
    'hideCategory' => ($hideCategoryFromCMS) ? $hideCategoryFromCMS : $hideCategory,
    'customCategoryClass' => $customCategoryClass,
    'colorBgCategory' => $colorBgCategory,
    'colorTextCategory' => $colorTextCategory,
    'image' => $image,
    'date' => $model->data_pubblicazione,
    'title' => $model->getTitle(),
    'abstract' => $model->getShortDescription(),
    'description' => strip_tags($model->getDescription(true)),
    'url' => $url,
    'nameSurname' => $createdUserProfile->nomeCognome,
    'imageAvatar' => $createdUserProfile->getAvatarWebUrl('table_small'),
    'urlAvatar' => '/'.AmosAdmin::getModuleName().'/user-profile/view?id='.$createdUserProfile->id,
    'additionalInfoAvatar' => (!empty($createdUserProfile->prevalentPartnership) ? $createdUserProfile->prevalentPartnership->name
            : ''),
    'contentScopesAvatar' => \open20\amos\core\utilities\CwhUtility::getTargetsString($model),
    'model' => $model,
    'actionModify' => '/news/news/update?id='.$model->id,
    'actionDelete' => '/news/news/delete?id='.$model->id,
    'widthColumn' => 'col-lg-4 col-md-6',
    'removeLink' => CurrentUser::isPlatformGuest()
    ]
);
?>