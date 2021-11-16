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

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

?>

<section>
    <?php if ($adminModule->confManager->isVisibleField('presentazione_personale', ConfigurationManager::VIEW_TYPE_FORM)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'presentazione_personale')->textarea([
                    'rows' => 6,
                    'readonly' => false,
                    'maxlength' => true,
                    'placeholder' => AmosAdmin::t('amosadmin', 'Enter a more detailed professional introduction up to 600 characters') . '.'
                ])->label(AmosAdmin::t('amosadmin', 'Professional introduction'), ['class' => 'bold']); ?>
            </div>
        </div>
    <?php endif; ?>
</section>
