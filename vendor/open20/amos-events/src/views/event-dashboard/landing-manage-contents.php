<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\events\assets\WizardEventAsset;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = AmosEvents::t('amosevents', "Gestisci i contenuti della landing page");
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$js = <<<JS
    var changedValues = false;
    $('.save-all-data').click(function(e){
        e.preventDefault();
        $('#save_and_redirect_to').val($(this).attr('href'));
        console.log(changedValues);
        if(changedValues){
                   $('#confirm-before-save').modal('show');
        }
        else {
            window.location.href = $(this).attr('href');
        }
    });

    $('#confirm-before-save').on('hidden.bs.modal', function () {
            $('#save_and_redirect_to').val('');
    });
    
    $('#annulla-save').click(function(e){
        e.preventDefault();
        $('#save_and_redirect_to').val('');
        $('#confirm-before-save').modal('hide');
    });
    
    
JS;
$this->registerJs($js);

$tinyMCECallback = <<< JS
    function (editor) {
        editor.on('change', function () {
               changedValues = true;
        });
    }
JS;

$form = ActiveForm::begin(); ?>

    <h3><?= AmosEvents::t('amosevents', 'Evento "{title}"', ['title' => $model->getTitle()]) ?></h3>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($eventLanding, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                'options' => ['placeholder' => AmosEvents::t('amosevents', 'Scrivi una breve presentazione del tuo evento. Usa un massimo di 500 caratteri.')],
                'clientOptions' => [
                    'toolbar' => \open20\amos\events\utility\EventsUtility::getToolbarTextEditor(),
                    'setup' => new \yii\web\JsExpression($tinyMCECallback),
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($eventLanding, 'schedule')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                'options' => ['placeholder' => AmosEvents::t('amosevents', 'Scrivi il programma del tuo evento. Usa un massimo di 1500 caratteri.')],
                'clientOptions' => [
                    'toolbar' => \open20\amos\events\utility\EventsUtility::getToolbarTextEditor(),
                    'setup' => new \yii\web\JsExpression($tinyMCECallback),

                ]
            ]) ?>
        </div>
    </div>
<?php echo $form->field($eventLanding, 'event_id')->hiddenInput()->Label(false); ?>


    <div>
        <h3><?= AmosEvents::t('amosevents', "Protagonisti") ?></h3>
        <div>
            <?php if (!$eventLanding->isNewRecord) { ?>
                <?php echo Html::a(AmosEvents::t('amosevents', 'Aggiungi protagonista'), [
                    '/events/event-landing-protagonist/create', 'idLanding' => $eventLanding->id], [
                    'class' => 'btn btn-secondary btn-sm mb-3 save-all-data'
                ]) ?>
                <?= \open20\amos\core\views\AmosGridView::widget(
                    [
                        'dataProvider' => $dataProviderProtagonists,
                        'columns' => [
                            'immagine' => [
                                'label' => AmosEvents::t('amosevents', 'Immagine'),
                                'format' => 'html',
                                'value' => function ($model) {
                                    /** @var News $model */
                                    $url = '/img/img_default.jpg';
                                    if (!is_null($model->image)) {
                                        $url = $model->image->getWebUrl('table_small', false, true);
                                    }
                                    $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => AmosEvents::t('amosnews', 'Immagine della notizia')]);

                                    return Html::a($contentImage, []);
                                }
                            ],
                            'name',
                            'surname',
                            'company',
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                'controller' => 'event-landing-protagonist',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                                        $iconModifica = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_edit></use>";
                                        $svgIconModifica = Html::tag('svg', $iconModifica, ['class' => 'icon icon-white']);
                                        $spanSvgIconModifica = Html::tag('span', $svgIconModifica, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Modifica'), ['class' => 'sr-only']);
                                        return Html::a($spanSvgIconModifica, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Modifica'], true);
                                    },
                                    'delete' => function ($url, $model) {
                                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                                        $iconDelete = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_close></use>";
                                        $svgIconDelete = Html::tag('svg', $iconDelete, ['class' => 'icon icon-white']);
                                        $spanSvgIconDelete = Html::tag('span', $svgIconDelete, ['class' => 'rounded-icon rounded-danger p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Elimina'), ['class' => 'sr-only']);
                                        return Html::a($spanSvgIconDelete, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Elimina'], true);
                                    }
                                ]
                            ]
                        ]
                    ]
                ) ?>
            <?php } else { ?>
                <p><?= AmosEvents::t('amosevents', 'Per inserire i protagonisti è necessario salvare i dati') ?></p>
            <?php } ?>
        </div>
    </div>

    <hr class="dashed my-5">

    <div>
        <h3><?= AmosEvents::t('amosevents', "Pics") ?></h3>
        <div>
            <?php if (!$eventLanding->isNewRecord) { ?>
                <?= $this->render('_sliders', [
                    'model' => $model,
                    'landing' => $eventLanding,
                    'dataProviderSliderElemImage' => $dataProviderSliderElemImage,
                ]) ?>
            <?php } else { ?>
                <p><?= AmosEvents::t('amosevents', 'Per inserire le immagini nella gallery è necessario salvare i dati.') ?></p>
            <?php } ?>
        </div>
    </div>

    <hr class="dashed my-5">
    <div>
        <h3><?= AmosEvents::t('amosevents', "Video") ?></h3>
        <div>
            <?php if (!$eventLanding->isNewRecord) { ?>
                <?= $this->render('_sliders_video', [
                    'model' => $model,
                    'landing' => $eventLanding,
                    'dataProviderSliderElemVideo' => $dataProviderSliderElemVideo

                ]) ?>
            <?php } else { ?>
                <p><?= AmosEvents::t('amosevents', 'Per inserire i video è necessario salvare i dati.') ?></p>
            <?php } ?>
        </div>
    </div>

    <hr class="dashed my-5">

    <div>
        <h3><?= AmosEvents::t('amosevents', "Info") ?></h3>
        <div>
            <?php if (!$eventLanding->isNewRecord) { ?>
                <?= $this->render('_parts/_news', [
                    'model' => $model,
                    'landing' => $eventLanding,
                    'dataProviderNews' => $dataProviderNews
                ]) ?>
            <?php } else { ?>
                <p><?= AmosEvents::t('amosevents', 'Per aggiungere le notizie é necessario salvare i dati.') ?></p>
            <?php } ?>
        </div>
    </div>

<?php
/**
 * Streaming enabled for Informative and Public and open to all
 */
?>
<?php if ($model->eventType->event_type == \open20\amos\events\models\EventType::TYPE_INFORMATIVE || ($model->eventType->event_type == \open20\amos\events\models\EventType::TYPE_OPEN && $model->eventType->limited_seats == false)) { ?>
    <hr class="dashed my-5">

    <div id="streaming">
        <h3><?= AmosEvents::t('amosevents', "Streaming") ?></h3>
        <div>
            <?php if (!$eventLanding->isNewRecord) { ?>
                <?= $this->render('_parts/_streaming', [
                    'model' => $model,
                    'landing' => $eventLanding,
                    'form' => $form
                ]) ?>
            <?php } else { ?>
                <p><?= AmosEvents::t('amosevents', 'Per aggiungere le notizie é necessario salvare i dati.') ?></p>
            <?php } ?>
        </div>
    </div>
<?php } ?>


<?php echo Html::hiddenInput('save_and_redirect_to', null, ['id' => 'save_and_redirect_to']) ?>
    <div class="buttons mt-5 d-flex">
        <?= Html::submitButton(AmosEvents::t('amosevents', 'Save'), ['class' => 'btn btn-primary ml-auto']) ?>
    </div>

<?php \yii\bootstrap4\Modal::begin([
    'id' => 'confirm-before-save',
    'size' => \yii\bootstrap4\Modal::SIZE_LARGE,

]); ?>

    <h5><?= AmosEvents::t('amosevents', "Verranno salvate le modifiche apportate alla scheda, confermi?") ?></h5>
    <div class="col-md-12 mt-5">
        <?= Html::a(AmosEvents::t('amosevents', "Annulla"), '', [
                'class' => 'btn btn-secondary',
                'id' => 'annulla-save'
            ]
        ); ?>
        <?= Html::submitButton(AmosEvents::t('amosevents', "Conferma"), [
                'class' => 'btn btn-primary pull-right',
                'id' => 'confirm-save'
            ]
        ); ?>
    </div>
<?php \yii\bootstrap4\Modal::end(); ?>
<?php ActiveForm::end(); ?>