<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\parts
 * @category   CategoryName
 */

use open20\amos\core\controllers\CrudController;
use yii\helpers\Inflector;


/** @var \yii\web\View $this */


?>
<div class="toolbar-wrapper">
    <div class="tools-container d-flex">
        <div class="sorting-tool">
            <!-- TODO recupero da amos-layout layouts/parts/chenge-view -->
        </div>

        <div class="download-tool">
            <!-- TODO recupero da amos-layout layouts/parts/chenge-view -->
        </div>

        <div class="search-tool">
            <?php
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
            ?>
            <?php if ($paramsSearch) : ?>
                <?php if ($searchActive) : ?>
                    <button class="btn btn-tertiary-outline" type="button" data-toggle="collapse" data-target="<?= $searchAreaTargetId ?>" aria-expanded="true" aria-controls="collapseSorting">
                        <svg class="icon">
                            <use xlink:href="<?= $current->baseUrl ?>/sprite/material-sprite.svg#magnify"></use>
                        </svg>
                        <span class="sr-only">Ricerca avanzata</span>
                    </button>
                <?php else : ?>
                    <button class="btn btn-tertiary-outline" type="button" data-toggle="collapse" data-target="<?= $searchAreaTargetId ?>" aria-expanded="false" aria-controls="collapseSorting">
                        <svg class="icon">
                            <use xlink:href="<?= $current->baseUrl ?>/sprite/material-sprite.svg#magnify"></use>
                        </svg>
                        <span class="sr-only">Ricerca avanzata</span>
                    </button>
                <?php endif ?>
            <?php endif ?>
        </div>

        <div class="changeview-tool">
            <!-- TODO recupero da amos-layout layouts/parts/chenge-view (in core già presente ChangeViewbs4 TO CHECK)-->
        </div>

    </div>
</div>