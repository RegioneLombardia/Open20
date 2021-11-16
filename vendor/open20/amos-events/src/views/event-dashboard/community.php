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

$this->title = AmosEvents::t('amosevents', 'Modifica comunity');
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

$css = <<<CSS
    tr.filters{
        display:none;
    }
CSS;
$this->registerCss($css);
?>

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
                        <?= AmosEvents::t('amosevents', "Una community è uno spazio di condivisione di discussioni, documenti, messaggi e notizie. L’accesso alla community è riservato agli utenti registrati al tuo evento.") ?>
                        <br><br><?= AmosEvents::t('amosevents', "Se apri una community, puoi pubblicizzarla inviando il link di accesso nella mail di conferma della registrazione.") ?>
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
                    <?= $form->field($model, 'advanced_options_community')->widget(\kartik\widgets\SwitchInput::className(),[
                        'options' => ['id' => 'switch-settings'],
                        'pluginOptions' => [
                            //'size' => 'mini',
                            //                    'onText' => AmosIcons::show('check-circle'),
                            //                    'offText' => AmosIcons::show('circle-o')
                            'onText' => '+',
                            'offText' => '-'
                        ],
                    ])->label(false);?>
                </div>
            </div>
            <?php $hideAdvanced = 'display:none';
            if($model->advanced_options_community){
                $hideAdvanced = '';
            }
            ?>
            <div id="settings-div" style="<?= $hideAdvanced ?>" class="p-2 p-sm-5 neutral-1-bg-a1">
                <?php echo $form->field($community, 'force_workflow')->widget(
                    \kartik\widgets\SwitchInput::className(),
                    [
                        'pluginOptions' => [

                            //                    'onText' => AmosIcons::show('check-circle'),
                            //                    'offText' => AmosIcons::show('circle-o')
                            'onText' => 'Si',
                            'offText' => 'No'
                        ],
                    ]
                )->label(AmosEvents::t('amosevents', "Attiva la moderazione della community")) ?>
                <hr class="dashed">
                <?php
                $defaultWidgetSelected = [];
                if(!empty($defaultWidgets)){
                    foreach ($defaultWidgets as $widget) {
                        $defaultWidgetSelected [] = $widget->id;
                    }
                } ?>

                <?= \open20\amos\community\widgets\ConfigureDashboardCommunityWidget::widget(['model' => $community, 'hideParticipants' => true, 'defaultWidgetSelected' => $defaultWidgetSelected]) ?>

            </div>
        </div>

    </div>
    <div class="buttons d-flex">
        <?= Html::submitButton(AmosEvents::t('amosevents', 'Save'), ['class' => 'btn btn-primary ml-auto']) ?>
    </div>
<?php ActiveForm::end(); ?>