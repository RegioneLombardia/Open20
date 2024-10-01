<?php

use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\CachedActiveQuery;

$model->usePrettyUrl = true;

$relationQuery      = $model->getCreatedUserProfile();
$relationCreated    = CachedActiveQuery::instance($relationQuery);
$relationCreated->cache(60);
$createdUserProfile = $relationCreated->one();

$image = null;
foreach ($viewFields as $field) {
    if ($field->type == 'IMAGE') {
        $image = (!is_null($model[$field->name])) ? $model[$field->name]->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';
    } else if (!empty($field['type']) && $field['type'] == 'IMAGE') {
        $image = (!is_null($model[$field['name']])) ? $model[$field['name']]->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';
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
    $customCategoryClass = 'mb-1 px-1' . ' ' . 'custom-category-bg-' . str_replace(' ', '-', strtolower($category));
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

<div class="container-news-focus col-12 ">
    <div class="angoli-cornice">
        <div class="content-focus" style="background-image: url('<?= $image ?>') ;">
            <div class="body-focus">
                <div>
                    <span class="cat-focus"><?= $category ?></span>
                </div>
                
                <div class="tit-focus h4"><?= $model->title ?></div>
                <div class="mt-auto text-right">
                    <a class="btn btn-outline-tertiary btn-icon bg-white" href="<?= $url ?>" title="<?= Yii::t('amoscore', 'Leggi tutto'); ?>">
                        <span class="mdi mdi-plus-circle-outline mr-2"></span>
                        <span><?= Yii::t('amoscore', 'Leggi tutto'); ?></span>
                    </a>
                </div>
            </div>


        </div>
    </div>
</div>