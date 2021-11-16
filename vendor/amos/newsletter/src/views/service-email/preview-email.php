<?php
/**
 * @var $email
 */

use amos\newsletter\Module;
use yii\helpers\Html;

$this->title = Module::t('amosnewsletter', 'Preview email');
$this->params['breadcrumbs'][] = $this->title;
$module = \Yii::$app->getModule('newsletter');
?>

<div class="col-xs-12">
    <div class="col-xs-12">
        <h3>
            <strong><?= Module::t('amosnewsletter', 'Subject') . ':' ?></strong>
            <?= $email->Subject ?>
        </h3>
    </div>
    <div class="col-xs-12 m-b-20">
        <h3><strong><?= Module::t('amosnewsletter', 'Content') . ':' ?></strong></h3>
        <?= $email->Content ?>
    </div>

    <hr>
    <div class="col-xs-12">
        <h2><?= Module::t('amosnewsletter', 'Other options') ?></h2>
        <div class="col-xs-12">
            <h3>
                <strong><?= Module::t('amosnewsletter', 'Use dynamic fields') . ':' ?></strong>
                <?= \Yii::$app->formatter->asBoolean($email->UseDynamicField) ?>
                <?php if ($email->UseDynamicField) {
                    echo Html::a(Module::t('amosnewsletter', 'Disable'), ['enable-disable-dynamic-fields-email', 'idList' => $email->idList, 'idMessage' => $email->idMessage, 'enable' => 'false'], [
                        'class' => 'btn btn-primary'
                    ]);
                } else {
                    echo Html::a(Module::t('amosnewsletter', 'Enable'), ['enable-disable-dynamic-fields-email', 'idList' => $email->idList, 'idMessage' => $email->idMessage], [
                        'class' => 'btn btn-primary'
                    ]);
                } ?>
            </h3>
        </div>
        <div class="col-xs-12">
            <h3>
                <strong><?= Module::t('amosnewsletter', 'Fields') . ':' ?></strong>
            </h3>
            <ul>
                <?php foreach ($email->Fields as $field) { ?>
                    <li><?= $field->Description ?></li>
                <?php } ?>
            </ul>

        </div>
    </div>
</div>


<div class="col-xs-12 m-t-20">
    <div class="col-xs-12">
        <?= Html::a('Back', ['email-templates', 'idList' => $email->idList], ['class' => 'btn btn-primary']) ?>
    </div>
</div>


