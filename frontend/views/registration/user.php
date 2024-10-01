<?php

use open20\amos\admin\AmosAdmin;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\tag\models\Tag;
use app\models\UserRegistrationForm;
use open20\amos\notificationmanager\models\NotificationsConfOpt;

/**
 * @var $model UserRegistrationForm
 */
// Validazione sulla sezione tag inizialmente disattivata
$checkTagsError = ($model->scenario != $model::WIZARD_SCENARIO);

// In fase di registrazione non va utilizzata l'opzione "mai" nella frequenza email
$removeEmailFrequencyNeverOption = ($model->scenario == $model::WIZARD_SCENARIO);
?>

<style>
    #bk-page .page-header {
        margin-bottom: 0;
    }
    #bk-page{
        
        background-color: #fff;
    }
</style>

<?php if(!$hideTitle) : ?>
    <h1 class="m-b-10"><?= AmosAdmin::t('amosadmin', 'Benvenuto in ') . \Yii::$app->name ?> </h1>
<?php endif; ?>
<p class="m-b-30 h2"><?= $subtitle ?> </p>

<?php
$exitUrl = \yii\helpers\Url::to(['/site/logout', 'redir' =>
    '/Shibboleth.sso/Logout?return=https://idpcwrapper.crs.lombardia.it/PublisherMetadata/Logout?dest=' . urlencode(\yii\helpers\Url::to('/thank-you-page-impostazioni', true))
], true);

$abortUrl = \yii\helpers\Url::to(['/abort-registration', 'redir' =>
    '/Shibboleth.sso/Logout?return=https://idpcwrapper.crs.lombardia.it/PublisherMetadata/Logout?dest=' . urlencode(\yii\helpers\Url::to('/', true))
], true);

if(!Yii::$app->user->isGuest) {
    $abortUrl = $exitUrl;
}
$errorText = AmosAdmin::t('amosadmin', "E' obbligatorio scegliere almeno un tag prima di salvare il profilo");
$js = <<<JS
$(document).on('ready', function(){
    var checkTagsError = '{$checkTagsError}';
    var errorText = "{$errorText}";
    var selector = $('div[class*=interesttagvalues]');
    var errorDiv = selector.find('.help-block-error');
    
    $('input[type="checkbox"]').change(function() {
        if(checkTagsError){
            checkExistsTagsError(errorText);
        }
    });
    $(":submit").on('click', function(e){
        checkTagsError = true;
        if(checkExistsTagsError(errorText)) {
            e.preventDefault();
        }
    });
});

function checkExistsTagsError(errorText) {
    let hasErrors = false;
    $('.field-userregistrationform-interesttagvalues').each(function() {
        if($(this).find('input:checkbox:checked').length != 0) {
            $(this).removeClass('has-error');
            $(this).find('.help-block-error').html('');
        } else {
            $(this).addClass('has-error');
            $(this).find('.help-block-error').html(errorText);
            hasErrors = true;
        }
    });
    return hasErrors;
}
JS;
$this->registerJs($js, \yii\web\View::POS_READY);


$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]);
?>

<div class="registration-form m-b-35">
    <div class="row">
        <div class="col-md-6">
            <?php if ($model->inScenario('name')) : ?>
                <?= $form->field($model, 'name')->textInput([
                    'value' => $model->name ?? ($idmData['nome'] ?? ''),
                    'readonly' => true,
                    'placeholder' => \Yii::t('application', 'Nome')
                ]) ?>
            <?php endif; ?>
        </div>
        <div class="col-md-6">


            <?php if ($model->inScenario('surname')) : ?>
                <?= $form->field($model, 'surname')->textInput([
                    'value' => $model->surname ?? ($idmData['cognome'] ?? ''),
                    'readonly' => true,
                    'placeholder' => \Yii::t('application', 'Cognome')
                ]) ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <?php if ($model->inScenario('email')) : ?>
                <?= $form->field($model, 'email')->textInput([
                    'id' => 'email-id',
                    'readonly' => ($model->scenario == \app\models\UserRegistrationForm::UNSUBSCRIBE_SCENARIO),
                    'placeholder' => \Yii::t('application', 'Email')
                ]) ?>
            <?php endif; ?>
        </div>

    </div>
    <div class="row">
        <?php if ($model->inScenario('tags')) :
            $codiciRoot = [];
            if(empty($codiciRoot)) {
                $idsRoot = Tag::find()->select('root')->distinct()->andWhere(['lvl' => 1])->asArray()->all();
                $idsRoot = \yii\helpers\ArrayHelper::map($idsRoot, 'root', 'root');
            } else {
                $idsRoot = Tag::find()->select('id')->andWhere(['in', 'codice', $codiciRoot])->asArray()->all();
                $idsRoot = \yii\helpers\ArrayHelper::map($idsRoot, 'id', 'id');
            } ?>
            <div class="col-md-12">
                <div class="flexbox align-items-end m-t-30">
                    <h3 class="m-b-0"><?= AmosAdmin::t('amosadmin', 'Scegli gli argomenti di tuo interesse') . ' <span style="color: #a61919">*</span>' ?></h3>
                </div>
                <p class="m-t-20"><strong><?= AmosAdmin::t('amosadmin', 'Seleziona almeno un “Prodotto” ed una “Area di intervento” di tuo interesse per ricevere in email informazioni personalizzate') ?></strong></p>
                <?php
                echo (new \open20\amos\cwh\widgets\CardTagWidget([
                    'model' => $model,
                    'attribute' => 'interestTagValues',
                    'form' => $form,
                    'codiceRoot' => $codiciRoot,
                    'showTagLabel' => true,
                    'selected' => $tags
                ]))->run();
                ?>
                <?= $form->field($model, 'hiddenIdsRoot')->hiddenInput(['value' => json_encode($idsRoot)])->label(false); ?>
            </div>
        <?php endif; ?>

        <div class="col-md-12">
            <?php if ($model->inScenario('emailFrequency')) : ?>
                <p class="m-b-10 m-t-20"><?= AmosAdmin::t('amosadmin', 'Indica la frequenza con cui desideri ricevere email di notifica automatica riguardo i contenuti pubblicati da ' . \Yii::$app->name) ?></p>
                <?php
                $html = Html::beginTag('div');
                $notificationConfOpt = AmosNotify::instance()->createModel('NotificationsConfOpt');
                $frequencyData = $notificationConfOpt::emailFrequencyValueAndLabels();
                if($removeEmailFrequencyNeverOption) {
                    unset($frequencyData[NotificationsConfOpt::EMAIL_OFF]);
                }
                echo $form->field($model, 'email_frequency_id', [
                    'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                ])->widget(Select::classname(), [
                    'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Scegli un\'opzione') . '...', 'disabled' => false],
                    'data' => $frequencyData
                ]);
            endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 m-b-30">


            <?php if ($model->inScenario('delete')) : ?>
                <?= Html::a(
                    AmosAdmin::t('amosadmin', '#delete_user'),
                    ['/user/delete', 'id' => Yii::$app->user->id],
                    [
                        'id' => 'drop-account-btn',
                        'class' => 'text-danger m-t-10',
                        'title' => AmosAdmin::t('amosadmin', '#delete_user'),
                        //'data-confirm' => AmosAdmin::t('amosadmin', '#delete_user_data')
                    ]
                ) ?>
                <p><small><?= Yii::t('amosapp', Yii::t('amosapp', '#deleteUserButtonMessage')); ?></small></p>
            <?php endif; ?>

            <?php if ($model->inScenario('privacy')) : ?>
                <p>Dichiaro di aver preso visione dell'<?= \yii\helpers\Html::a('informativa sul trattamento dei dati personali', Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'], ['title' => AmosAdmin::t('amosadmin', 'Show privacy informations'), 'target' => '_blank']) ?> e acconsento al trattamento di tali dati personali per la finalità indicata.</p>

                <?= $form->field($model, 'privacy')->inline(true)->radioList(
                    [
                        1 => AmosAdmin::t('amosadmin', '#cookie_policy_ok'),
                        0 => AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')
                    ]
                ); ?>
            <?php endif; ?>

            <?php if ($model->inScenario('unsubscribe')) : ?>
                <div class="row">
                    <div class="col-md-12">
                        <p><?= Yii::t('amosapp', '#unsubscribeConfirm') ?></p>
                    </div>
                </div>
            <?php endif; ?>



        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <?= Html::a($model->getButtonLabels('cancel'), $abortUrl, ['class' => 'btn btn-secondary']) ?>
        </div>
        <div class="col-xs-6 text-right">
            
            <?= Html::submitButton($model->getButtonLabels('submit'), ['class' => 'btn btn-navigation-primary']); ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>