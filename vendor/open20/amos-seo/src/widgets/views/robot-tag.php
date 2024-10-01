<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo
 * @category   CategoryName
 */
use open20\amos\seo\AmosSeo;
use open20\amos\core\helpers\Html;
use kartik\datecontrol\DateControl;

//print 'getMetaRobots: '.$contentModel->getMetaRobots().'.<br />';
//print 'getMetaGooglebot: '.$contentModel->getMetaGooglebot().'.<br />';
?>
<?php
$js = <<<JS
    // sho/hide unavailable_after_date if one of unavailable_after is checked
    function hideShowUnavailableAfterDate() {
        var visibleUnavailableAfterDate = false;
        $("[id$='unavailable_after']:checked").each(function() {
            visibleUnavailableAfterDate = true;
        });
        if (visibleUnavailableAfterDate) {
            $(".field_unavailable_after_date").show();
        } else {
            $(".field_unavailable_after_date").hide();
        }
    }
    hideShowUnavailableAfterDate();
    $("[id$='unavailable_after']").on('change', function() {
        hideShowUnavailableAfterDate();
    });
JS;

$this->registerJs($js);

?>
<div class="row robot-tag">

    <div class="col-xs-12">
        <?= Html::tag('h3', AmosSeo::t('amosseo', '#robot_tag_title'), ['class' => 'subtitle-form']) ?>
    </div>

    <div class="col-xs-8">
        <?= $form->field($model, 'meta_robots')->checkboxList($metaRobotsList)->hint(AmosSeo::t('amosseo', '#meta_robots_field_hint')) ?>

        <?= $form->field($model, 'meta_googlebot')->checkboxList($metaGooglebotList)->hint(AmosSeo::t('amosseo', '#meta_googlebot_field_hint')) ?>
    </div>
        
    <div class="col-xs-4 field_unavailable_after_date">
        <?= $form->field($model, 'unavailable_after_date')->widget(DateControl::className(), [
//                    'displayFormat' => 'php:d-M-Y H:i:s',
            'type' => DateControl::FORMAT_DATETIME
        ]) ?>
    </div>
</div>

