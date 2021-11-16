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
use open20\amos\core\icons\AmosIcons;
use open20\amos\notificationmanager\widgets\NotifyFrequencyWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

?>
    <h2><?= AmosAdmin::t('amosadmin', 'Configure notifications') . '.' ?></h2>
    <p><?= AmosAdmin::t('amosadmin', 'If the frequency is not indicated, you will receive the notifications as automatically set by the system') . '.' ?></p>
    <div class="col-xs-12 nop m-t-15">
        <?= \open20\amos\notificationmanager\widgets\NotifyFrequencyAdvancedWidget::widget([
            'model' => $model
        ]) ?>
    </div>

