<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $container
 * @var $canPublish
 * @var $lvl
 * @var $ancestors array
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\AmosGridView;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\documenti\models\Documenti $model
 * @var string $actionId
 * @var AmosDocumenti $documentsModule
 * @var \open20\amos\documenti\controllers\DocumentiController $controller
 * @var array $previousDocumentParentIds
 */

$controller = Yii::$app->controller;
$documentsModule = AmosDocumenti::instance();
$foldersEnabled = $controller->documentsModule->enableFolders;
$enableCategories = $controller->documentsModule->enableCategories;
$enableVersioning = $controller->documentsModule->enableDocumentVersioning;
$actionColumnsTemplate = $controller->getGridViewActionColumnsTemplate($actionId);
//$dataProvider->sort = false;

$columns = [];
if ($foldersEnabled) {
// Expand Row
    $columns['expandRow'] = [
        'width' => '2%;',
        'class' => 'kartik\grid\ExpandRowColumn',
        'expandOneOnly' => false,
        'allowBatchToggle' => false,
        'detailRowCssClass' => '',
        'header' => '',
        'expandTitle' => AmosDocumenti::t('amosdocumenti', 'Espandi cartella'),
        'collapseTitle' => AmosDocumenti::t('amosdocumenti', 'Comprimi cartella'),
//        'extraData' => [
//            'container' => $container,
//        ],
        'value' => function ($model, $key, $index) use ($previousDocumentParentIds) {
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
        'detailUrl' => '/documenti/documenti/expand-folder-documents',
    ];

// Status
    $columns['status'] = [
        'label' => AmosDocumenti::t('amosdocumenti', 'Stato'),
        'value' => function ($model) {
            /** @var Documenti $model */
            return $model->getStatusIcon();
        },
        'format' => 'raw',
        'width' => '2%',
    ];

// Type
    $columns['type'] = [
        'label' => AmosDocumenti::t('amosdocumenti', '#type'),
        'format' => 'html',
        'value' => function ($model) {
            /** @var Documenti $model */
            return $model->getFileTypeIcon();
        },
        'width' => '2%',
    ];

// Titolo
    $columns['title'] = [
        'attribute' => 'titolo',
        'enableSorting' => false,
        'format' => 'raw',
        'value' => function ($model) use ($actionId) {
            /** @var Documenti $model */
            $iconSubFolder = Html::tag('span', '', ['class' => 'mdi mdi-subdirectory-arrow-right m-r-10']);
            return $iconSubFolder . $model->getTitleGridView($actionId);
        },
        'width' => '40%',
    ];

// Categoria
    $columns['category'] = [
        'attribute' => 'documentiCategorie.titolo',
        'label' => AmosDocumenti::t('amosdocumenti', 'Categoria'),
        'value' => function ($model) {
            /** @var Documenti $model */
            if ($model->documentiCategorie) {
                return $model->documentiCategorie->titolo;
            }

            return '';
        },
        'width' => '15%',
    ];
    // Caricato da
    if (!isset(\Yii::$app->params['hideListsContentCreatorName']) || (\Yii::$app->params['hideListsContentCreatorName'] === false)) {
        $columns['created_by'] = [
            'attribute' => 'createdUserProfile',
            'label' => AmosDocumenti::t('amosdocumenti', 'Caricato da'),
            'width' => '15%',
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
            'width' => '15%',
        ];
    }
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
    'enableSorting' => false,
    'width' => '15%',
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

if ($documentsModule->enableAnnualArchiving) {
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


// Action column
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

            if (Yii::$app->getUser()->can('DOCUMENTI_READ', ['model' => $model])
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


echo AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'showHeader' => true,
    'showPageSummary' => false,
    'options' => [
        'id' => 'subFolderGridView_' . time(),
    ],
//    'rowOptions' => function ($model, $key, $index, $grid) {
//            return ['class' => 'active'];
//    },
    'columns' => $columns
]);
