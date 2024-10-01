<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfileAgeGroup;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var string $idTabInsights
 */
/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;
?>
<section>
    <div class="row">
        <?php if ($adminModule->confManager->isVisibleFieldInForm('email_istituzionale')): ?>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'email_istituzionale')->textInput(['maxlength' => 255, 'readonly' => false]) ?>
            </div>
        <?php endif; ?>
        <?php if ($adminModule->confManager->isVisibleFieldInForm('codice_ipa')): ?>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'codice_ipa')->textInput(['maxlength' => 255, 'readonly' => false]) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if ($adminModule->confManager->isVisibleFieldInForm('codice_ipa')): ?>
            <div class="col-xs-12 col-md-12">
                <?= $form->field($model, 'correttezza_info')->checkbox()->label(AmosAdmin::t('amosadmin','Ho verificato la correttezza delle informazioni')) ?>
            </div>
        <?php endif; ?>
    </div>
  
</section>
