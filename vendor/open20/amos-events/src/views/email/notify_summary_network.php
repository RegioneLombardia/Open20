<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\events\AmosEvents;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var Record[] $arrayModels
 */
if (!empty($profile)) {
    $this->params['profile'] = $profile;
}

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
/** @var \open20\amos\events\models\Event $eventModel */
$eventModel = $eventsModule->createModel('Event');

$closestEvent = $eventModel::find()
    ->andWhere(['>', 'begin_date_hour', date('Y-m-d h:s:i')])
    ->orderBy('begin_date_hour ASC')->one();


?>

<div style="box-sizing:border-box;color:#000000;background-color: #414149;width: 100%;">
    <table border="0" cellspacing="0" style="padding:5px 0;background-color: #414149;" align="center">
        <tr>
            <td width="600px">
                <h1 style="color:#ffffff;font-size:1.2em;margin:0;padding: 10px 0;">
                    <?= 'Eventi'?>
                </h1>
            </td>
        </tr>
    </table>
</div>

<div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff;">

    <div style="padding:0;margin:0">
        <h3 style="font-size:2em;line-height: 1;margin:0;padding:10px 0;">
            <?= Html::a($closestEvent->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($closestEvent->getFullViewUrl()), ['style' => 'color: #297A38;']) . ' PROSSIMO'?>
        </h3>
    </div>
    <div style="box-sizing:border-box;font-size:13px;font-weight:normal;color:#000000;">
        <?= $closestEvent->getDescription(true); ?>
    </div>
    <div style="box-sizing:border-box;padding-bottom: 5px;">
        <div style="margin-top:20px; display: flex; padding: 10px;">
            <div style="width: 50px; height: 50px; overflow: hidden;-webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                <?php
                $layout = '{publisher}';
                if ($closestEvent instanceof  open20\amos\cwh\base\ModelContentInterface) {
                    $layout = '{publisher}{publishingRules}{targetAdv}';
                }
                $user = $closestEvent->createdUserProfile;
                ?>

                <?php if (!is_null($user)): ?>
                    <?= \open20\amos\admin\widgets\UserCardWidget::widget([
                        'model' => $user,
                        'onlyAvatar' => true,
                        'absoluteUrl' => true
                    ])
                    ?>
                <?php endif; ?>
            </div>

            <div style="margin-left: 20px; max-width: 430px;">
                <?= \open20\amos\core\forms\PublishedByWidget::widget([
                    'model' => $closestEvent,
                    'layout' => $layout,
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php foreach ($arrayModels as $model){
    if($model->id != $closestEvent->id) {  ?>
    <div style="border:1px solid #cccccc;padding:10px;margin-bottom: 10px;background-color: #ffffff;">

        <div style="padding:0;margin:0">
            <h3 style="font-size:2em;line-height: 1;margin:0;padding:10px 0;">
                <?= Html::a($model->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: #297A38;']) ?>
            </h3>
        </div>
        <div style="box-sizing:border-box;font-size:13px;font-weight:normal;color:#000000;">
            <?= $model->getDescription(true); ?>
        </div>
        <div style="box-sizing:border-box;padding-bottom: 5px;">
            <div style="margin-top:20px; display: flex; padding: 10px;">
                <div style="width: 50px; height: 50px; overflow: hidden;-webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;float: left;">
                    <?php
                    $layout = '{publisher}';
                    if ($model instanceof  open20\amos\cwh\base\ModelContentInterface) {
                        $layout = '{publisher}{publishingRules}{targetAdv}';
                    }
                    $user = $model->createdUserProfile;
                    ?>

                    <?php if (!is_null($user)): ?>
                        <?= \open20\amos\admin\widgets\UserCardWidget::widget([
                            'model' => $user,
                            'onlyAvatar' => true,
                            'absoluteUrl' => true
                        ])
                        ?>
                    <?php endif; ?>
                </div>

                <div style="margin-left: 20px; max-width: 430px;">
                    <?= \open20\amos\core\forms\PublishedByWidget::widget([
                        'model' => $model,
                        'layout' => $layout,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
<?php } ?>
