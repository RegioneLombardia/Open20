<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\Sondaggi;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */
$this->title                   = AmosSondaggi::t('amossondaggi', 'Pubblica sondaggi');
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

    <p>
        <?php /* echo         Html::a(AmosSondaggi::t('amossondaggi', 'Nuovo {modelClass}', [
          'modelClass' => 'Sondaggi',
          ])        , ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>

    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                'filemanager_mediafile_id' => [
                    'label' => 'Immagine',
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                        $mediafile = \pendalf89\filemanager\models\Mediafile::findOne($model->filemanager_mediafile_id);
                        $url       = '/img/img_default.jpg';
                        if ($mediafile) {
                            $url = $model->getAvatarUrl('medium');
                        }
                        return Html::img($url,
                                [
                                'class' => 'gridview-image'
                        ]);
                    }
                ],
                'descrizione' => [
                    'format' => 'html',
                    'value' => function($model) {
                        $arr[] = $model->titolo;
                        $arr[] = $model->descrizione;
                        return implode("<br>", $arr);
                    },
                ],
                /* 'risposte' => [
                  'label' => 'Risposte',
                  'value' => function($model) {
                  return 'Nessuna risposta';
                  }
                  ], */
                'status' => [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                        return $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--';
                    }
                ],
                //['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            'created_by',
//            'updated_by',
//            'deleted_by',
//            'version',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{pubblica} {notifica}',
                    'buttons' => [
                        'anteprima' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGI_READ',
                                    ['model' => $model])) {
                                return Html::a(AmosIcons::show('eye', ['class' => 'btn btn-tool-secondary']),
                                        Yii::$app->urlManager->createUrl([
                                            '/'.$this->context->module->id.'/sondaggi/view',
                                            'id' => $model->id,
                                            'url' => $url,
                                        ]),
                                        [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Visualizza anteprima'),
                                ]);
                            } else {
                                return '';
                            }
                        },
                        'pubblica' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SondaggiValidate',
                                    ['model' => $model])) {
                                if ($model->verificaSondaggioPubblicabile()) {
                                    if ($model->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
                                        return Html::a(AmosIcons::show('globe-alt',
                                                    ['class' => 'btn btn-tool-secondary']),
                                                Yii::$app->urlManager->createUrl([
                                                    '/'.$this->context->module->id.'/pubblicazione/pubblica',
                                                    'idSondaggio' => $model->id,
                                                    'url' => $url
                                                ]),
                                                ['data-confirm' => AmosSondaggi::t('amossondaggi',
                                                    'ATTENZIONE!!! La ripubblicazione del sondaggio sovrascriverà il vecchio, le risposte al precedente sondaggio non verranno cancellate, sei sicuro di voler continuare?'),
                                                'title' => AmosSondaggi::t('amossondaggi', 'Ripubblica sondaggio'),
                                        ]);
                                    } else {
                                        return Html::a(AmosIcons::show('globe-alt',
                                                    ['class' => 'btn btn-tool-secondary']),
                                                Yii::$app->urlManager->createUrl([
                                                    '/'.$this->context->module->id.'/pubblicazione/pubblica',
                                                    'idSondaggio' => $model->id,
                                                    'url' => $url,
                                        ]));
                                    }
                                } else {
                                    return Html::a(AmosIcons::show('globe-alt',
                                                ['class' => 'btn btn-tool-secondary', 'style' => 'color:red;']), NULL,
                                            [
                                            'title' => AmosSondaggi::t('amossondaggi',
                                                'Sondaggio non pubblicabile in quanto la sua configurazione non è corretta, verificare le pagine, le domande e le risposte predefinite. Se hai abilitato la registrazione nel frontend, devi mappare i campi nome, cognome ed email nelle domande dei sondaggi con campi liberi'),
                                            'data-confirm' => AmosSondaggi::t('amossondaggi',
                                                'Sondaggio non pubblicabile in quanto la sua configurazione non è corretta, verificare le pagine, le domande e le risposte predefinite. Se hai abilitato la registrazione nel frontend, devi mappare i campi nome, cognome ed email nelle domande dei sondaggi con campi liberi'),
                                    ]);
                                }
                            } else {
                                return '';
                            }
                        },
                        'notifica' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiSearch $model */
                            $url = \yii\helpers\Url::current();
                            if ((\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SondaggiValidate',
                                    ['model' => $model])) && $this->context->module->enableNotificationEmailByRoles) {
                                if ($model->verificaSondaggioPubblicabile()) {
                                    if (($model->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) && !empty($model->getSondaggiPubblicaziones()->one()['ruolo'])
                                        && $model->getSondaggiPubblicaziones()->one()['ruolo'] != 'PUBBLICO') {
                                        if (empty($model->getSondaggiPubblicaziones()->one()['mail_subject']) || empty($model->getSondaggiPubblicaziones()->one()['mail_message'])
                                            || empty($this->context->module->defaultEmailSender)) {
                                            return Html::a(AmosIcons::show('email',
                                                        ['class' => 'btn btn-tool-secondary', 'style' => 'color:red;']),
                                                    NULL,
                                                    [
                                                    'title' => AmosSondaggi::t('amossondaggi',
                                                        'Non è possibile inviare le notifiche se non si compilano tutti campi relativi alla notifica in "Gestione sondaggi" e se non si imposta la mail da cui inviare le notifiche.'),
                                            ]);
                                        } else {
                                            return Html::a(AmosIcons::show('email',
                                                        ['class' => 'btn btn-tool-secondary']),
                                                    Yii::$app->urlManager->createUrl([
                                                        '/'.$this->context->module->id.'/pubblicazione/notifica',
                                                        'idSondaggio' => $model->id,
                                                        'url' => $url
                                                    ]),
                                                    ['data-confirm' => AmosSondaggi::t('amossondaggi',
                                                        'ATTENZIONE!!! Stai per inviare una mail a tutti gli utenti a cui è destinato il sondaggio, sei sicuro di voler continuare?'),
                                                    'title' => AmosSondaggi::t('amossondaggi',
                                                        'Notifica la presenza di un nuovo sondaggio'),
                                            ]);
                                        }
                                    }
                                }
                            }
                            return '';
                        }
                    ]
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_itemPub',
            'viewParams' => [
                'hideDateEnd' => true
            ],
        ], /*
          'iconView' => [
          'itemView' => '_icon'
          ],
          'mapView' => [
          'itemView' => '_map',
          'markerConfig' => [
          'lat' => 'domicilio_lat',
          'lng' => 'domicilio_lon',
          ]
          ],
          'calendarView' => [
          'itemView' => '_calendar',
          'clientOptions' => [
          //'lang'=> 'de'
          ],
          'eventConfig' => [
          //'title' => 'titoloEvento',
          //'start' => 'data_inizio',
          //'end' => 'data_fine',
          //'color' => 'coloreEvento',
          //'url' => 'urlEvento'
          ],
          ] */
    ]);
    ?>
</div>
