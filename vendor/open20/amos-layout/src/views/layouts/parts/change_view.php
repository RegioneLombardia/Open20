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
use open20\amos\core\forms\ContentSettingsMenuWidget;
use open20\amos\core\forms\CreateNewButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\ChangeView;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\slideshow\models\Slideshow;
use open20\amos\core\forms\editors\ExportMenu;
use yii\base\Event;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\helpers\Inflector;
use yii\web\View;

/** @var \yii\web\View $this */

$createNewWidgetConfig = ['btnClasses' => 'btn btn-primary'];
if (isset($this->params['createNewBtnParams']) && !is_null($this->params['createNewBtnParams']) && is_array($this->params['createNewBtnParams'])) {
    $createNewWidgetConfig = $this->params['createNewBtnParams'];
}

?>
<div class="container-change-view">
    <div class="btn-tools-container flexbox">
        <!-- < ?php if (isset($this->params['forceCreateNewButtonWidget']) || Yii::$app->controller->can('CREATE')): ?>
            < ?= CreateNewButtonWidget::widget($createNewWidgetConfig); ?>
        < ?php endif; ?> -->

        <?php if (isset($this->params['additionalButtons'])): ?>
            <?= \open20\amos\core\forms\ChangeViewButtonWidget::widget($this->params['additionalButtons']); ?>
        <?php endif; ?>
        <div class="tools-right">
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
                if ($searchActive) {
                    echo Html::tag(
                        'div',
                        AmosIcons::show(
                            'search',
                            [
                                'class' => 'btn btn-secondary show-hide-element active',
                                'data-toggle-element' => 'form-search',
                                'title' => 'Cerca'
                            ]
                        ),
                        [
                            'class' => 'btn-group'
                        ]
                    );
                } else {
                    if (!isset($this->params['disable-search']) || (isset($this->params['disable-search']) && $this->params['disable-search'] == false)) {
                        echo Html::tag(
                            'div',
                            AmosIcons::show(
                                'search',
                                [
                                    'class' => 'btn btn-secondary show-hide-element',
                                    'data-toggle-element' => 'form-search',
                                    'title' => 'Cerca'
                                ]
                            ),
                            [
                                'class' => 'btn-group'
                            ]
                        );
                    }
                }
            }

            ?>
            <?php
            //ORDER ENABLED?
            if (
                isset(\Yii::$app->controller->module)
                &&
                isset(\Yii::$app->controller->module->params)
                &&
                isset(\Yii::$app->controller->module->params['orderParams'])
                &&
                isset(\Yii::$app->controller->module->params['orderParams'][\Yii::$app->controller->id])
                &&
                isset(\Yii::$app->controller->module->params['orderParams'][\Yii::$app->controller->id]['enable'])
                &&
                \Yii::$app->controller->module->params['orderParams'][\Yii::$app->controller->id]['enable']
            ) {
                echo Html::tag(
                    'div',
                    AmosIcons::show(
                        'unfold-more',
                        [
                            'class' => 'btn btn-secondary show-hide-element',
                            'data-toggle-element' => 'form-order',
                            'title' => 'Cambia ordinamento'
                        ]
                    ),
                    [
                        'class' => 'btn-group'
                    ]
                );
            }

            ?>

            <?php
            $changeViewType = true;
            if (
                isset(\Yii::$app->controller->module)
                &&
                isset(\Yii::$app->controller->module->params)
                &&
                isset(\Yii::$app->controller->module->params['hideChangeViewType'])
                &&
                \Yii::$app->controller->module->params['hideChangeViewType']
            ) {
                $changeViewType = false;
            }
            if ($changeViewType) {
                echo ChangeView::widget([
                    'dropdown' => Yii::$app->controller->getCurrentView(),
                    'views' => Yii::$app->controller->getAvailableViews(),
                ]);
            }
            ?>

            <?php
            //DOWNLOAD ENABLED?
            if (isset(Yii::$app->request->queryParams['download'])) {
                echo Html::tag('div', '', ['id' => 'change-view-download-btn', 'class' => 'btn-group hidden']);
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
                                'selectedColumns' => array_keys($columns),
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
                                    'class' => 'btn btn-secondary',
                                    'icon' => AmosIcons::show('download'),
                                    'title' => 'Scarica'
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
            ?>
            <?= ContentSettingsMenuWidget::widget(); ?>
        </div>
    </div>
</div>