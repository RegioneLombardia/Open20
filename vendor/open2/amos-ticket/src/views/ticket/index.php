<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\models\TicketCategorie;
use open2\amos\ticket\assets\TicketAsset;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketSearch $model
 * @var AmosTicket $module
 */

TicketAsset::register($this);

 
/** @var AmosTicket $module */
$module = \Yii::$app->getModule('ticket');
$disableTicketOrganization = (!empty($module) ? $module->disableTicketOrganization : false);

$template = '{view}{update}{delete}';
if (AmosTicket::instance()->hideUpdateButtonOnTickets) {
    $template = '{view}{delete}';
}

/** @var TicketCategorie $emptyTicketCategorie */
$emptyTicketCategorie = $module->createModel('TicketCategorie');

$columnsGrid = [
    'id',
    'titolo:ntext',
    'status' => [
        'attribute' => 'status',
        'value' => function ($model) {
            /** @var Ticket $model */
            return $model->getWorkflowStatusLabel();
        }
    ],
    'created_by' => [
        'attribute' => 'createdUserProfile',
        'label' => AmosTicket::t('amosticket', 'Aperto Da'),
        'value' => function ($model) {
            if ($model->isGuestTicket()) {
                return $model->guest_name . ' ' . $model->guest_surname;
            } else {
                /** @var Ticket $model */
                $createdUserProfile = $model->createdUserProfile;
                return Html::a($createdUserProfile->nomeCognome, $createdUserProfile->getFullViewUrl(), [
                    'title' => AmosTicket::t('amosticket', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $createdUserProfile->nomeCognome])
                ]);
            }
        },
        'format' => 'html'
    ],
    'created_at' => [
        'label' => AmosTicket::t('amosticket', 'Aperto il'),
        'value' => function ($model) {
            /** @var Ticket $model */
            return Yii::$app->formatter->asDatetime($model->created_at, 'humanalwaysdatetime');
        }
    ],
    
    'partnership_id' => [
        'attribute' => 'partnership_id',
        'label' => AmosTicket::t('amosticket', 'Relativo alla sede'),
        'value' => function ($model) {
            /** @var Ticket $model */
            $partnership_principale = $model->partnership;
            if (!is_null($partnership_principale)) {
                return $partnership_principale->name;
            } else {
                return '---';
            }
        }
    ],
    
    /** **
     * 'closed_by' => [
     * 'attribute' => 'closed_by',
     * ],
     ** */
    'closed_by' => [
        'label' => AmosTicket::t('amosticket', 'Chiuso Da'),
        'value' => function ($model) {
            /** @var Ticket $model */
            $closedUserProfile = $model->closedUserProfile;
            if ($closedUserProfile) {
                return Html::a($closedUserProfile->nomeCognome, $closedUserProfile->getFullViewUrl(), [
                    'title' => AmosTicket::t('amosticket', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $closedUserProfile->nomeCognome])
                ]);
            } else {
                return '--';
            }
        },
        'format' => 'html'
    ],
    'closed_at' => [
        'attribute' => 'closed_at',
        'value' => function ($model) {
            /** @var Ticket $model */
            return Yii::$app->formatter->asDatetime($model->closed_at, 'humanalwaysdatetime');
        }
    ],
    'ticketCategoria.nomeCompleto',
];
$hideColumns = (isset($this->params['hideColumns'])) ? $this->params['hideColumns'] : null;
if (!is_null($hideColumns) && is_array($hideColumns)) {
    if (!in_array('tecnica', $hideColumns) || !in_array('administrative', $hideColumns)) {
        if ($module->enableAdministrativeTicketCategory && !in_array('tecnica', $hideColumns) && !in_array('administrative', $hideColumns)) {
            $columnsGrid[] = [
                'label' => AmosTicket::t('amosticket', '#category_type'),
                'value' => function ($model) {
                    /** @var Ticket $model */
                    $category = $model->ticketCategoria;
                    if ($category->tecnica) {
                        return AmosTicket::t('amosticket', '#external_technical');
                    } elseif ($category->administrative) {
                        return AmosTicket::t('amosticket', '#external_administrative');
                    } else {
                        return AmosTicket::t('amosticket', '#category_type_empty');
                    }
                }
            ];
        } elseif ($module->enableAdministrativeTicketCategory && in_array('tecnica', $hideColumns) && !in_array('administrative', $hideColumns)) {
            $columnsGrid[] = 'ticketCategoria.administrative:boolean';
        } elseif ($module->enableAdministrativeTicketCategory && !in_array('tecnica', $hideColumns) && in_array('administrative', $hideColumns)) {
            $columnsGrid[] = 'ticketCategoria.tecnica:boolean';
        }
    }
} else {
    if ($module->enableAdministrativeTicketCategory) {
        $columnsGrid[] = [
            'label' => AmosTicket::t('amosticket', '#category_type'),
            'value' => function ($model) {
                /** @var Ticket $model */
                $category = $model->ticketCategoria;
                if ($category->tecnica) {
                    return AmosTicket::t('amosticket', '#external_technical');
                } elseif ($category->administrative) {
                    return AmosTicket::t('amosticket', '#external_administrative');
                } else {
                    return AmosTicket::t('amosticket', '#category_type_empty');
                }
            }
        ];
    }
}
$columnsGrid['action'] = [
    'class' => 'open20\amos\core\views\grid\ActionColumn',
    'template' => $template,
];
if (!is_null($hideColumns) && is_array($hideColumns)) {
    foreach ($hideColumns as $c) {
        if (isset($columnsGrid[$c])) {
            unset($columnsGrid[$c]);
        }
    }
}

if ($disableTicketOrganization) {
    unset($columnsGrid['partnership_id']);
}

$rowOptions = function ($model) {
    /** @var Ticket $model */
    $category = $model->ticketCategoria;
    if ($category->administrative) {
        return ['class' => 'administrative-row'];
    }
    return [];
};

?>
<div class="ticket-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'createNewBtnParams' => null,
        'gridView' => [
            'rowOptions' => $rowOptions,
            'columns' => $columnsGrid,
            'enableExport' => Yii::$app->user->can('EXPORT_TICKETS'),
        ]
    ]); ?>
</div>
