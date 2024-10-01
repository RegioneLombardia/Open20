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
use yii\helpers\Url;
use yii\web\View;
use open20\amos\layout\Module;

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

        <?php if (isset($this->params['additionalButtons'])) : ?>
            <?= \open20\amos\core\forms\ChangeViewButtonWidget::widget($this->params['additionalButtons']); ?>
        <?php endif; ?>
        <div class="tools-right">
            <?php
            //SEARCH ENABLED?
            $paramsSearch = false;
            $searchActive = false;
            if (
                isset(\Yii::$app->controller->module) &&
                isset(\Yii::$app->controller->module->params) &&
                isset(\Yii::$app->controller->module->params['searchParams']) &&
                isset(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]) &&
                (
                    (is_array(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]) &&
                        isset(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]['enable']) &&
                        \Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]['enable']) ||
                    (is_bool(\Yii::$app->controller->module->params['searchParams'][\Yii::$app->controller->id]) &&
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
                    isset(Yii::$app->request->queryParams[$classSearch]) &&
                    isset(Yii::$app->request->queryParams['enableSearch']) &&
                    Yii::$app->request->queryParams['enableSearch']
                ) {
                    $searchActive = TRUE;
                }

                // bullet filter search
                $countFilterActive = 0;
                $filterActiveClass = 'btn-secondary';
                foreach (Yii::$app->request->queryParams[$classSearch] as $attribute => $val) {
                    if (!empty($val) && $attribute != 'orderAttribute' && $attribute != 'orderType') {
                        if ($attribute == 'tagValues') {
                            if (!in_array(null, $val, true) && !in_array('', $val, true)) {
                                $countFilterActive++;
                            }
                        } else {
                            $countFilterActive++;
                        }
                    }
                }
                $htmlCountFilter = '<span class="m-l-5">' . Module::t('amoslayout', 'Filtri') . '</span>';
                $tooltipText = Module::t('amoslayout', 'Azzera opzioni di ricerca');
                if ($countFilterActive > 0) {
                    $htmlCountFilter = "<span class='m-l-5'>" . Module::t('amoslayout', 'Filtri') . " ($countFilterActive)</span>";
                    $filterActiveClass = 'btn-secondary-outline active-filter';
                    $tooltipText = Module::t('amoslayout', 'Filtri di ricerca applicati: {x}',
                        [
                            'x' => $countFilterActive
                        ]);
                }
            }
            if ($paramsSearch) {
                if ($searchActive) {
                    echo Html::tag(
                        'div',
                        AmosIcons::show(
                            'tune', [
                                // 'class' => '',
                            ]
                        ) . $htmlCountFilter,
                        [
                            'class' => 'btn-group btn show-hide-element active ' . $filterActiveClass,
                            'data-toggle-element' => 'form-search',
                            'title' => $tooltipText,
                            //'data-toggle' => 'tooltip'
                            'tabindex' => 0
                        ]
                    );
                } else {
                    if (!isset($this->params['disable-search']) || (isset($this->params['disable-search']) && $this->params['disable-search']
                            == false)) {
                        echo Html::tag(
                            'div',
                            AmosIcons::show(
                                'tune',
                                [
                                    // 'class' => 'btn btn-secondary show-hide-element ' . $filterActiveClass,
                                    // 'data-toggle-element' => 'form-search',
                                    // 'title' => Module::t('amoslayout', 'Cerca')
                                ]
                            ) . $htmlCountFilter,
                            [
                                'class' => 'btn-group btn show-hide-element ' . $filterActiveClass,
                                'data-toggle-element' => 'form-search',
                                'title' => $tooltipText,
                                //'data-toggle' => 'tooltip'
                                'tabindex' => 0
                            ]
                        );

                    }
                }
            }
            ?>
            <?php
            //ORDER ENABLED?
            if (
                isset(\Yii::$app->controller->module) &&
                isset(\Yii::$app->controller->module->params) &&
                isset(\Yii::$app->controller->module->params['orderParams']) &&
                isset(\Yii::$app->controller->module->params['orderParams'][\Yii::$app->controller->id]) &&
                isset(\Yii::$app->controller->module->params['orderParams'][\Yii::$app->controller->id]['enable']) &&
                \Yii::$app->controller->module->params['orderParams'][\Yii::$app->controller->id]['enable']
            ) {
                echo Html::tag(
                    'div',
                    AmosIcons::show(
                        'unfold-more',
                        [
                            'class' => 'btn btn-secondary show-hide-element',
                            'data-toggle-element' => 'form-order',
                            'title' => Module::t('amoslayout', 'Cambia ordinamento'),
                            //'data-toggle' => 'tooltip'
                        ]
                    ),
                    [
                        'class' => 'btn-group',
                        'tabindex' => 0
                    ]
                );
            }
            ?>

            <?php
            // VIEW TYPE
            $changeViewType = true;
            if (
                isset(\Yii::$app->controller->module) &&
                isset(\Yii::$app->controller->module->params) &&
                isset(\Yii::$app->controller->module->params['hideChangeViewType']) &&
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
            // MAP
            if (isset(\Yii::$app->controller->module) &&
                isset(\Yii::$app->controller->module->params) &&
                !empty(\Yii::$app->controller->module->params['mapParams'][\Yii::$app->controller->id]['enable']) &&
                !Yii::$app->controller->view->params['hideMapSearch']
            ) { ?>

                <div class="btn-group" role="group">
                    <div id="btnMapView">
                        <?= Html::a(AmosIcons::show('map', []),
                            Url::to([\Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id, 'map' => true]),
                            [
                                'class' => 'btn-group btn btn-secondary show-map-element dropdown-toggle',
                                'data-toggle-element' => 'map-search',
                                'title' => Module::t('amoslayout', 'Punti di interesse'),
                                //'data-toggle' => 'tooltip'
                                'tabindex' => 0
                            ]
                        ); ?>
                    </div>
                </div>
            <?php } ?>

            <?php
            //DOWNLOAD ENABLED?
            if (empty(\Yii::$app->params['hideDownload']) && isset(Yii::$app->request->queryParams['download'])) {
                echo Html::tag('div', '', ['id' => 'change-view-download-btn', 'class' => 'btn-group hidden']);
                Event::on(View::className(), View::EVENT_END_BODY,
                    function ($event) {
                        $controller = \Yii::$app->controller;
                        if ($controller instanceof CrudController) {
                            $columns = $controller->getGridViewColumns();
                            if (is_array($columns)) {
                                $noExportColumns = [];
                                if (isset($controller->exportConfig) && is_array($controller->exportConfig)) {
                                    if (isset($controller->exportConfig['noExportColumns']) && is_array($controller->exportConfig['noExportColumns'])) {
                                        $noExportColumns = $controller->exportConfig['noExportColumns'];
                                    }
                                }

                                $exportConfigExists = isset($controller->exportConfig) && is_array($controller->exportConfig);

                                // Setting dataProvider as variable. It can be overwritten if necessary.
                                $dataProvider = $controller->getDataProvider();

                                if (
                                    $exportConfigExists &&
                                    array_key_exists('dataProvider', $controller->exportConfig) &&
                                    $controller->exportConfig['dataProvider'] instanceof DataProviderInterface
                                ) {
                                    $dataProvider = $controller->exportConfig['dataProvider'];
                                }


                                /** Create a different dataProvider for the export menu */
                                /** @var \yii\data\ActiveDataProvider $exportAllDataProvider */
                                if (empty(\Yii::$app->params['disableExportAll']) || !empty(\Yii::$app->params['disableExportAll']
                                        && \Yii::$app->params['disableExportAll'] == false)) {
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

                                // Gli attributi con chiave numerica hanno bisogno di essere ridefiniti
                                // Quelli classici contengono solo una stringa con il loro nome oppure sono array senza indice
                                $newColumns = [];
                                foreach ($columns as $k => $v) {
                                    if (is_numeric($k) && (gettype($v) === 'string' || isset($v['attribute']))) {
                                        $value = $columns[$k];
                                        if (!empty($v['attribute']) && !empty($value)) {
                                            $newColumns += [$v['attribute'] => $value];
                                        } else {
                                            $newColumns += [$value => ['attribute' => $value]];
                                        }
                                    } else {
                                        $newColumns[$k] = $v;
                                    }
                                }

                                $exportMenuParams = [
                                    'dataProvider' => $exportDataProvider,
                                    'columns' => $newColumns,
                                    'selectedColumns' => array_keys($newColumns),
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
                                    'noExportColumns' => $noExportColumns,
                                    'dropdownOptions' => [
                                        'class' => 'btn btn-secondary',
                                        'icon' => AmosIcons::show('download'),
                                        'title' => Module::t('amoslayout', 'Scarica'),
                                        //'data-toggle' => 'tooltip'
                                        'tabindex' => 0
                                    ],
                                ];

                                if (
                                    $exportConfigExists &&
                                    array_key_exists('emptyText', $controller->exportConfig) &&
                                    ($controller->exportConfig['emptyText'] != '')
                                ) {
                                    $exportMenuParams['emptyText'] = $controller->exportConfig['emptyText'];
                                }

                                $enableBackgroundDownload = false;

                                // Check if it's enabled background download with module taskmanager
                                $moduleTaskmanager = \Yii::$app->getModule('taskmanager');
                                if ($moduleTaskmanager) {
                                    if (isset($controller->exportConfig['enableBackgroundDownload'])) {
                                        $enableBackgroundDownload = $controller->exportConfig['enableBackgroundDownload'];
                                        if (isset($controller->exportConfig['backgroundDownloadOptions'])) {
                                            if (isset($controller->exportConfig['backgroundDownloadOptions']['exportAction'])) {
                                                $exportAction = $controller->exportConfig['backgroundDownloadOptions']['exportAction'];
                                            }
                                            if (isset($controller->exportConfig['backgroundDownloadOptions']['redirectUrl'])) {
                                                $redirectUrl = $controller->exportConfig['backgroundDownloadOptions']['redirectUrl'];
                                            }
                                        }
                                    }
                                }

                                // Renders an export dropdown menu
                                echo Html::beginTag('div', [
                                    'id' => 'change-view-dropdown-download',
                                    'class' => 'hidden'
                                ]);

                                if ($enableBackgroundDownload && !empty($exportAction)) {
                                    echo Html::beginTag('div', ['class' => 'dropdown']);
                                    echo Html::button(
                                        AmosIcons::show('download'), [
                                            'class' => 'btn btn-secondary dropdown-toggle',
                                            'data-toggle' => 'dropdown',
                                            'title' => Module::t('amoslayout', 'Scarica'),
                                        ]
                                    );
                                    echo \yii\bootstrap\Dropdown::widget([
                                        'encodeLabels' => false,
                                        'items' => [
                                            [
                                                'label' => Html::a(
                                                    Html::tag('span', '', ['class' => 'mdi mdi-file-excel-box text-success m-r-5']) . 'Excel 2007+',
                                                    '#',
                                                    [
                                                        'title' => Module::t('amoslayout', 'Scarica in formato .xlsx'),
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#modal-export',
                                                    ]
                                                ),
                                                'url' => '#',
                                            ],
                                        ],
                                    ]);

                                    echo Html::endTag('div');

                                    // Modal
                                    \yii\bootstrap\Modal::begin([
                                        'id' => 'modal-export',
                                        'header' => '<h5>' . Module::t('amoslayout', 'Esportazione') . '</h5>',
                                    ]);

                                    // Modal body
                                    $modalBody = Module::t('amoslayout', 'Sei sicuro di voler avviare l\'esportazione?');
                                    if (!empty($redirectUrl)) {
                                        $modalBody .= '<br>';
                                        $modalBody .= Module::t('amoslayout', 'Una volta confermato, verrai reindirizzato alla pagina "<a href="' . "{url}" . '" target="_blank">Le mie esportazioni</a>".', [
                                            'url' => $redirectUrl
                                        ]);
                                    }
                                    echo $modalBody;

                                    // Modal footer
                                    echo Html::beginTag('div', ['class' => 'modal-footer', 'style' => 'border-top: none']);
                                    echo Html::button('Annulla', ['class' => 'btn btn-secondary m-r-10', 'data-dismiss' => 'modal']);
                                    echo Html::a('Conferma', [
                                        $exportAction,
                                        'redirectUrl' => !empty($redirectUrl) ? $redirectUrl : '',
                                        'query' => $exportDataProvider->query->createCommand()->getRawSql(),
                                    ], ['class' => 'btn btn-primary']);
                                    echo Html::endTag('div');

                                    \yii\bootstrap\Modal::end();
                                } else {
                                    echo ExportMenu::widget($exportMenuParams);
                                }

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


<?php
$script = <<< JS

    $('.close').click(function () {
        var notThisBtn = $('.show-hide-element').not($(this));

        notThisBtn.each(function () {
            $(this).removeClass('active');
        });

        $('.element-to-toggle').each(function () {
            $(this).removeClass('toggleIn');
        });
    });

JS;
$this->registerJs($script, View::POS_READY);
?>
