<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCartellePath;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\DocumentsOwlCarouselWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\documenti\models\search\DocumentiSearch $model
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var array $previousDocumentParentIds
 * @var array $currentView
 */


$js = <<<JS
    // Toggle active class to row expanded/collapsed
    var grid = $('#admin-all-documents-grid');
    grid.on('kvexprow:toggle', function (event, ind, key, extra, state) {
        var tr = $(this).find('tr[data-key="' + key + '"]');
        tr.toggleClass('active');
    });
    // Add active class to row expanded by default if previousDocumentParentIds is not empty
    grid.on('kvexprow:loaded', function (event, ind, key, extra) {
        var tr = $(this).find('tr[data-key="' + key + '"]');
        tr.addClass('active');
    });

JS;
$this->registerJs($js);


if ($currentView['name'] == 'expl') {
    echo $this->render('_explorer', []);
    return null;
}

/*
if ($currentView['name'] == 'grid') {
    $dataProvider->pagination = false;
}
*/

$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;

/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$actionId = $controller->action->id;
$actionColumnsTemplate = $controller->getGridViewActionColumnsTemplate($actionId);

$foldersEnabled = $controller->documentsModule->enableFolders;
$enableCategories = $controller->documentsModule->enableCategories;
$hidePubblicationDate = $controller->documentsModule->hidePubblicationDate;
$showCountDocumentRecursive = $controller->documentsModule->showCountDocumentRecursive;
$enableVersioning = $controller->documentsModule->enableDocumentVersioning;

$queryParams = \Yii::$app->request->getQueryParams();
$parentId = \Yii::$app->request->get('parentId');
if (!empty($parentId)) {
    $folder = Documenti::findOne($parentId);
    if ($folder) {
        $statusFolder = '';
        if ($model->status != \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
            $statusFolder = "<span style='color:#777777' class='m-l-5'><i> (". AmosDocumenti::t('amosdocumenti', $folder->status) . ") </i></span>";
        }
        $this->params['subTitleSection'] = '<span class="icon icon-folder icon-sm mdi mdi-folder "></span>'.ucfirst($folder->getTitle()).$statusFolder;
        $this->params['subTitleAdditionalClass'] = 'lead m-t-10';

    }
}

$documentsModule = AmosDocumenti::instance();

$columns = [];
if ($foldersEnabled) {
    // Expand row
    $columns['expandAllTitle'] = [
        'width' => '2%',
        'class' => 'kartik\grid\ExpandRowColumn',
        'expandTitle' => AmosDocumenti::t('amosdocumenti', 'Espandi cartella'),
        'collapseTitle' => AmosDocumenti::t('amosdocumenti', 'Comprimi cartella'),
        'expandOneOnly' => false,
        'detailRowCssClass' => '',
        'allowBatchToggle' => false,
        'extraData' => [
            'actionId' => $actionId,
            'queryParams' => $queryParams,
            'previousDocumentParentIds' => $previousDocumentParentIds,
        ],
//            'header' => AmosDocumenti::t('amosdocumenti', 'Expand / Collapse'),
//            'headerOptions' => [
//                'style' => 'white-space: nowrap;',
//            ],
//            'expandIcon' => Html::tag('span', '', ['class' => 'mdi mdi-plus']),
//            'collapseIcon' => Html::tag('span', '', ['class' => 'mdi mdi-minus']),
        'value' => function ($model, $key, $index, $column) use ($controller, $previousDocumentParentIds) {
            /** @var Documenti $model */
            if (!empty($previousDocumentParentIds) && $model->is_folder && in_array($model->id, $previousDocumentParentIds)) {
                return \kartik\grid\GridView::ROW_EXPANDED;
            }
            if ($model->is_folder && $model->hasChildren()) {
                return \kartik\grid\GridView::ROW_COLLAPSED;
            }
            return '';
        },
        'disabled' => function ($model, $key, $index, $column) {
            /** @var Documenti $model */
            if (!$model->is_folder || ($model->is_folder && !$model->hasChildren())) {
                return true;
            }
            return false;
        },
        'detailUrl' => \yii\helpers\Url::to(['/documenti/documenti/expand-folder-documents'])
    ];
    // Status
    $columns['status'] = [
        'label' => AmosDocumenti::t('amosdocumenti', 'Stato'),
        'value' => function ($model) {
            /** @var Documenti $model */
            return $model->getStatusIcon();
        },
        'format' => 'raw',
        'width' => '2%'
    ];
    // Type
    $columns['type'] = [
        'label' => AmosDocumenti::t('amosdocumenti', '#type'),
        'format' => 'html',
        'value' => function ($model) {
            /** @var Documenti $model */
            return $model->getFileTypeIcon();
        },
        'width' => '2%'
    ];
    // Titolo
    $columns['titolo'] = [
        'attribute' => 'titolo',
        'headerOptions' => [
            'id' => $model->getAttributeLabel('titolo')
        ],
        'contentOptions' => [
            'headers' => $model->getAttributeLabel('titolo')
        ],
        'format' => 'raw',
        'value' => function ($model) {
            /** @var Documenti $model */
            return $model->getTitleGridView();
        },
        'width' => '40%'
    ];
    if ($enableCategories) {
        // Categoria
        $columns['documenti_categorie_id'] = [
            'attribute' => 'documentiCategorie.titolo',
            'label' => AmosDocumenti::t('amosdocumenti', 'Categoria'),
            'value' => function ($model) {
                /** @var Documenti $model */
                if ($model->documentiCategorie) {
                    return $model->documentiCategorie->titolo;
                }

                return '';
            },
            'width' => '15%'
        ];
    }

    // Numero downloads
//    $columns['downloads'] = [
//        'label' => AmosDocumenti::t('amosdocumenti', '#num_downloads'),
//        'value' => function ($model) {
//            /** @var Documenti $model */
//            if ($model->is_folder) {
//                return '';
//            } else {
//                $numDown = 0;
//                $file = $model->getDocumentMainFile();
//                if (!is_null($file)) {
//                    $numDown = $file->getNumDownloads();
//                }
//                return $numDown;
//            }
//        },
//    ];

    // Caricato da
    if (!isset(\Yii::$app->params['hideListsContentCreatorName']) || (\Yii::$app->params['hideListsContentCreatorName'] === false)) {
        $columns['created_by'] = [
            'attribute' => 'createdUserProfile',
            'label' => AmosDocumenti::t('amosdocumenti', 'Caricato da'),
            'width' => '15%'
        ];
    }

    // Ultima modifica
    if (!isset(\Yii::$app->params['hideListsContentCreatorName']) || (\Yii::$app->params['hideListsContentCreatorName'] === false)) {
        $columns['updated_by'] = [
            'label' => AmosDocumenti::t('amosdocumenti', 'Ultima modifica'),
            'value' => function ($model) {
                /** @var Documenti $model */
                $profile = \open20\amos\admin\models\UserProfile::find()
                    ->andWhere(['user_id' => $model->updated_by])
                    ->one();

                if (empty($profile)) {
                    return '';
                }

                if (isset(\Yii::$app->params['disableLinkContentCreator']) && (\Yii::$app->params['disableLinkContentCreator'] === true)) {
                    return $profile->nomeCognome;
                }

                return Html::a($profile->nomeCognome, $profile->getFullViewUrl(), [
                    'title' => AmosDocumenti::t('amosdocumenti', 'Apri il profilo di {nome_profilo}', [
                        'nome_profilo' => $profile->nomeCognome
                    ])
                ]);
            },
            'format' => 'html',
            'width' => '15%'
        ];
    }

//    $columns[] = [
//        'label' => AmosDocumenti::t('amosdocumenti', 'Documents'),
//        'value' => function ($model) use ($showCountDocumentRecursive) {
//            /** @var Documenti $model */
//            if ($model->is_folder) {
//                if ($showCountDocumentRecursive) {
//                    return count($model->allDocumentChildrens);
//                } else {
//                    return count($model->documentChildrens);
//                }
//            } else {
//                return '';
//            }
//        },
//    ];
} else {
    $columns['titolo'] = [
        'attribute' => 'titolo',
        'headerOptions' => [
            'id' => $model->getAttributeLabel('titolo')
        ]
    ];

    if (!isset(\Yii::$app->params['hideListsContentCreatorName']) || (\Yii::$app->params['hideListsContentCreatorName'] === false)) {
        $columns['created_by'] = [
            'attribute' => 'createdUserProfile',
            'label' => AmosDocumenti::t('amosdocumenti', 'Pubblicato Da'),
        ];
    }
}

// Data pubblicazione
//$columns['data_pubblicazione'] = [
//    'label' => AmosDocumenti::t('amosdocumenti', '#uploaded_at'),
//    'attribute' => 'data_pubblicazione',
//    'value' => function ($model) {
//        /** @var Documenti $model */
//        return is_null($model->data_pubblicazione)
//            ? AmosDocumenti::t('amosdocumenti', 'Subito')
//            : Yii::$app->formatter->asDate($model->data_pubblicazione);
//    }
//];

// Data ultima modifica
$columns['updated_at'] = [
    'label' => AmosDocumenti::t('amosdocumenti', 'Data ultima modifica'),
    'attribute' => 'updated_at',
    'value' => function ($model) {
        /** @var Documenti $model */
        if (!is_null($model->updated_at)) {
            return date_format(date_create($model->updated_at), 'd/m/Y H:i');
        }
        return '';
    },
    'width' => '15%'
];

if (!$foldersEnabled) {
    $columns['data_rimozione'] = [
        'attribute' => 'data_rimozione',
        'value' => function ($model) {
            /** @var Documenti $model */
            return is_null($model->data_rimozione)
                ? AmosDocumenti::t('amosdocumenti', 'Mai')
                : Yii::$app->formatter->asDate($model->data_rimozione);
        }
    ];

    $columns['status'] = [
        'attribute' => 'status',
        'value' => function ($model) {
            /** @var Documenti $model */
            return $model->hasWorkflowStatus()
                ? AmosDocumenti::t('amosdocumenti', $model->getWorkflowStatus()->getLabel())
                : '--';
        }
    ];
}

// Versione
//if ($controller->documentsModule->enableDocumentVersioning) {
//    $columns['version'] = [
//        'attribute' => 'version',
//        'value' => function ($model) {
//            /** @var Documenti $model */
//            return (!$model->is_folder && $model->version ? $model->version : '');
//        },
//    ];
//}

if ($controller->documentsModule->enableAnnualArchiving) {
    $columns['archiving'] = [
        'label' => AmosDocumenti::t('amosdocumenti', '#archiviazione'),
        'value' => function ($model) {
            /** @var Documenti $model */
            $stato = $model->getArchiving();
            return $stato === 0 ? AmosDocumenti::t('amosdocumenti', '#in_corso') : $stato;
        },
    ];
}

//the columns for export have to be before the special columns (ExpandRowColumn, Action column)
$exportColumns = $columns;
$exportColumns['type'] = [
    'value' => function ($model) {
        /** @var Documenti $model */
        if ($model->is_folder) {
            $return = AmosDocumenti::t('amosdocumenti', '#folder');
        } else {
            $return = AmosDocumenti::t('amosdocumenti', '#document');
        }
        return $return;
    }
];

$deleteOptions = DocumentsUtility::getGridActionColumnsButtonsOptions('delete');
$deleteOptions['data-confirm'] = function ($model) {
    /** @var Documenti $model */
    if ($model->is_folder) {
        $trans = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
    } else {
        $trans = AmosDocumenti::t('amoscore', 'Sei sicuro di voler cancellare questo elemento?');
    }
    return $trans;
};


$actionColumns = [
    'class' => 'open20\amos\core\views\grid\ActionColumn',
    'template' => $actionColumnsTemplate,
    'deleteOptions' => $deleteOptions,
    'buttons' => [
        'duplicateBtn' => function ($url, $model) use ($controller) {
            /** @var Documenti $model */
            return $this->render('_duplicate_btn', [
                'model' => $model,
                'isInIndex' => true
            ]);
        },
        'view' => function ($url, $model) {
            /** @var Documenti $model */
            $btn = '';
            if (!$model->is_folder && Yii::$app->getUser()->can('DOCUMENTI_READ', ['model' => $model])) {
                $btn = Html::a(
                    AmosIcons::show('file'),
                    ['view', 'id' => $model->id],
                    [
                        'class' => 'btn btn-tools-secondary',
                        'title' => AmosDocumenti::t('amosdocumenti', 'Open the card')
                    ]
                );
            }
            return $btn;
        },
        'move' => function ($url, $model) use ($documentsModule) {
            /** @var Documenti $model */
            $btn = '';
            
            if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model]) 
                    && $documentsModule->enableMoveDoc 
                    ) {
                $btn = \yii\helpers\Html::a(AmosIcons::show('swap'),'#modalMove', [
                        'class' => 'open-modalMove btn btn-tools-secondary',
                        'data-toggle' => 'modal',
                        'data-id' => $model->id,
                    ]);
            }
            return $btn;
        },
        'newDocVersion' => function ($url, $model) use ($controller) {
            /** @var Documenti $model */
            $btn = '';
            if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO && Yii::$app->getUser()->can(
                    'DOCUMENTI_UPDATE',
                    [
                        'model' => $model,
                        'newVersion' => $controller->documentsModule->enableDocumentVersioning
                    ]
                )) {
                if ($controller->documentsModule->enableDocumentVersioning && !$model->is_folder && (is_null($model->version_parent_id))) {
                    $btn = ModalUtility::addConfirmRejectWithModal([
                        'modalId' => 'new-document-version-modal-id-' . $model->id,
                        'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT'),
                        'btnText' => AmosIcons::show('plus', ['class' => '']),
                        'btnLink' => \yii\helpers\Url::to([
                            '/documenti/documenti/new-document-version',
                            'id' => $model['id']
                        ]),
                        'btnOptions' => [
                            'title' => AmosDocumenti::t(
                                'amosdocumenti',
                                'New document version'
                            ), 'class' => 'btn btn-tools-secondary'
                        ]
                    ]);
                }
            }
            return $btn;
        },
        'validate' => function ($url, $model) {
            /** @var Documenti $model */
            $btn = '';
            if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
                $btn = ModalUtility::addConfirmRejectWithModal([
                    'modalId' => 'validate-document-modal-id-' . $model->id,
                    'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#VALIDATE_DOCUMENT_MODAL_TEXT'),
                    'btnText' => AmosIcons::show('check-circle', ['class' => '']),
                    'btnLink' => Yii::$app->urlManager->createUrl([
                        '/documenti/documenti/validate-document',
                        'id' => $model->id
                    ]),
                    'btnOptions' => [
                        'title' => AmosDocumenti::t(
                            'amosdocumenti',
                            'Publish'
                        ), 'class' => 'btn btn-tools-secondary'
                    ]
                ]);
            }
            return $btn;
        },
        'reject' => function ($url, $model) {
            /** @var Documenti $model */
            $btn = '';
            if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
                $btn = ModalUtility::addConfirmRejectWithModal([
                    'modalId' => 'reject-document-modal-id-' . $model->id,
                    'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#REJECT_DOCUMENT_MODAL_TEXT'),
                    'btnText' => AmosIcons::show('minus-circle', ['class' => '']),
                    'btnLink' => Yii::$app->urlManager->createUrl([
                        '/documenti/documenti/reject-document',
                        'id' => $model->id
                    ]),
                    'btnOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Reject'), 'class' => 'btn btn-tools-secondary']
                ]);
            }
            return $btn;
        },
        'update' => function ($url, $model) use ($enableVersioning) {
            /** @var Documenti $model */
            $btn = '';
            if (Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model])) {
                $action = '/documenti/documenti/update?id=' . $model->id;
                $options = ModalUtility::getBackToEditPopup(
                    $model,
                    'DocumentValidate',
                    $action,
                    [
                        'class' => 'btn btn-tools-secondary',
                        'title' => Yii::t('amoscore', 'Modifica'),
                        'data-pjax' => '0'
                    ]
                );
                $btn = Html::a(\open20\amos\core\icons\AmosIcons::show('edit'), $action, $options);
            }
            return $btn;
        }
    ]
];
$columns[] = $actionColumns;


?>

<?php
if($documentsModule->enableMoveDoc){
        echo $this->render('_move_document', [
            'parentId' => $parentId,
    ]); 

}
?>





<div class="documents-index">
    <?php
    echo $this->render('_search', [
        'model' => $model,
        'originAction' => Yii::$app->controller->action->id,
        'queryParams' => $queryParams
    ]);
    echo $this->render('_order', [
        'model' => $model,
        'queryParams' => $queryParams
    ]);

    echo DocumentsOwlCarouselWidget::widget([
        'owlCarouselId' => 'documentOwlCarousel',
        'owlCarouselClass' => 'document-owl-carousel',
        'singleItemView' => '@vendor/open20/amos-documenti/src/views/documenti/amos_owl_carousel_widget_item',
        'owlCarouselJSOptions' => "{
        margin: 10,
        nav: true,
        loop: false,
        autoplay: false,
        autoplayTimeout: 3000,
        responsive: {
            320: {
                items: 1,
                stagePadding: 30
            },
            420: {
                items: 1,
                stagePadding: 30
            },
            620: {
                items: 1,
                stagePadding: 30
            },
            768: {
                items: 2,
                stagePadding: 30
            },
            992: {
                items: 2,
                stagePadding: 30
            },
            1199: {
                items: 3,
                stagePadding: 30
            }
        }
    }"
    ]);

    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'options' => [
                'id' => 'admin-all-documents-grid',
            ],
            'rowOptions' => function ($model) {
                return ['class' => 'kv-disable-click'];
            },
            'columns' => $columns,
            'enableExport' => true
        ],
        'listView' => [
            'itemView' => '_item',
            'showItemToolbar' => false,
        ],
        'exportConfig' => [
            'exportEnabled' => true,
            'exportColumns' => $exportColumns
        ]
    ]);
    ?>
</div>
