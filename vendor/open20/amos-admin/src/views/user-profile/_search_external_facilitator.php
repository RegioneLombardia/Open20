<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\forms\editors\m2mwidget\views
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\utilities\JsUtility;
use open20\amos\admin\AmosAdmin;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var \yii\db\ActiveRecord $model
 * @var string $pjaxContainerId
 * @var string $gridViewContainerId
 * @var string $gridId
 * @var bool $useCheckbox
 */

$post = Yii::$app->getRequest()->post();
$genericSearchFieldId = 'm2mwidget-generic-search-textinput';
$fromGenericSearchFieldId = 'm2mwidget-from-generic-search-hiddeninput';
$resetId = 'm2mwidget-generic-search-reset-btn';
$submitId = 'm2mwidget-generic-search-submit-btn';
$gridId = 'associate-external-facilitator';
$isModal = false;
$useCheckbox = false;


$form = \open20\amos\core\forms\ActiveForm::begin();
?>
<div class="m2mwidget-generic-search">
    <div class="col-xs-12 nop m-15-0">
        <div class="col-sm-6 col-lg-4 nop">
            <!-- TODO Rimuovere hiddenInput fromGenericSearch quando funzionerà il pjax -->
            <?= Html::hiddenInput('fromGenericSearch', 0, [
                'id' => 'fromGenericSearch'
            ]); ?>

            <?= Html::textInput('genericSearch', (isset($post['genericSearch']) ? $post['genericSearch'] : null), [
                'placeholder' => BaseAmosModule::t('amoscore', 'Search') . '...',
                'id' => $gridId . 'associate-external-facilitator-search-field', 'class' => 'form-control'
            ]); ?>
        </div>

        <div class="col-sm-6 col-lg-8">
            <?= Html::a(BaseAmosModule::t('amoscore', 'Reset'), ['/'.AmosAdmin::getModuleName().'/user-profile/send-request-external-facilitator', 'id' => $model->id ],['class' => 'btn btn-secondary', 'id' => $gridId . '-reset-search-btn']) ?>
            <?= Html::submitButton(BaseAmosModule::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary', 'id' => $gridId . '-search-btn']) ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<?php
\open20\amos\core\forms\ActiveForm::end();
?>
