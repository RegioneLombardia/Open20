<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\socialwall\models\SocialwallTokensFacebookAccounts;
use open20\socialwall\Module;

$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/socialwall']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('socialwall', 'Socialwall Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('socialwall', 'Scegli account');

$dummyModel = new SocialwallTokensFacebookAccounts();

?>

<?php
$form = ActiveForm::begin([
    'options' => [
        'id' => 'socialwall-facebook-accounts-selection-form_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
    ],
    'action' => '/socialwall/socialwall-tokens/save-facebook-accounts-selection',
]);
?>

<div class="row">
    <div class="col-xs-12">

        <h2>Seleziona gli account da associare a <?= $accountsList['socialwallTokensName']; ?></h2>

        <?= \yii\helpers\Html::hiddenInput('long_access_token', $accountsList['longAccessToken']); ?>
        <?= \yii\helpers\Html::hiddenInput('expire_date', $accountsList['expire_date']); ?>
        <?= \yii\helpers\Html::hiddenInput('socialwall_tokens_id', $accountsList['socialwallTokensId']); ?>

        <?php foreach ($accountsList['accounts'] as $account) : ?>

            <?= \yii\helpers\Html::hiddenInput("Accounts[{$account['id']}][id]", $account['id']); ?>
            <?= \yii\helpers\Html::hiddenInput("Accounts[{$account['id']}][name]", $account['name']); ?>
            <?= \yii\helpers\Html::hiddenInput("Accounts[{$account['id']}][access_token]", $account['access_token']); ?>

            <div class="form-group">
                <div class="checkbox">
                    <label for="socialwalltokens-fb<?= $account['id'] ?>">
                        <input type="checkbox" id="socialwalltokens-fb<?= $account['id'] ?>" name="Accounts[<?= $account['id'] ?>][selected]" value="1">
                        <?= $account['name']; ?> <?php if (!empty($account['privateProfile']) && $account['privateProfile']) { echo ' (' . Module::t('socialwall', 'Profilo Privato') . ')'; } ?>
                    </label>
                </div>
            </div>


        <?php endforeach; ?>

        <?= CloseSaveButtonWidget::widget(['model' => $dummyModel]); ?>
    </div>

    <div class="col-md-4 col xs-12"></div>

</div>

<div class="clearfix"></div>

<?php ActiveForm::end(); ?>
