<?php

/**
 * @var $model \open20\amos\events\models\Event
 */

use open20\amos\events\AmosEvents;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

$this->title = AmosEvents::t('amosevents', 'Verifica il tuo evento');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$eventLanding = $model->eventLanding;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);



?>


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

<?php $errors = $model->getPublishErrors();

if(!empty($errors)){?>
<div class="alert alert-danger" role="alert">
    <p>
        <?php echo implode("<br>", $errors) ?>
    </p>
</div>
<?php }?>

<div class="my-5">
    <p><?= AmosEvents::t('amosevents', "Il tuo evento è stato creato ma <strong>non è attivo</strong>.<br>
Quando attivi un evento lo pubblichi sulla piattaforma Modulo per la gestione di eventi e delle anagrafiche e lo rendi visibile agli utenti. Prima di attivare il tuo evento, controlla i dati inseriti.  
") ?></p>
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
                    <strong><?= AmosEvents::t('amosevents', "Riepilogo evento") ?></strong>
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
                    <strong><?= AmosEvents::t('amosevents', "Landing page") ?></strong>
                    <br>
                    <?= AmosEvents::t('amosevents', "Data di pubblicazione") . ': ' . "<strong>" .  \Yii::$app->formatter->asDate($model->publication_date_begin) . "</strong>" ?>
                    <br>
                    <?= AmosEvents::t('amosevents', "Url di pubblicazione") . ': ' . "<strong>" .   \open20\amos\events\utility\EventsUtility::getUrlLanding($model)  . "</strong>" ?>
                    <br>
                    <i><?= AmosEvents::t('amosevents', "Contenuti") ?></i>
                    <br>
                    <?= AmosEvents::t('amosevents', "Presentazione") . ': ' . "<strong>" . $presentazione . "</strong>" ?>
                    <br>
                    <?= AmosEvents::t('amosevents', "Programma") . ': ' . "<strong>" . $program . "</strong>" ?>
                    <br>
                    <?= AmosEvents::t('amosevents', "Protagonisti") . ': ' . "<strong>" . $protagonists . "</strong>" ?>
                    <br>
                    <?= AmosEvents::t('amosevents', "Info") . ': ' . "<strong>" . $news . "</strong>" ?>
                    <br>
                    <?= AmosEvents::t('amosevents', "Pics") . ': ' . "<strong>" . $pics . "</strong>" ?>
                    <br>
                    <?= AmosEvents::t('amosevents', "Video") . ': ' . "<strong>" . $video . "</strong>" ?>
                    <br>
                    <?= AmosEvents::t('amosevents', "Streaming") . ': ' . "<strong>" . $streaming . "</strong>" ?>
                    <br>

                </div>
            </div>


            <div class="d-flex align-items-center mb-5">
                <div class="mr-4">
                    <svg class="icon icon-lg icon-primary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                    </svg>
                </div>
                <div>
                    <strong><?= AmosEvents::t('amosevents', "Iscrizioni e registrazioni") ?></strong>
                    <br>
                    <?= AmosEvents::t('amosevents', "Le iscrizioni apriranno il") . ': ' . "<strong>" . \Yii::$app->formatter->asDatetime($model->registration_date_begin) . "</strong>" ?>
                    <br>
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

                        <strong><?= AmosEvents::t('amosevents', "Community") ?></strong>
                        <br>
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
                    <strong><?= AmosEvents::t('amosevents', "Gestione e modifica") ?></strong>
                    <br>
                    <?= AmosEvents::t('amosevents', "Gli utenti a ruolo social riceveranno notifica dell’attivazione del tuo evento.
<br>Verrà attivata la <strong>gestione degli invii</strong> di email agli utenti destinatari. 
<br>Potrai modificare e gestire il tuo evento all’interno della dashboard.")  ?>
                    <br>
                </div>
            </div>

            <div class="mt-5 pt-3">
                <p>
                    <?= AmosEvents::t('amosevents', "Se tutti i dati sono stati inseriti correttamente, attiva il tuo evento. 
                    <br>Potrai disattivarlo in qualsiasi momento. 
<br>Se vuoi continuare a modificare il tuo evento, chiudi questa finestra e torna alla dashboard
") ?>
                </p>
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
        <?php $form = \yii\bootstrap4\ActiveForm::begin(); ?>
        <?php echo \yii\helpers\Html::a(AmosEvents::t('amosevents', 'Annulla'), ['/events/event-dashboard/view', 'id' => $model->id], [
            'class' => 'btn btn-secondary',
            'title' => AmosEvents::t('amosevents', 'Annulla'),
        ]) ?>
        <?= \yii\helpers\Html::submitButton(AmosEvents::t('amosevents', 'Attiva evento'), [
            'class' => 'btn btn-primary',
            'title' => AmosEvents::t('amosevents', 'Finalizza evento'),
            'disabled' => empty($errors) ? false : true,
            'data-confirm' => AmosEvents::t('amosevents', "Sei sicuro di attivare l'evento?")
        ]) ?>
        <?php $form = \yii\bootstrap4\ActiveForm::end(); ?>

    </div>
</div>