<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = AmosEvents::t('amosevents', 'Crea un nuovo evento');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');
$types = $eventTypeSearchModel::searchGenericContextEventTypes()->all();
$moduleCwh = \Yii::$app->getModule('cwh');
$scope = null;
if (!empty($moduleCwh)) {
    $scope = $moduleCwh->getCwhScope();
}

$js = <<<JS
    $('#switch-settings').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#settings-div').show();
        }
        else {
           $('#settings-div').hide();
        }
    });

  $('#switch-community').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#advanced-settings').show();
        }
        else {
           $('#advanced-settings').hide();
        }
    });
JS;
$this->registerJs($js);

?>
<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="mb-0 pr-2 text-muted"><?= (empty(trim($this->title)) ?: $this->title) ?></h3>
        </div>
        <div>
            <div class="step">5</div>
        </div>
    </div>
</div>
<?php $form = ActiveForm::begin(); ?>
<div class="crea-community">

    <div>

        <div class="my-4 d-flex">
            <div class="mr-2">
                <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_account-supervisor-circle"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', "Vuoi aprire una community dell'evento?") ?>
            </h5>
        </div>

        
        <div class="row variable-gutters">


            <div class="col-md-3 mr-auto">
                <p class="text-muted">
                    <?= AmosEvents::t('amosevents', "Puoi permettere agli iscritti all'evento di utilizzare una <strong>community</strong> per aprire discussioni, condividere documenti, messaggi, news.") ?>
                    <br><?= AmosEvents::t('amosevents', "Il link per accedere alla community verrà pubblicizzato ai partecipanti nella email di conferma registrazione.") ?>
                    <br><?= AmosEvents::t('amosevents', "Il pulsante per accedere alla community verrà visualizzato all'interno dell'area riservata nella scheda di dettaglio.") ?>

                </p>
            </div>
            <div class="col-md-8">
                <?php echo $form->field($model, 'show_community')->widget(
                    \kartik\widgets\SwitchInput::className(),
                    [
                        'options' => ['id' => 'switch-community'],

                        'pluginOptions' => [

                            //                    'onText' => AmosIcons::show('check-circle'),
                            //                    'offText' => AmosIcons::show('circle-o')
                            'onText' => 'Si',
                            'offText' => 'No'
                        ],
                    ]
                )->label(AmosEvents::t('amosevents', "Apri una community per l'evento")) ?>
                <div class="d-none d-md-flex mt-auto">
                    <img src="<?= $wizardAsset->baseUrl ?>/img/community-evento.png" class="img-fluid py-5 ml-auto">
                </div>
            </div>

        </div>
    </div>
    <hr class="dashed">
    <?php if($model->show_community){
        $displaynone = '';
    } else {
        $displaynone ='display:none;';
    }?>
    <div id="advanced-settings" class="impostazioni-avanzate my-5" style="<?= $displaynone?>">
        <div class="row">
            <div class="col-md-3 mr-auto pb-3">
                <div class="text-muted d-flex">
                    <div class="mr-2">
                        <svg class="icon icon-secondary">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_settings"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold"><?= AmosEvents::t('amosevents', "Impostazioni avanzate") ?></h5>
                </div>
            </div>
            <div class="col-md-8">
                <?= \kartik\widgets\SwitchInput::widget([
                    'name' => 'settings-on-off',
                    'options' => ['id' => 'switch-settings'],
                    'pluginOptions' => [

                        //                    'onText' => AmosIcons::show('check-circle'),
                        //                    'offText' => AmosIcons::show('circle-o')
                        'onText' => 'Si',
                        'offText' => 'No'
                    ],
                ]) ?>
            </div>
        </div>
        <div id="settings-div" style="display:none" class="p-2 p-sm-5 neutral-1-bg-a1">
            <div class="row">
                <div class="col-md-3 mr-auto">

                </div>
                <div class="col-md-8">
                    <?= \open20\amos\community\widgets\ConfigureDashboardCommunityWidget::widget(['model' => $community, 'hideParticipants' => true]) ?>
                </div>
            </div>

            <hr class="dashed">

            <div class="row">
                <div class="col-md-3 mr-auto">

                </div>
                <div class="col-md-8 pt-5 moderazione-community">
                    <?php echo $form->field($community, 'force_workflow')->widget(
                        \kartik\widgets\SwitchInput::className(),
                        [
                            'pluginOptions' => [

                                //                    'onText' => AmosIcons::show('check-circle'),
                                //                    'offText' => AmosIcons::show('circle-o')
                                'class' => 'd-flex',
                                'onText' => 'Si',
                                'offText' => 'No'
                            ],
                        ]
                    )->label(AmosEvents::t('amosevents', "Attivare moderazione community")) ?>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="buttons row">

    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'viewPreviousBtn' => true,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/wizard/landing', 'id' => $model->id]),

        'cancelUrl' => '',

    ]) ?>

</div>
<?php ActiveForm::end(); ?>