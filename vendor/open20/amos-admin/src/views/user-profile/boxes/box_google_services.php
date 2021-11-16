<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    box_google_services.php
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\icons\AmosIcons;

/**
 * @var \open20\amos\admin\models\UserProfile $model
 * @var \open20\amos\socialauth\models\SocialAuthUsers[] $socialAuthUsers
 * @var array $enableServices
 */

$socialAuthUser = null;
if (count($socialAuthUsers) && array_key_exists('google', $socialAuthUsers)) {
    $socialAuthUser = $socialAuthUsers['google'];
}
$serviceCalendarActive = in_array('calendar', $enableServices);
$serviceContactActive = in_array('contacts', $enableServices);

if ($serviceCalendarActive || $serviceContactActive) {

    /** @var \open20\amos\socialauth\models\SocialAuthUsers $socialAuthUser */
    if ($socialAuthUser) {
        $isEnabledCalendar = $serviceCalendarActive && $socialAuthUser->getServices()->andWhere(['service' => 'calendar'])->count();
        $isEnabledContacts = $serviceContactActive && $socialAuthUser->getServices()->andWhere(['service' => 'contacts'])->count();
    } else {
        $isEnabledCalendar = false;
        $isEnabledContacts = false;
    }
    $js = <<<JS

function isEnabledCalendar(){
    isEnabledService($model->id, 'google', 'calendar');       
}
function isEnabledContacts(){
    isEnabledService($model->id, 'google', 'contacts');       
}
isEnabledCalendar();
isEnabledContacts();
      
JS;

    $this->registerJs($js);
    ?>

    <div id='google-services' class="<?= $socialAuthUser ? "google-services" : "hidden" ?>">
        <div class="col-xs-12 nop">
            <p><?= AmosAdmin::t('amosadmin', '#google_calendar_description') ?></p>
            <p class="label-social"><strong><?= AmosAdmin::t('amosadmin', '#google_calendar_label'); ?></strong></p>
            <div class="wrap-btn-social">
                <?php
                if ($serviceCalendarActive){
                ?>
                <span id="manage-calendar">
                <?= \open20\amos\core\helpers\Html::a(
                    AmosIcons::show('calendar', [], 'dash'),
                    '/'.AmosAdmin::getModuleName().'/user-profile/enable-google-service?id=' . $model->id . '&serviceName=calendar',
                    [
                        'id' => 'enable-calendar-btn',
                        'class' => 'btn btn-google-services' . ($isEnabledCalendar ? ' hidden' : ''),
                        'title' => AmosAdmin::t('amosadmin', 'Enable') . ' ' . AmosAdmin::t('amosadmin', '#calendar'),
                        'onclick' => "window.open(this.href, 'enableCalendar', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"
                    ]) ?>
                <?= \open20\amos\core\helpers\Html::a(
                    AmosIcons::show('calendar', [], 'dash') . '&nbsp;' . AmosAdmin::t('amosadmin', 'Disconnect'),
                    '/'.AmosAdmin::getModuleName().'/user-profile/disable-google-service?id=' . $model->id . '&serviceName=calendar',
                    [
                        'id' => 'disable-calendar-btn',
                        'class' => 'btn btn-google-services' . ($isEnabledCalendar ? ' btn-google-services-disconnect' : ' hidden'),
                        'title' => AmosAdmin::t('amosadmin', 'Disconnect') . ' ' . AmosAdmin::t('amosadmin', '#calendar'),
                    ]) ?>
            </span>
            </div>
        </div>
        <div class="col-xs-12 nop">
            <p><?= AmosAdmin::t('amosadmin', '#google_contact_description') ?></p>
            <p class="label-social"><strong><?= AmosAdmin::t('amosadmin', '#google_contact_label'); ?></strong></p>
            <div class="wrap-btn-social">
                <?php
                }
                if ($serviceContactActive) {
                    ?>
                    <span id="manage-contacts">
            <?= \open20\amos\core\helpers\Html::a(
                AmosIcons::show('account'),
                '/'.AmosAdmin::getModuleName().'/user-profile/enable-google-service?id=' . $model->id . '&serviceName=contacts',
                [
                    'id' => 'enable-contacts-btn',
                    'class' => 'btn btn-google-services' . ($isEnabledContacts ? ' hidden' : ''),
                    'title' => AmosAdmin::t('amosadmin', 'Enable') . ' ' . AmosAdmin::t('amosadmin', '#contacts'),
                    'onclick' => "window.open(this.href, 'enableContacts', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;"
                ]) ?>
            <?= \open20\amos\core\helpers\Html::a(
                AmosIcons::show('account') . '&nbsp;' . AmosAdmin::t('amosadmin', 'Disconnect'),
                '/'.AmosAdmin::getModuleName().'/user-profile/disable-google-service?id=' . $model->id . '&serviceName=contacts',
                [
                    'id' => 'disable-contacts-btn',
                    'class' => 'btn btn-google-services' . ($isEnabledContacts ? ' btn-google-services-disconnect' : ' hidden'),
                    'title' => AmosAdmin::t('amosadmin', 'Disconnect') . ' ' . AmosAdmin::t('amosadmin', '#contacts')
                ]) ?>
        </span>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>
