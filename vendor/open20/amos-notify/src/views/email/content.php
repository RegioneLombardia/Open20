<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var \open20\amos\admin\models\UserProfile $profile
 */

if (!empty($profile)) {
    $this->params['profile'] = $profile;
}
$notifyModule = \open20\amos\notificationmanager\AmosNotify::instance();
?>

<div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff;">
    <div style="padding:0;margin:0">
        <h3 style="font-size:2em;line-height: 1;margin:0;padding:10px 0;">
            <?= Html::a($model->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: '.$notifyModule->mailThemeColor['bgPrimary'] .';']) ?>        </h3>
    </div>
    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;color:#000000;">
        <?= $model->getDescription(true); ?>
    </div>
    <div style="box-sizing:border-box;padding-bottom: 5px;">
        <div style="margin-top:20px; display: flex; padding: 10px;">
            <?= ItemAndCardHeaderWidget::widget([
                'model' => $model,
                'publicationDateNotPresent' => true,
                'showPrevalentPartnershipAndTargets' => true,
                'absoluteUrlAvatar' => true,
            ]); ?>
        </div>
    </div>
</div>
