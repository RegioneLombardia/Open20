<?php

use open20\amos\sondaggi\AmosSondaggi;
//use open20\amos\sondaggi\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\models\SondaggiInvitations;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\forms\ActiveForm;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use yii\helpers\Html;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\sondaggi\models\SondaggiComunication;
use open20\amos\core\forms\TextEditorWidget;


/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiInvitations $model
 */

//$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$moduleSondaggi = Yii::$app->getModule('sondaggi');
$isCommunitySurvey = $model->sondaggi->isCommunitySurvey();

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$this->registerCss("
#errore-alert-common {display:none;}
");

?>


<div class="utenti">
    <?php
    $form = ActiveForm::begin([
            'options' => ['id' => 'form-search']
    ]);
    ?>
    <div>

        <?php
        // Se sono attivi gli inviti per gli utenti della piattaforma e per le organizzazioni
        if ($moduleSondaggi->enableInvitationsForPlatformUsers && $moduleSondaggi->enableInvitationsForOrganizations) {
            // Se non è un sondaggio di community
            if (!$isCommunitySurvey) { ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'target')->widget(Select2::classname(), [
                            'data' => [
                                SondaggiInvitations::TARGET_USERS => AmosSondaggi::t('amossondaggi', 'Utenti'),
                                SondaggiInvitations::TARGET_ORGANIZATIONS => AmosSondaggi::t('amossondaggi', 'Organizzazioni'),
                            ],
                            'hideSearch' => true,
                            'options' => [
                                'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona a chi inviare la comunicazione...'),
                                'id' => 'target-select',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $typeData = [];
                        if (!$model->isNewRecord) {
                            $typeData = $model->getFilterData();
                        }
                        ?>
                        <?= $form->field($model, "type")->widget(\kartik\depdrop\DepDrop::className(), [
                            'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
                            'data' => $typeData,
                            'options' => [
                                'id' => 'type-select',
                            ],
                            'pluginOptions' => [
                                'url' => \yii\helpers\Url::to(['/sondaggi/dashboard/communication-filter-values']),
                                'depends' => ['target-select'],
                                'placeholder' => AmosSondaggi::t('amossondaggi', "#select"),
                            ],
                            'select2Options' => [
                                'pluginOptions' => [
                                    'allowClear' => false,
                                ],
                            ]
                        ]); ?>

                    </div>
                </div>
            <!-- Se è un sondaggio di community -->
            <?php } else { ?>
                <div class="row">

                    <span class="hidden">
                            <?= $form->field($model, 'target')->hiddenInput(['value' => SondaggiInvitations::TARGET_USERS])->label(false); ?>
                        </span>

                    <div class="col-md-12">
                        <?= $form->field($model, 'type')->widget(Select2::classname(), [
                            'data' => $model->getFilterData(SondaggiInvitations::TARGET_USERS, true),
                            'hideSearch' => true,
                            'options' => [
                                'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona a chi inviare la comunicazione...'),
                                'id' => 'target-select',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]); ?>
                    </div>

                </div>
            <?php
            }
        } else {
            if ($moduleSondaggi->enableInvitationsForPlatformUsers && !$moduleSondaggi->enableInvitationsForOrganizations || $isCommunitySurvey) {
                $targetValue = SondaggiInvitations::TARGET_USERS;
                if (!$isCommunitySurvey) {
                    $typeData = $model->getFilterData(SondaggiInvitations::TARGET_USERS);
                } else {
                    $typeData = $model->getFilterData(SondaggiInvitations::TARGET_USERS, true);
                }
            }
            else if (!$moduleSondaggi->enableInvitationsForPlatformUsers && $moduleSondaggi->enableInvitationsForOrganizations) {
                $targetValue = SondaggiInvitations::TARGET_ORGANIZATIONS;
                $typeData = $model->getFilterData(SondaggiInvitations::TARGET_ORGANIZATIONS);
            }
            ?>
            <div class="row">

                <span class="hidden">
                    <?= $form->field($model, 'target')->hiddenInput(['value' => $targetValue])->label(false); ?>
                </span>

                <div class="col-md-12">
                    <?= $form->field($model, 'type')->widget(Select2::classname(), [
                        'data' => $typeData,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona a chi inviare la comunicazione...'),
                            'id' => 'target-select',
                        ],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]); ?>
                </div>

            </div>
        <?php } ?>

    </div>

    <div>
        <?=
        $form->field($model, 'name')->textInput(['placeholder' => AmosSondaggi::t('AmosSondaggi',
                'Assegna un titolo alla tua ricerca')])->label(AmosSondaggi::t('AmosSondaggi', 'Titolo della ricerca'))
        ?>
    </div>
    <hr/>
    <div>
        <?=
        $form->field($model, 'subject')->textInput(['placeholder' => AmosSondaggi::t('AmosSondaggi',
                'Soggetto della mail')])->label(AmosSondaggi::t('AmosSondaggi', 'Soggetto della mail'))
        ?>
    </div>
    <div>
        <?=
        $form->field($model, 'message')->widget(TextEditorWidget::className(),
            [
            'clientOptions' => [
                'placeholder' => AmosSondaggi::t('amossondaggi', '#insert_text'),
                'lang' => substr(Yii::$app->language, 0, 2)
            ]
        ])->label(AmosSondaggi::t('amossondaggi', 'Testo della mail'))
        ?>
    </div>
    <hr/>

    <div>
        <?=
        $form->field($model, 'email_test')->textInput(['placeholder' => AmosSondaggi::t('AmosSondaggi', 'Email di test')])->label(AmosSondaggi::t('amossondaggi',
                'Email di test'))
        ?>
    </div>

    <div id="form-actions" class="bk-btnFormContainer">
        <?=
        CloseSaveButtonWidget::widget([
            'model' => $model,
            'buttonNewSaveLabel' => $model->isNewRecord ? AmosSondaggi::t('amossondaggi', 'Inserisci') : AmosSondaggi::t('amossondaggi',
                    'Salva'),
            'closeButtonLabel' => AmosSondaggi::t('amossondaggi', 'Chiudi'),
        ]);
        ?>
    </div>

    <?= $form->field($model, 'sondaggi_id')->hiddenInput()->label(false) ?>
    <?php
    // Se sono attivi solo gli inviti per utenti o se è un sondaggio di community, il target è sempre UTENTI
    if (($moduleSondaggi->enableInvitationsForPlatformUsers && !$moduleSondaggi->enableInvitationsForOrganizations) || $isCommunitySurvey) {
        echo $form->field($model, 'target')->hiddenInput(['value' => SondaggiInvitations::TARGET_USERS])->label(false);
    }
    // Se sono attivi solo gli inviti per organizzazioni, il target è sempre ORGANIZZAZIONI
    else if (!$moduleSondaggi->enableInvitationsForPlatformUsers && $moduleSondaggi->enableInvitationsForOrganizations) {
        if (!$isCommunitySurvey) {
            echo $form->field($model, 'target')->hiddenInput(['value' => SondaggiInvitations::TARGET_ORGANIZATIONS])->label(false);
        }
    }
    ?>
    <?php ActiveForm::end(); ?>

</div>
