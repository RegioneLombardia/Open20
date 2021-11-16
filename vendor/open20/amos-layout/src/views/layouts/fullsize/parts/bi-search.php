<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\Inflector;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\Html;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \yii\web\View $this */
?>
<?php if (!empty($this->params['enableLayoutList']) && $this->params['enableLayoutList'] == true) { ?>

<div class="container-search">

    <?php

    //SEARCH ENABLED?
    $paramsSearch = false;
    $searchActive = false;
    if (
        isset(\Yii::$app->controller->module)
        &&
        isset(\Yii::$app->controller->module->params)
        &&
        isset(\Yii::$app->controller->module->params['searchParams'])
        &&
        isset(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id])
        &&
        (
            (is_array(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id])
                &&
                isset(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]['enable'])
                &&
                \Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]['enable'])
            ||
            (is_bool(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id])
                &&
                \Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]))
    ) {
        //check if the controller is istance of CrucController to retrieve the setted searchModel
        if (\Yii::$app->controller instanceof CrudController) {
            //retrieve the form name of current modelSearch
            $modelSearch = \Yii::$app->controller->getModelSearch();
            $classSearch = $modelSearch->formName();
        } else {
            //use the previous mode to calculate the modelSearch name
            $classSearch = Inflector::id2camel(\Yii::$app->controller->id, '-') . 'Search';
        }

        $paramsSearch = \Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id];
        if (
            isset(Yii::$app->request->queryParams[$classSearch])
            &&
            isset(Yii::$app->request->queryParams['enableSearch'])
            &&
            Yii::$app->request->queryParams['enableSearch']
        ) {
            $searchActive = TRUE;
        }
    }
    if ($paramsSearch) {

    ?>
        <a class="btn btn-outline-secondary btn-sm btn-icon mb-3" data-toggle="collapse" href="#form-search-events" role="button" aria-expanded="false" aria-controls="form-search-events">
            <svg class="icon">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_search"></use>
            </svg>
            <span><?= \Yii::t('app','Cerca')?></span>
        </a>

    <?php
    }
    ?>

</div>
<?php  } ?>