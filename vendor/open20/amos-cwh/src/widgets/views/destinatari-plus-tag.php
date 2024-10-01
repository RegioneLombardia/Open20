<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use open20\amos\cwh\widgets\Cwh3ColsWidget;

/**
 * @var yii\web\View $this
 * @var int|array $singleFixedTreeId
 * @var open20\amos\core\record\Record $model
 * @var open20\amos\cwh\AmosCwh $moduleCwh
 */

$scope = null;
$enableTagsAjax = false;
if (isset($moduleCwh) && !is_null($moduleCwh)) {
    $enableTagsAjax = $moduleCwh->enableTagsAjax;
    $scope = $moduleCwh->getCwhScope();
}

$scopeFilter = (empty($scope)) ? false : true;

if (!$scopeFilter) {
    $this->registerJs(<<<JS
    var resetTag = function(tag) {
         tag.removeClass('focused');
         tag.find('.red').remove();
    };
    var requiredTag = function(tag) {
         tag.addClass('focused');
         
         if(tag.find('.red').length == 0) {
             tag.find('.tags-title').append('<span class=\"red\">*</span>');
         }
    };
        
    var resetRecipients = function(recipientsInput, recipientsWrap) {
         recipientsInput.prop('disabled', true);
         recipientsInput.val(null);
         
         recipientsWrap.removeClass('focused');
         recipientsWrap.find('.red').remove();
    };
    var requiredRecipients = function(recipientsInput, recipientsWrap) {
        recipientsInput.prop('disabled', false);
        recipientsWrap.addClass('focused');
        
        if(recipientsWrap.find('.red').length == 0) {
             recipientsWrap.find('label').append('<span class=\"red\">*</span>');
        }
    };
JS
    );
}

//check if network scope is set, in this case change the input value of publication rule
$this->registerJS(<<<JS

if($("#cwh-regola_pubblicazione").val() == 1 || $("#cwh-regola_pubblicazione").val() == 3){
    $("#amos-tag").hide();
    $(".cwh-tag-section").hide();
}

function setRegolaPubblicazione () {
    // if($("#cwh-regola_pubblicazione").val() == 1){
    //     if($("#cwh-destinatari").val() == null) {
    //         $("input[id$=\"regola_pubblicazione\"").val(1);
    //     } else {
    //         $("input[id$=\"regola_pubblicazione\"").val(3);
    //     }
    // } else if($("#cwh-regola_pubblicazione").val() == 2){
    //     if($("#cwh-destinatari").val() == null) {
    //         $("input[id$=\"regola_pubblicazione\"").val(2);
    //     } else {
    //         $("input[id$=\"regola_pubblicazione\"").val(4);
    //     }
    // } else {
         $("input[id$=\"regola_pubblicazione\"]").val($("#cwh-regola_pubblicazione").val());
    // }
}

$("#cwh-regola_pubblicazione").on('change', function(e) {    
    setRegolaPubblicazione();
        
    if($("#cwh-regola_pubblicazione").val() == 1 || $("#cwh-regola_pubblicazione").val() == 3){
        $("#amos-tag").hide();
        $(".cwh-tag-section").hide();

        $($("div[id^=\"preview_tag_tree\"] > div")).each(function(index, el) {
            $("input[id^=\"tree_obj_\"]").treeview("uncheckNode", $(el).attr("data-tagid"));
        });
    } else {
        $("#amos-tag").show();
        $(".cwh-tag-section").show();

    }
});

$("form").on('submit', function(e) {
     setRegolaPubblicazione();
    
    if($("#landing-checkbox:checked").length > 0){
        $("input[id$=\"regola_pubblicazione\"").val(5);
    }
    
});

JS
);
?>

<div class="cwh-section">
    <?php
    if (isset($moduleCwh) && in_array(get_class($model), $moduleCwh->modelsEnabled) && $moduleCwh->behaviors) {
        echo Cwh3ColsWidget::widget([
            'form' => \yii\base\Widget::$stack[0],
            'model' => $model,
            'regolaPubblicazione' => [
                'data' => \open20\amos\cwh\models\CwhRegolePubblicazione::find()->asArray()->all()
            ],
            'renderCols' => true,
            'moduleCwh' => $moduleCwh
        ]);
    }
    ?>
</div>

<div class="cwh-tag-section tag-section">
    <?php
    $moduleTag = \Yii::$app->getModule('tag');
    if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
        $tagWidgetConf = [
            'model' => $model,
            'attribute' => 'tagValues',
            'form' => \yii\base\Widget::$stack[0],
            'moduleCwh' => $moduleCwh,
            'enableAjax' => $enableTagsAjax
        ];
        if (isset($singleFixedTreeId)) {
            $tagWidgetConf['singleFixedTreeId'] = $singleFixedTreeId;
        }
        echo \open20\amos\tag\widgets\TagWidget::widget($tagWidgetConf);
    }
    ?>
</div>

<div class="check-recipients-section">
    <?php
    if (isset($moduleCwh) && in_array(get_class($model), $moduleCwh->modelsEnabled) && $moduleCwh->behaviors) {
        echo Cwh3ColsWidget::widget([
            'form' => \yii\base\Widget::$stack[0],
            'model' => $model,
            'renderCols' => false,
            'moduleCwh' => $moduleCwh
        ]);
    }
    ?>
</div>
