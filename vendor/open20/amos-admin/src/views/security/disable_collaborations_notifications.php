<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\forms\ActiveForm;

$this->title = AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_title');
$this->params['breadcrumbs'][] = $this->title;

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12 nop">
        <h4 class="subtitle-login"><?= AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_text'); ?></h4>
        <?php $form = ActiveForm::begin(['id' => 'disable-collaborations-notifications']); ?>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::submitButton(
                        AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_submit_button'),
                        [
                            'class' => 'btn btn-primary btn-administration-primary m-t-20',
                            'name' => 'disable-collaborations-notifications',
                            'value' => 1,
                            'title' => AmosAdmin::t('amosadmin', '#disable_collaborations_notifications_submit_button_title')]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


</div>
