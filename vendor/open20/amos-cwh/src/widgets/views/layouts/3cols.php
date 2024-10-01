<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */
if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') {

} else {
    \open20\amos\cwh\assets\CwhAsset::register($this);
}

use open20\amos\cwh\AmosCwh;

?>


<div class='row'>
    <h3 class="subtitle-section-form"><?= AmosCwh::t('amoscwh', '#subtitle_section_form'); ?></h3>
    <div class="col-md-6">{validatori}</div>
    <div class='col-md-6'>{previewSign}</div>
    <div class="clearfix"></div>
    <div class="col-md-6">{destinatari}</div>
    <div class='col-md-6'>{regolaPubblicazione}</div>
</div>
<?php if (!empty($enableIgnoreNotifyFromEditorialStaff)) { ?>
    <div class='row'>
        <div class='col-md-6'>{ignore_notify_from_editorial_staff}</div>
    </div>
<?php }

