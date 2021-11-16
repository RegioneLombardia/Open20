<?php

/**
 * @var $model \open20\amos\events\models\Event
 */

use open20\amos\events\AmosEvents;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

$this->title = AmosEvents::t('amosevents', 'Crea un nuovo evento');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$eventLanding = $model->eventLanding;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>
<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="mb-0 pr-2 text-muted"><?= (empty(trim($this->title)) ?: $this->title) ?></h3>
        </div>
        <div>
            <div class="step">4</div>
        </div>
    </div>
</div>
<div class="my-4 d-flex">
    <div class="mr-2">
        <svg class="icon">
            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_playlist_add_check"></use>
        </svg>
    </div>

    <h5 class="font-weight-bold ">
        <?= AmosEvents::t('amosevents', "Riepilogo") ?>
    </h5>
</div>

<div class="my-5">
    <h6>
        <strong><?= AmosEvents::t('amosevents', "Hai creato con successo l'evento!<br>L'evento non è ancora pubblico. Potrai renderlo pubblico in qualunque momento cliccando su Pubblica Evento all'interno della Dashboard.") ?></strong>
    </h6>
</div>
<div class="riepilogo">
    <div class="row variable-gutters">
        <div class="col-md-7 mr-auto order-2 order-md-1">


            <div class="d-flex align-items-center mb-5 mt-5 mt-md-0">
                <div class="mr-4">
                    <svg class="icon icon-lg icon-primary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                    </svg>
                </div>
                <div>
                    <div><?= AmosEvents::t('amosevents', "Titolo dell'evento") . ': ' . "<strong>" . $model->getTitle() . "</strong>" ?></div>
                    <div><?= AmosEvents::t('amosevents', "Luogo di svolgimento") . ': ' . "<strong>" . (!empty($model->eventLocation) ? $model->eventLocation->name : "") . "</strong>" ?></div>
                    <div><?= AmosEvents::t('amosevents', "Entrata") . ': ' . "<strong>" . (!empty($model->eventLocationEntrance) ? $model->eventLocationEntrance->name : "") . "</strong>" ?></div>
                    <div><?= AmosEvents::t('amosevents', "Data e ora di inizio") . ': ' . "<strong>" . \Yii::$app->formatter->asDatetime($model->begin_date_hour) . "</strong>" ?></div>

                </div>
            </div>

            <div class="d-flex align-items-center mb-5">
                <div class="mr-4">
                    <svg class="icon icon-lg icon-primary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                    </svg>
                </div>
                <div>
                    <?= AmosEvents::t('amosevents', "Aspetto della landing page") . ': ' . "<strong>" . $titleTemplate . "</strong>" ?><br>
                </div>
            </div>

            <div class="d-flex align-items-center mb-5">
                <div class="mr-4">
                    <svg class="icon icon-lg icon-primary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                    </svg>
                </div>
                <div>
                    <?= AmosEvents::t('amosevents', "La landing page sarà pubblicata il") . ': ' . "<strong>" . \Yii::$app->formatter->asDate($model->publication_date_begin) . "</strong>" ?><br>
                    <?= AmosEvents::t('amosevents', "all'indirizzo") . ': ' . "<strong>" . \open20\amos\events\utility\EventsUtility::getUrlLanding($model) . "</strong>" ?><br>
                </div>
            </div>

            <div class="d-flex align-items-center mb-5">
                <div class="mr-4">
                    <svg class="icon icon-lg icon-primary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                    </svg>
                </div>
                <div>
                    <?= AmosEvents::t('amosevents', "Le iscrizioni apriranno il") . ': ' . "<strong>" . \Yii::$app->formatter->asDatetime($model->registration_date_begin) . "</strong>" ?><br>
                </div>
            </div>

            <?php if ($model->show_community) { ?>
                <div class="d-flex align-items-center mb-5">
                    <div class="mr-4">
                        <svg class="icon icon-lg icon-primary">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                        </svg>
                    </div>
                    <div>
                        <?php
                        $names = [];
                        foreach ($icons as $icon) {
                            $clasnameWidget = $icon->classname;
                            $widget = new $clasnameWidget();
                            $names[] = $widget->label;
                        }
                        ?>

                        <?= AmosEvents::t('amosevents', "Hai aperto una community dell'evento con i seguenti plugin") . ': ' . "<br>"
                        . "<strong>" . implode(', ', $names) . "</strong>"
                        ?>
                    </div>
                </div>
            <?php } ?>

            <div class="d-flex align-items-center mb-5">
                <div class="mr-4">
                    <svg class="icon icon-lg icon-primary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                    </svg>
                </div>
                <div>
                    <?= AmosEvents::t('amosevents', "Hai scelto il layout predefinito per le <strong>email</strong> da inviare agli utenti. Potrai modificarlo in qualunque momento.") . "<strong>" . "</strong>" ?><br>
                </div>
            </div>

            <div class="d-flex align-items-center mb-5">
                <div class="mr-4">
                    <svg class="icon icon-lg icon-primary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                    </svg>
                </div>
                <div>
                    <?php $list = $model->getEventInternalLists()->one() ?>
                    <?= AmosEvents::t('amosevents', "Hai salvato una lista di <strong>{n} utenti che puoi invitare.</strong>", ['n' => $list ? $list->n_user_found : 0]) ?><br>
                    <?= AmosEvents::t('amosevents', "Puoi invitare altri utenti in qualunque momento dalla dashboard.") ?><br>
                </div>
            </div>
            <div class="mt-5 pt-3">
                <?= AmosEvents::t('amosevents', "<p><strong>GRAZIE!</strong><br>
Nel caso tu voglia modificare alcune opzioni o personalizzare 
ulteriormente i dettagli dell’evento puoi proseguire alla 
DASHBOARD EVENTO altrimenti chiudi semplicemente questa finestra.</p>") ?>
            </div>


        </div>
        <div class="col-md-4 d-flex flex-column order-1 order-md-2">
            <?php
            $logoUrl = $model->getMainImageEvent();

            ?>
            <div>
                <img src="<?= $logoUrl ?>" class="img-fluid">
            </div>

            <div class="d-none d-md-block mt-auto">
                <img src="<?= $wizardAsset->baseUrl ?>/img/riepilogo-evento.png" class="img-fluid">
            </div>

        </div>

    </div>
    <div class="buttons mt-5">
        <?= \yii\helpers\Html::a(AmosEvents::t('amosevents', 'Vai alla Dashbord e pubblica l\'evento'), ['/events/event-dashboard/view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>