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
    $hideCategory = true;
} else {
    $category = $model->newsCategorie->titolo;
    $customCategoryClass = 'mb-1 px-1 ' . 'custom-category-bg-' . str_replace(' ', '-', strtolower($category));
    $colorBgCategory = $model->newsCategorie->color_background;
    $colorTextCategory = $model->newsCategorie->color_text;
}
?>

<div class="it-single-slide-wrapper">
    <?=
        $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
            [
                'category' => $category,
                'hideCategory' => $hideCategory,
                'customCategoryClass' => $customCategoryClass,
                'colorBgCategory' => $colorBgCategory,
                'colorTextCategory' => $colorTextCategory,
                'image' => $image,
                'date' => $model->data_pubblicazione,
                'title' => $model->getTitle(),
                'abstract' => $model->descrizione_breve,
                'description' => $model->sottotitolo,
                'url' => $model->getFullViewUrl(),
                'nameSurname' => $createdUserProfile->nomeCognome,
                'imageAvatar' => $createdUserProfile->getAvatarWebUrl('table_small'),
                'urlAvatar' => '/' . AmosAdmin::getModuleName() . '/user-profile/view?id=' . $createdUserProfile->id,
                'additionalInfoAvatar' => (!empty($createdUserProfile->prevalentPartnership) ? $createdUserProfile->prevalentPartnership->name : ''),
                'contentScopesAvatar' => \open20\amos\core\utilities\CwhUtility::getTargetsString($model),
                'model' => $model,
                'actionModify' => '/news/news/update?id=' . $model->id,
                'actionDelete' => '/news/news/delete?id=' . $model->id,
                'widthColumn' => 'col-12',
                'removeLink' => CurrentUser::isPlatformGuest(),
                'itemDirection' => 'row'
            ]
        );
    ?>
</div>