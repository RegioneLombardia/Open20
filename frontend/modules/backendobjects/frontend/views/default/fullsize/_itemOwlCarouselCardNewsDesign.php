<?php

use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\CachedActiveQuery;

$relationQuery = $model->getCreatedUserProfile();
$relationCreated = CachedActiveQuery::instance($relationQuery);
$relationCreated->cache(60);
$createdUserProfile = $relationCreated->one();
//$model->usePrettyUrl = true;

$image = null;
foreach ($viewFields as $field) {
    if ($field->type == 'IMAGE') {
        $image = (!is_null($model[$field->name])) ? $model[$field->name]->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';
    } else if (!empty($field['type']) && $field['type'] == 'IMAGE') {
        $image = (!is_null($model[$field['name']])) ? $model[$field['name']]->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';
    }
}

$hideCategory   = false;
$newsCategories = NewsUtility::getAllNewsCategories();
if ($newsCategories->count() == 1) {
    $hideCategory = false;
} else {
    $category = $model->newsCategorie->titolo;
    $customCategoryClass = 'mb-1 px-1 ' . 'custom-category-bg-' . str_replace(' ', '-', strtolower($category));
    $colorBgCategory = $model->newsCategorie->color_background;
    $colorTextCategory = $model->newsCategorie->color_text;
}

$url = '';
if ($detailPage) {
    $url = Yii::$app->getModule('backendobjects')::getSeoUrl($model->getPrettyUrl(), $blockItemId);
} else {
    $url = $model->getFullViewUrl();
}
?>
<div class="item-news-evidenza">
    <div class="content-news" style="background-image: url('<?= $image ?>') ;">
        <div class="body-news">
            <div>
                <span class="cat-news"><?= $category ?></span>
            </div>

            <div class="tit-news-evidenza"><?= $model->title ?></div>
            <div class="data-news-evidenza"><?= \open20\design\utility\DateUtility::getDate($model->data_pubblicazione, 'dd MMMM yyyy') ?></div>

            <a class="small text-uppercase text-white read-more" href="<?= $url ?>" title="<?= Yii::t('amoscore', 'Leggi'); ?>">
                <span><span><?= Yii::t('amoscore', 'Leggi'); ?></span></span>
            </a>

        </div>


    </div>
</div>