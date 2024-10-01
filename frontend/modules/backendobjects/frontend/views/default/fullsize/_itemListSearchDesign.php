<?php

use app\modules\backendobjects\frontend\Module;

$route = "#";
$cls = $model->className();
$module = Module::getInstance();

$realModel = $cls::findOne($model->id);
if (!is_null($realModel)) {
    if (property_exists($realModel, 'usePrettyUrl')) {
        $realModel->usePrettyUrl = true;
    }
    if (strpos($cls, 'NavItem') !== false) {
        $nav = $realModel->nav;
        if (!empty($nav) && $nav->is_offline) {
            $route = '/cms-page-preview?itemId=' . $realModel->id . '&version=' . $realModel->nav_item_type_id;
        }
    }
    if ($route == '#') {
        $route = $realModel->getFullViewUrl();
    }
}


if (method_exists($realModel, 'getPublicatedFrom')) {
    $date = $realModel->getPublicatedFrom();
    if (!empty($date)) {
        $publicationDate = \Yii::$app->formatter->asDate($date);
    }
} else if ($realModel instanceof \luya\cms\models\NavItem) {
    $publicationDate = \Yii::$app->formatter->asDate($realModel->timestamp_create);
} else if ($realModel instanceof \open20\amos\core\record\Record) {
    $publicationDate = \Yii::$app->formatter->asDate($realModel->created_at);
}
if (!empty($realModel)) {
    $type = $realModel->getGrammar()->getModelLabel();
    if ($type == 'Pagine pubbliche') {
        $type = 'Pagine';
    }
}
?>

<?php

if (!empty($realModel)) {
    echo $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-search-results-item',
            [
                'url' => $route,
                'type' => $type,
                'titleLink' => '',
                'title' => $realModel->title,
                'description' => $realModel->getDescription(200),
                'publicationDate' => $publicationDate
            ]
    );
}