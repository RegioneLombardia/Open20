<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\parts
 * @category   CategoryName
 */

use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\CreateNewButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\ChangeView;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\slideshow\models\Slideshow;
use kartik\export\ExportMenu;
use yii\base\Event;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\web\View;

/** @var \yii\web\View $this */

$createNewWidgetConfig = [];
if (isset($createNewBtnParams) && !is_null($createNewBtnParams) && is_array($createNewBtnParams)) {
    $createNewWidgetConfig = $createNewBtnParams;
}
?>
<div class="container-change-view ">
    <div class="btn-tools-container">
        <?= CreateNewButtonWidget::widget($createNewWidgetConfig); ?>

        <div class="tools-right">
            <?php
            //ORDER ENABLED?
            if (isset($this->params['orderParams'])) {
                echo AmosIcons::show('unfold-more', [
                    'class' => 'btn btn-tools-primary show-hide-element',
                    'data-toggle-element' => 'form-order',
                ]);
            }

            //INTRODUCTION ENABLED?
            if (isset($this->params['introductionParams']) &&
                Yii::$app->getModule('slideshow') &&
                isset(Yii::$app->params['slideshow']) &&
                Yii::$app->params['slideshow'] === true
            ) {
                $slideshow = new Slideshow;
                $route = "/" . Yii::$app->request->getPathInfo();
                $idSlideshow = $slideshow->hasSlideshow($route);
                if ($idSlideshow) {
                    echo AmosIcons::show('triangle-up', [
                        'class' => 'btn btn-tools-primary rotate-right-90',
                        'id' => 'plugin-introduction-slideshow'
                    ]);
                    $js = "
                            $('#plugin-introduction-slideshow').on('click', function (event) {
                                $('#amos-slideshow').modal('show');
                            });
                        ";
                    $this->registerJs($js);
                }
            }

            //DOWNLOAD ENABLED?
            if (isset(Yii::$app->request->queryParams['download'])) {
                echo Html::tag('div', '', ['id' => 'change-view-download-btn', 'class' => 'pull-left m-r-3 hidden']);
                Event::on(View::className(), View::EVENT_END_BODY, function ($event) {
                    $controller = \Yii::$app->controller;
                    if ($controller instanceof CrudController) {
                        $columns = $controller->getGridViewColumns();
                        if (is_array($columns)) {
                            $actionColumnsIndex = false;
                            // Setting dataProvider as variable. It can be overwritten if necessary.
                            $dataProvider = $controller->getDataProvider();
                            foreach ($columns as $index => $column) {
                                if (is_array($column) && isset($column['class']) && ($column['class'] == ActionColumn::className())) {
                                    $actionColumnsIndex = $index;
                                }
                            }

                            $exportConfigExists = isset($controller->exportConfig) && is_array($controller->exportConfig);

                            if (
                                $exportConfigExists &&
                                array_key_exists('dataProvider', $controller->exportConfig) &&
                                $controller->exportConfig['dataProvider'] instanceof DataProviderInterface
                            ) {
                                $dataProvider = $controller->exportConfig['dataProvider'];
                            }


                            /** Create a different dataProvider for the export menu */
                            /** @var \yii\data\ActiveDataProvider $exportAllDataProvider */
                            if (empty(\Yii::$app->params['disableExportAll']) || !empty(\Yii::$app->params['disableExportAll'] && \Yii::$app->params['disableExportAll'] == false)) {
                                $confAllDataProv = [
                                    'query' => $dataProvider->query,
                                    'pagination' => false
                                ];
                                if (!empty($controller->getDataProvider()->getSort())) {
                                    $confAllDataProv['sort'] = $dataProvider->getSort();
                                }
                                $exportAllDataProvider = new ActiveDataProvider($confAllDataProv);
                                $exportDataProvider = $exportAllDataProvider;
                            } else {
                                $exportDataProvider = $dataProvider;
                            }

                            $exportMenuParams = [
                                'dataProvider' => $exportDataProvider,
                                'columns' => $columns,
                                'showColumnSelector' => false,
                                'showConfirmAlert' => false,
                                'filename' => Yii::$app->view->title,
                                'clearBuffers' => true,
                                'exportConfig' => [
                                    ExportMenu::FORMAT_HTML => false,
                                    ExportMenu::FORMAT_CSV => false,
                                    ExportMenu::FORMAT_TEXT => false,
                                    ExportMenu::FORMAT_PDF => false
                                ],
                                'noExportColumns' => [
                                    $actionColumnsIndex
                                ],
                                'dropdownOptions' => [
                                    'class' => 'btn btn-tools-primary',
                                    'icon' => AmosIcons::show('download')
                                ]
                            ];

                            if (
                                $exportConfigExists &&
                                array_key_exists('emptyText', $controller->exportConfig) &&
                                ($controller->exportConfig['emptyText'] != '')
                            ) {
                                $exportMenuParams['emptyText'] = $controller->exportConfig['emptyText'];
                            }

                            // Renders a export dropdown menu
                            echo Html::beginTag('div', ['id' => 'change-view-dropdown-download', 'class' => 'hidden']);
                            echo ExportMenu::widget($exportMenuParams);
                            echo Html::endTag('div');
                        }
                    }
                });


                $js = "
                    $('#change-view-dropdown-download').appendTo('#change-view-download-btn').removeClass('hidden');
                    $('#change-view-download-btn').removeClass('hidden');
                    ";
                $this->registerJs($js, View::POS_READY);
            }

            //SEARCH ENABLED?
            $paramsSearch = false;
            $searchActive = false;
            if (isset($this->params['searchParams'])) {
                $paramsSearch = $this->params['searchParams'];
                if (isset($paramsSearch['searchClass'])) {
                    $classSearch = $paramsSearch['searchClass'];
                    if (
                        isset(Yii::$app->request->queryParams[$classSearch])
                        &&
                        isset(Yii::$app->request->queryParams['enableSearch'])
                        &&
                        Yii::$app->request->queryParams['enableSearch']
                    ) {
                        $searchActive = true;
                    }
                }
            }
            if ($paramsSearch) {
                if ($searchActive) {
                    echo AmosIcons::show('search', [
                        'class' => 'btn btn-tools-primary show-hide-element active',
                        'data-toggle-element' => 'form-search'
                    ]);
                } else {
                    echo AmosIcons::show('search', [
                        'class' => 'btn btn-tools-primary show-hide-element',
                        'data-toggle-element' => 'form-search'
                    ]);
                }
            }
            ?>
            <?= ChangeView::widget([
                'dropdown' => $currentView,
                'views' => $availableViews,
            ]); ?>
        </div>
    </div>
</div>
