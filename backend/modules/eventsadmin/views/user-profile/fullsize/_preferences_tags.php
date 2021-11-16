<?php
use yii\helpers\ArrayHelper;
use open20\amos\events\utility\EventsUtility;
?>
<?php
$preferenceCenterTags = EventsUtility::getPreferenceCenterTags();

$preferencesTags = \backend\modules\eventsadmin\controllers\UserProfileController::loadTagPreferences($model);
?>
<h3><?= \Yii::t('app', "Tag di interesse informativo Preference Center")?></h3>
<?=
 \yii\helpers\Html::checkboxList('preferencesTags', $preferencesTags, EventsUtility::getLabelPreference(),['encode' => false]); ?>
