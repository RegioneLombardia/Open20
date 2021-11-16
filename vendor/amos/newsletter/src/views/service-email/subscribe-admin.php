<?php

use open20\amos\core\forms\ActiveForm;
use amos\newsletter\Module;

if($idGroup){
    $this->title = \amos\newsletter\Module::t('amosnewsletter', 'Subscribe to group').' '. $idGroup;
} else {
    $this->title = \amos\newsletter\Module::t('amosnewsletter', 'Subscribe to list') . ' ' . $idList;
}
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
if(!empty($isUpdate)){
    $action =  ['/newsletter/service-email/update-detail-user', 'idRecipient' => \Yii::$app->request->get('idRecipient') ];
    $textSubmit = Module::t('amosnewletter', 'Save');
} else {
    $action =  ['/newsletter/service-email/subscribe-admin', 'idList' => $idList, 'idGroup' => $idGroup ];
    $textSubmit = Module::t('amosnewletter', 'Subscribe');
}

$form = ActiveForm::begin([
    'action' => $action
]);
?>
<div class="newsletter-fields">
    <div class="col-xs-12 nop m-t-20">
        <div class="col-xs-6">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'email') ?>
        </div>
    </div>

    <div class="col-xs-12">
        <h3>Altri dati</h3>
    </div>
    <?php foreach ((Array)$fields as $field) { ?>
        <div class="col-xs-6">
            <?php echo $form->field($model, 'fields[' . $field->Id . ']')->label($field->Description) ?>
        </div>
    <?php } ?>


    <div class="col-xs-12">
        <?php echo $form->field($model, 'privacy')->checkbox()
            ->label('<a data-toggle="modal" data-target="#modalPrivacy">' . \amos\newsletter\Module::t('amosnewsletter', 'Visualizza e accetta il documento della privacy') . '</a>')
            ->label("<a  >" . \amos\newsletter\Module::t('amosnewsletter', "Dichiaro di aver letto l'informativa.") . "</a>") ?>
    </div>

    <div hidden>
        <?php echo  $form->field($model, 'redirect_url')->hiddenInput() ?>
    </div>

    <div class="newsletter-button col-xs-12">
        <?= \yii\helpers\Html::submitButton($textSubmit, ['class' => 'btn btn-navigation-primary pull-right']) ?>
    </div>

    <?php ActiveForm::end();?>
</div>