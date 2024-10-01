<?php

use open20\amos\core\utilities\CurrentUser;
use Yii;

$model->usePrettyUrl = true;

$image = null;

if (isset($model) && !is_null($model->logoOrganization)) {
    $image = $model->logoOrganization->getUrl('original', [
        'class' => 'img-responsive'
    ]);
} else {
    $image = '/img/img_default.jpg';
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
    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
    [
    'title' => $model->getTitle(),
    'url' => $url,
    'actionModify' => '/organizzazioni/profilo/update?id='.$model->id,
    'actionDelete' => '/organizzazioni/profilo/delete?id='.$model->id,
    'removeLink' => CurrentUser::isPlatformGuest(),
    'image' => $image,
    'model' => $model
    ]
);
?>