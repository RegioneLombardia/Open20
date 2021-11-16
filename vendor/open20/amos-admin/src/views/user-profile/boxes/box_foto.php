<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\attachments\components\CropInput;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

?>

<?php if ($adminModule->confManager->isVisibleField('userProfileImage', ConfigurationManager::VIEW_TYPE_FORM)): ?>
    <?= $form->field($model,'userProfileImage')->widget(CropInput::classname(), [
        'enableUploadFromGallery' => false,
        'jcropOptions' => [ 'aspectRatio' => '1']
    ])->label(AmosAdmin::t('amosadmin', '#image_field'))->hint(AmosAdmin::t('amosadmin', '#image_field_hint')); ?>
<?php endif; ?>
