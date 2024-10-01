<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;
use open20\amos\events\AmosEvents;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var \open20\amos\events\models\Event $closeEvent
 * @var Record[] $arrayModels
 */
$user_id = null;
if (!empty($profile)) {
    $this->params['profile'] = $profile;
    $user_id = $profile->user_id;
}

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
/** @var \open20\amos\events\models\Event $eventModel */
$eventModel = $eventsModule->createModel('Event');

$firstDate = null;
foreach ((Array) $arrayModels as $model) {
    if (empty($firstDate)) {
        $closestEvent = $model;
    } else {
        if (strtotime($model->begin_date_hour) < strtotime($closestEvent->begin_date_hour)) {
            $closestEvent = $model;
        }
    }
}
$beginDateHour = !empty($closestEvent->begin_date_hour) ? new \DateTime($closestEvent->begin_date_hour) : null;
$logoUrl = '/img/img_default.jpg';
if (!empty($closestEvent->eventLogo)) {
    $logoUrl = $closestEvent->eventLogo->getWebUrl();
}
$logoUrl = Yii::$app->urlManager->createAbsoluteUrl($logoUrl);
?>

<?php
if (!empty($closestEvent)) {
    $text = \open20\amos\events\AmosEvents::t('amosevents', 'Iscriviti');
    if (!empty($user_id)) {
        if ($closestEvent->isUserSubscribedToEvent($user_id)) {
            $text = \open20\amos\events\AmosEvents::t('amosevents', 'Accedi');
        }
    }
    ?>
    <tr>
        <td colspan="2" style="padding:15px;" bgcolor="#454545">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">

                <tr>
                    <td style="background:#297A38" width="30%">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td width="100%" valign="top" style="font-family: sans-serif; mso-height-rule: exactly; color: #FFFFFF; padding: 10px; text-align: center; text-transform: uppercase; font-size:18px;"><strong style="font-size:46px; line-height: 1"><?= !empty($beginDateHour) ? $beginDateHour->format('d') : '' ?></strong>
                                    <br>
                                    <?= !empty($beginDateHour) ? Yii::$app->formatter->asDate($beginDateHour, 'MMM') : '' ?>&nbsp;
                                    <?= !empty($beginDateHour) ? Yii::$app->formatter->asDate($beginDateHour, 'YYY') : '' ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="70%">
                        <img src="<?= $logoUrl ?>" width="400" border="0" align="center" style="width: 100%; max-width:395px; min-height: 130px; object-fit: cover">
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Hero Image, Flush : END -->
    <!-- CORPO EVENTO : BEGIN -->
    <tr>
        <td colspan="2" style="background: #454545; padding: 0 15px 15px 15px; text-align: center; font-family: sans-serif;  mso-height-rule: exactly;  color: #FFFFFF; text-align: left">
            <a href="<?=Yii::$app->urlManager->createAbsoluteUrl($closestEvent->getFullViewUrl())?>" style="color: #FFFFFF; text-decoration:none; font-size: 24px; line-height: 28px; font-weight: bold; padding-right:3px;"><?= $closestEvent->getTitle() ?></a><span style="display: inline-block;

                                                                                                                                                                                          margin: 2px;
                                                                                                                                                                                          text-transform: uppercase;
                                                                                                                                                                                          color: #7CC588;
                                                                                                                                                                                          border: 1px solid #7CC588;

                                                                                                                                                                                          line-height: 16px;
                                                                                                                                                                                          font-size: 10px;
                                                                                                                                                                                          vertical-align:top;
                                                                                                                                                                                          padding: 1px 5px;" class="tag"><?= \open20\amos\events\AmosEvents::t('amosevents', 'Prossimo evento') ?></span>
            <table width="100%" style="color:#CAC8C8; line-height: normal;">
                <tr style="font-size: 14px; padding: 0px; margin-top: 5px;">
                    <td style="padding-top:5px; font-family: sans-serif; font-weight: bold; color:#CAC8C8;" class="place"><?= $closestEvent->event_location ?></td>
                </tr>
                <tr style="font-size: 14px; font-weight:normal; padding: 0px;">
                    <td style="padding-bottom:5px; font-family: sans-serif; color:#CAC8C8;" class="place"><?= $closestEvent->getFullAddress() ?></td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto">

                <tr>
                    <?php if (!empty($closestEvent->community)) { ?> 
                        <td style="font-size: 12px; line-height: 16px; color: #ffffff; font-family: sans-serif; font-weight:bold;"><?= "Community " . $closestEvent->community->name ?></td>
                    <?php } ?>
                    <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;"><a href="<?= Yii::$app->urlManager->createAbsoluteUrl($closestEvent->getFullViewUrl()) ?>" style="background: #297A38; border:3px solid #297A38; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                            <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><?= $text ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                        </a></td>
                </tr>
            </table>
        </td>
    </tr>
<?php } ?>




<?php
foreach ((Array) $arrayModels as $model) {
    $text = \open20\amos\events\AmosEvents::t('amosevents', 'Iscriviti');
    if (!empty($user_id)) {
        if ($model->isUserSubscribedToEvent($user_id)) {
            $text = \open20\amos\events\AmosEvents::t('amosevents', 'Accedi');
        }
    }
    $beginDateHour = null;
    if ((!empty($closestEvent) && $model->id != $closestEvent->id) || empty($closestEvent)) {
        $beginDateHour = !empty($model->begin_date_hour) ? new \DateTime($model->begin_date_hour) : null;
        ?>
        <tr>
            <td colspan="2" style="padding:0 15px;" bgcolor="#454545">
                <table width="100%">
                    <tr>
                        <td style="border-top: 1px solid #838383; padding:15px 0px;">
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tr>
                                    <td style="border:solid 2px #297A38; width:70px">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top" style="font-family: sans-serif; mso-height-rule: exactly; color: #FFFFFF; padding: 10px; text-align: center; text-transform: uppercase; font-size:12px;"><strong style="font-size:34px;"><?= !empty($beginDateHour) ? $beginDateHour->format('d') : '' ?></strong>
                                                    <br>
                                                    <?= !empty($beginDateHour) ? Yii::$app->formatter->asDate($beginDateHour, 'MMM') : '' ?> <?= !empty($beginDateHour) ? Yii::$app->formatter->asDate($beginDateHour, 'YYY') : '' ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="80%" border="0">
                                        <table width="100%" border="0">
                                            <tr>
                                                <td style="background: #454545; text-align: center; font-family: sans-serif; font-size: 18px; mso-height-rule: exactly; line-height: normal;  text-align: left; font-weight: bold; padding-left:10px"><a href="#" style="color: #FFFFFF; text-decoration:none; padding-right:3px;"><?= $model->getTitle() ?></a><span style="display: inline-block; margin: 2px;text-transform: uppercase; color: #7CC588; border: 1px solid #7CC588; line-height: 16px; font-size: 10px; vertical-align:top; padding: 1px 5px;" class="tag">Nuovo</span>
                                                    <table width="100%" style="color:#CAC8C8; line-height: normal;">
                                                        <tr style="font-size: 12px; padding: 0px; margin-top: 5px;">
                                                            <td style="padding-top:5px; font-family: sans-serif; color:#CAC8C8;" class="place"><?= $model->event_location ?></td>
                                                        </tr>
                                                        <tr style="font-size: 12px; font-weight:normal; padding: 0px;">
                                                            <td style="padding-bottom:5px; font-family: sans-serif; color:#CAC8C8;" class="place"><?= $model->getFullAddress() ?></td>
                                                        </tr>
                                                    </table>
                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto">

                                                        <tr>
                                                            <?php if (!empty($model->community)) { ?>
                                                                <td style="font-size: 11px; line-height: 16px; color: #ffffff; font-family: sans-serif;">Community <?= $model->community->name ?></td>
                                                            <?php } ?>
                                                            <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;"><a href="<?= Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()) ?>" style="background: #297A38; border:3px solid #297A38; color: #ffffff; font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                                                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><?= $text ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                                                </a></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <?php
    }
}
?>
