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


/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiInvitations $model
 */

//$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
\open20\amos\layout\assets\LoadingSpinnerAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */

$moduleSondaggi = Yii::$app->getModule('sondaggi');
if (empty($model->type)) $model->type = 0;
if (empty($model->filter_type)) $model->filter_type = 0;
$filtersOrganizations = $moduleSondaggi->invitationsOrganizationsFilterTypes;
if (!$filtersOrganizations['groups'] && $model->isNewRecord) {
    if ($filtersOrganizations['invited_tag']) {
        $model->filter_type = SondaggiInvitationsSearch::FILTER_INVITED_TAG;
    } else if ($filtersOrganizations['compiled_tag']) {
        $model->filter_type = SondaggiInvitationsSearch::FILTER_COMPILED_TAG;
    }
}
$targetUsers = SondaggiInvitations::TARGET_USERS;
$targetOrganizations = SondaggiInvitations::TARGET_ORGANIZATIONS;

$isCommunitySurvey = $model->sondaggi->isCommunitySurvey();

$this->registerCss("
#errore-alert-common {display:none;}
");

$js = <<<JS

     $(document).on('click', '#form-result button[type="submit"]', function(){
         $("#form-result").yiiActiveForm("validate");
     });

    var targetUsers = {$targetUsers};
    var targetOrganizations = {$targetOrganizations};
    $('#target-select').on('change', function(){
        if($(this).val() == targetUsers) {
            $('.invitations-users-container').show();
            $('.invitations-organizations-container').hide();
            if ($('#form-results')) {
                $('#form-results').remove();
            }
        } else if ($(this).val() == targetOrganizations) {
            $('.invitations-users-container').hide();
            $('.invitations-organizations-container').show();
            if ($('#form-results')) {
                $('#form-results').remove();
            }
        }
    });

JS;
$this->registerJs($js);

?>

<!-- SELECT 2 PLACEHOLDER FIX -->
<style>
.select2-container .select2-search--inline {
    width: 100%;
}
.select2-container .select2-search--inline > input {
    min-width: 100% !important;
}
</style>

<div class="utenti">
    <?php
    $form = ActiveForm::begin([
        'options' => ['id' => 'form-search']
    ]);
    ?>

    <div>
        <?php
        if ($moduleSondaggi->enableInvitationsForPlatformUsers && $moduleSondaggi->enableInvitationsForOrganizations) {
            if (!$isCommunitySurvey) {
                echo $form->field($model, 'target')->widget(Select2::classname(), [
                    'data' => [
                        SondaggiInvitations::TARGET_USERS => AmosSondaggi::t('amossondaggi', 'Utenti'),
                        SondaggiInvitations::TARGET_ORGANIZATIONS => AmosSondaggi::t('amossondaggi', 'Organizzazioni'),
                    ],
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona a chi inviare l\'invito...'),
                        'id' => 'target-select',
                    ],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
            }
        } else { ?>
            <span class="hidden">
                <?php
                // Se sono attivi solo gli inviti per utenti o se è un sondaggio di community, il target è sempre UTENTI
                if (($moduleSondaggi->enableInvitationsForPlatformUsers && !$moduleSondaggi->enableInvitationsForOrganizations) || $isCommunitySurvey) {
                    echo $form->field($model, 'target')->hiddenInput(['value' => SondaggiInvitations::TARGET_USERS])->label(false);
                } // Se sono attivi solo gli inviti per organizzazioni, il target è sempre ORGANIZZAZIONI
                else if (!$moduleSondaggi->enableInvitationsForPlatformUsers && $moduleSondaggi->enableInvitationsForOrganizations) {
                        echo $form->field($model, 'target')->hiddenInput(['value' => SondaggiInvitations::TARGET_ORGANIZATIONS])->label(false);
                }
                ?>
            </span>
        <?php
        }
        ?>
    </div>

    <?php
    if ($moduleSondaggi->enableInvitationsForPlatformUsers || $isCommunitySurvey) {
        echo $this->render('parts/_invitations_users', [
            'model' => $model,
            'isCommunitySurvey' => $isCommunitySurvey,
            'form' => $form,
        ]);
    } ?>

    <?php
    if ($moduleSondaggi->enableInvitationsForOrganizations && !$isCommunitySurvey) {
        echo $this->render('parts/_invitations_organizations', [
            'model' => $model,
            'form' => $form,
            'filters' => $filtersOrganizations,
        ]);
    } ?>

    <div id="result-search-container">

    </div>

    <div>
        <?= $form->field($model, 'name')->textInput(['placeholder' =>  AmosSondaggi::t('amossondaggi', 'Assegna un titolo alla tua ricerca')])->label(AmosSondaggi::t('amossondaggi', 'Titolo della ricerca')) ?>
    </div>

    <div id="form-actions" class="bk-btnFormContainer">
        <?= CloseSaveButtonWidget::widget([
            'model' => $model,
            'buttonNewSaveLabel' => $model->isNewRecord ? AmosSondaggi::t('amossondaggi', 'Inserisci') : AmosSondaggi::t('amossondaggi', 'Salva'),
            'closeButtonLabel' => AmosSondaggi::t('amossondaggi', 'Chiudi'),
        ]); ?>
    </div>

    <span class="hidden">
        <?= $form->field($model, 'sondaggi_id')->hiddenInput()->label(false) ?>
    </span>

    <?php ActiveForm::end(); ?>

</div>