<?php
/**
 * @var $dataProviderLists \yii\data\ActiveDataProvider
 */

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use \yii\bootstrap4\ActiveForm;
// use open20\amos\core\forms\ActiveForm;
use open20\amos\events\models\search\UserEventSearch;
use yii\helpers\Html;
use open20\amos\layout\utility\AmosIconsBi;
use open20\amos\events\models\Event;

$js = <<<JS
    $('.alert-send-invitation').click(function(e){
        e.preventDefault();
        $('#modal-alert-send-invitation').modal('show');
    });
JS;
$this->registerJs($js);
$isEventPublic = $model->isEventPublic();

$this->title = AmosEvents::t('amosevents', 'Ricerca utenti');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;


$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
?>
    <h5 class="font-weight-bold my-4">
        <svg class="icon">
            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_contact_mail"></use>
        </svg>
        <?= AmosEvents::t('amosevents', "Liste Utenti salvate") ?>
    </h5>
    <div class="row">
        <?php
        $statuses = \open20\amos\events\models\EventInternalList::getStatusSending($dataProviderLists->keys) ?>
        <?php $event = $model; ?>
        <div class="col-md-12">
            <?= \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $dataProviderLists,
                'columns' => [
                    [
                        'label' => AmosEvents::t('amosevents', 'Titolo della ricerca'),
                        'attribute' => 'name',
                        'value' => function ($model) {
                            return $model->name;
                        }
                    ],
                    [
                        'attribute' => 'created_by',
                        'value' => function ($model) {
                            $profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $model->created_by])->one();
                            if ($profile) {
                                return $profile->nomeCognome;
                            }
                        }

                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'label' => AmosEvents::t('amosevents', "Data ed ora di salvataggio")

                    ],
                    [
                        'label' => AmosEvents::t('amosevents', 'Modello email'),
                        'value' => function ($model) {
                            return \open20\amos\events\models\EventEmailTemplates::getLabelTemplate()[$model->template];
                        }
                    ],
                    'n_user_found',
                    [
                        'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                        'template' => '{stop_sending}{statistics}{update}{delete}{send-invitation}',
                        'buttons' => [
                            // 'update' => function ($url, $model) {
                            //     return Html::a('Modifica', ['update-invite', 'event_id' => $model->event_id, 'id' => $model->id], [
                            //         'class' => 'btn btn-xs btn-outline-secondary mr-2'
                            //     ]);
                            // },
                            // 'delete' => function ($url, $model) {
                            //     return Html::a('Elimina', ['delete', 'id' => $model->id], [
                            //         'class' => 'btn btn-xs btn-outline-danger mr-2'
                            //     ]);
                            // },
                            // 'send-invitation' => function ($url, $model) {
                            //     if ($model->getEventInvitationSent()->count() > 0) {
                            //         return AmosEvents::t('amosevents', 'Inviati');
                            //     };
                            //     return Html::a(AmosIcons::show('mail-send') . 'Invia inviti', ['summary-sending', 'idInternalList' => $model->id], [
                            //         'class' => 'btn btn-xs btn-primary',
                            //     ]);
                            // }

                            'update' => function ($url, $model) use ($event) {
                                $url = ['update-invite', 'idList' => $model->id, 'eid' => $event->id];
                                return Html::a(AmosIconsBi::show('ic_edit'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => AmosEvents::t('amosevents', 'Modifica')], true);
                            },

                            'delete' => function ($url, $model) use ($event) {
                                $url = ['delete-internal-list', 'idList' => $model->id, 'eid' => $event->id];
                                return Html::a(AmosIconsBi::show('ic_close', 'danger'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => AmosEvents::t('amosevents', 'Elimina'),
                                    'data-confirm' => 'Sei sicuro di eliminare la lista?'], true);
                            },
                            'send-invitation' => function ($url, $model) use ($event, $statuses, $isEventPublic) {
                                if (!is_null($model->status)) {
                                    $status = $model->getSingleStatusSending($statuses);
                                    return "<div class='w-100 text-right'>" . $status . "</div>";
                                };

                                if(!$isEventPublic) {
                                    $class = 'alert-send-invitation';
                                }
                                $url = ['summary-sending', 'idInternalList' => $model->id, 'eid' => $event->id];
                                $label = Html::tag('span', AmosEvents::t('amosevents', 'Gestisci gli invii'), ['class' => 'ml-1']);
                                return "<div>" . Html::a(AmosIconsBi::show('ic_send', 'primary') . $label, $url, ['class' => "$class ml-2 btn btn-xs btn-icon btn-primary", 'data-toggle' => 'tooltip', 'title' => AmosEvents::t('amosevents', 'Gestisci gli invii')], true) . "</div>";
                                // task 1306 la label prima era invia Inviti
                            },

                            'statistics' => function ($url, $model) use ($event) {
                                $url = ['statistics-invitation', 'internalListId' => $model->id, 'eid' => $event->id];
                                if ($model->mailup_message_id) {
                                    return Html::a(AmosIconsBi::show('ic_show_chart'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => AmosEvents::t('amosevents', 'Statistics')], true);
                                }
                            },
                            'stop_sending' => function ($url, $model) use ($event) {
                                $url = ['stop-sending', 'internalListId' => $model->id, 'eid' => $event->id];
                                if ($model->status != \open20\amos\events\models\EventInternalList::STATUS_SENT && $model->status != null) {
                                    return Html::a(AmosIconsBi::show('ic_block', 'danger'), $url, [
                                        'class' => 'btn btn-xs btn-icon',
                                        'data-toggle' => 'tooltip',
                                        'title' => AmosEvents::t('amosevents', 'Annulla invio'),
                                        'data-confirm' => AmosEvents::t('amosevents', 'Sei sicuro di annullare l\'invio?')
                                    ], true);
                                }
                            },

                        ]
                    ]
                ],

            ])
            ?>
        </div>
    </div>
<?php
\yii\bootstrap4\Modal::begin([
    'id' => 'modal-alert-send-invitation',
    'title' => "<h3>" . AmosEvents::t('amosevents', 'Gestisci invii non attiva') . "</h3>",
    'size' => 'modal-lg',

]); ?>
<?php if ($model->status != Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) { ?>
    <div class="col-md-12">
        <p><?= AmosEvents::t('amosevents', "Non puoi accedere alla Gestione degli invii perché la landing page dell’evento non è stata pubblicata. Per pubblicare la landing page, devi prima attivare l’evento.") ?></p>
        <p>
            <?= Html::a(AmosEvents::t('amosevents', "Attiva evento"), ['/events/event-dashboard/publish', 'id' => $model->id], [
                'class' => 'btn btn-primary btn-xs mt-3'
            ]) ?>
        </p>
    </div>
<?php } else if(!$isEventPublic){ ?>
      <div class="col-md-12">
        <p><?= AmosEvents::t('amosevents', "Non puoi accedere alla Gestione degli invii perché la landing page dell’evento non è ancora stata pubblicata. <br>Per pubblicare la landing page, modifica la sua data di pubblicazione.") ?></p>
        <p>
            <?= Html::a(AmosEvents::t('amosevents', "Modifica pubblicazione"), ['/events/event-dashboard/info-event', 'id' => $model->id, '#' => 'publication-date-section'], [
                'class' => 'btn btn-primary btn-xs mt-3'
            ]) ?>
        </p>
    </div>
<?php }?>
<?php \yii\bootstrap4\Modal::end();
?>

<?= $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_invite', [
    'modelSearch' => $modelSearch,
    'internalList' => $internalList,
    'moduleEvents' => $moduleEvents,
    'model' => $model,
    'wizard' => false,
    'saveButton' => true,

]) ?>