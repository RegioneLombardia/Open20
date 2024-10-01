<?php

use open20\elasticsearch\Module;

$route  = "#";
$cls    = $model->className();

$realModel = $cls::findOne($model->id);
if (!is_null($realModel)) {
    if (property_exists($realModel, 'usePrettyUrl')) {
        $realModel->usePrettyUrl = true;
    }
    $route = $realModel->getFullViewUrl();
}
?>

<div class="single-result">
    <a href="<?= $route ?>" title="<?= Module::t('amoselasticsearch', 'Visualizza la pagina') . ' ' . $realModel->title ?>">
        <div>
            <p>
                <span class="label label-secondary mr-1">
                    <?= $realModel->getGrammar()->getModelLabel() ?>
                </span>
                <strong><?= $realModel->title ?></strong>
            </p>
            <p>
                <small>
                    <?= $realModel->getDescription(200) ?>
                </small>
            </p>
        </div>
        <div>
            <span class="mdi mdi-arrow-right"></span>
        </div>
    </a>
</div>