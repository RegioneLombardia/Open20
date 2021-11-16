<?php
/** @var $profile \open20\amos\admin\models\UserProfile */
$js = <<<JS
$('#validatori-cwh').on('select2:select', function() {
    $.ajax({
        url: '/cwh/cwh-ajax/get-network',
        type: 'get',
        data: {cwhNodiId: $(this).val()},
        success: function (data) {
            if($('#sign-inserted').length > 0) {
                $('#sign-inserted strong').text(data);
            } else {
                var p = $("<p id='sign-inserted'></p>")
                    .addClass('card-creator-targets');
                $(p).append("<strong>"+data+"</strong>");

                var last_child = $("#preview-sign .post-header > p").last();
                $(last_child).append(p);
            }
        }
    });
});

JS;
$this->registerJs($js);

if ($model->isNewRecord) {
    $model->created_by = \Yii::$app->user->id;
//  $profile = \open20\amos\admin\models\UserProfile::findOne(['user_id' => $model->created_by]);

    $contentCreatorTargets = \open20\amos\core\forms\ItemAndCardHeaderWidget::getValidatorNameGeneral([\open20\amos\cwh\utility\CwhUtil::getCwhNodeFromScope()]);

    $model->validatori = [\open20\amos\cwh\utility\CwhUtil::getCwhNodeFromScope()];
}
?>

<div id="preview-sign">
    <div id="profile-image-preview" class="signature-preview col-xs-12">
        <p><?= \open20\amos\cwh\AmosCwh::t('amoscwh', 'Example sign') ?></p>
        <?php
        echo \open20\amos\core\forms\ItemAndCardHeaderWidget::widget([
                'model' => $model,
                'publicationDateNotPresent' => true,
                'showPrevalentPartnershipAndTargets' => true,
            ]
        );
        ?>
    </div>
</div>
