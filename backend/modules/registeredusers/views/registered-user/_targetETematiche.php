<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\design\assets\BootstrapItaliaCustomAsset;
use kartik\widgets\SwitchInput;

use open20\design\components\bootstrapitalia\ActiveForm;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
//$bootstrapItaliaAsset = BootstrapItaliaCustomAsset::register($this);

$js = <<<JS
    $('.target').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
         
        $('#dimmer1').dimmerShow();
        }
        else {
           $('#dimmer1').dimmerHide();
        }
    });

  
JS;
//$this->registerJs($js);
$js2 = <<<JS
    $('.topic').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
             var nameclass=$(this).attr("id");
      		 $('.'+ nameclass).each(function (){
      		     $(this).attr('disabled', false);
      		 })
        }
        else {
          var nameclass=$(this).attr("id");
      		 $('.'+ nameclass).each(function (){
      		     $(this).attr('checked', false);
      		     $(this).attr('disabled', true);
      		 })
        }
    });

  $(document).ready(function(){
        $('.topic').trigger('switchChange.bootstrapSwitch');
});
JS;
$this->registerJs($js2);
?>

<div class="tematiche-container mb-5">
<?php
$listOfTarget = preference\userprofile\utility\TargetTagUtility::getAllTargetTag();
foreach ($listOfTarget

    as $key => $target) :
    $targetAttributes = TargetAttributeUtility::getAttributesByUserCode(Yii::$app->user->id, $target->codice);
    $isSelected = TargetTagUtility::isTargetSelectedForUser($target, $model->user_id);
    $attributes = TopicTagUtility::getAllTopicByTargetCode($target->codice);
?>

    
        <div class="d-flex align-items-center mt-5 <?= ($isSelected) ?: 'opacity-5' ?>">
            <div class="d-flex align-items-center mb-4">
                <svg class="icon icon-lg mr-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#<?= TargetAttributeUtility::getTargetIcon($target->nome) ?>"></use>
                </svg>
                <p class="font-weight-bold mb-0">Target: <span class="text-uppercase"><?= $target->nome ?></span></p>
                

            </div>

            <div class="toggles ml-auto">
                <label for="toggle-stato-id-<?= $target->id ?>">
                    <?php
                    echo SwitchInput::widget([
                        'name' => 'target[' . $target->id . ']',
                        'options' => ['class' => 'target'],
                        // 'id' =>,
                        'pluginOptions' => [
                            'size' => 'mini',
                        ],
                        'value' => $isSelected,
                    ]);
                    ?>
                </label>
                <?php
                $targetCode = $target->codice;
                $jsId = $targetAttributes->id;
                $this->registerJs(
                    <<<JS

    $("#toggle-stato-id-{$jsId}").click(function(event) {

        if($(this).prop("checked") == true) {
            $(this).parent().find('.jsSwitchLabel').text('Attivo').delay(1000);
            $.post({
                url: 'enable-target-ajax',
                type: 'post',
                data: {
                        target_code: "{$targetCode}",
                    },
                success: function (data) {
                    if(data == 'true'){                     
                        location.reload();
                    } else {                                                
                        
                        //$(this).prop('checked', false);

                        $('#messages-attributes-id').html(' \
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                        Errore, impossibile abilitare il target: ' + data + ' \
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                            <span aria-hidden="true">&times;</span> \
                        </button> \
                        </div> \
                        ');
                        
                    }
                }
            });
        }

        if($(this).prop("checked") == false) {
            $(this).parent().find('.jsSwitchLabel').text('Disattivo');
            $.post({
                url: 'disable-target-ajax',
                type: 'post',
                data: {
                        target_code: "{$targetCode}",
                    },
                success: function (data) {
                    if(data == 'true'){                     
                        location.reload();
                    } else {                         

                        //$(this).prop('checked', true);

                        $('#messages-attributes-id').html(' \
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                        Errore, impossibile disabilitare il target: ' + data + ' \
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                            <span aria-hidden="true">&times;</span> \
                        </button> \
                        </div> \
                        ');
                    }
                }
            });
        }
    });

JS
,
                    View::POS_READY
                );

                $this->registerJs(
                    <<<JS

    $(document).ready(function(event) {

        var allToggle = $(".toggles");
        allToggle.each(function(){
            var input = $(this).find('input');
            if((input).prop("checked") == true){
                $(this).find('.jsSwitchLabel').text('Attivo');
            } else {
                $(this).find('.jsSwitchLabel').text('Disattivo');
            }
        })

    });

JS
,
                    View::POS_READY
                );
                ?>
            </div>
        </div>

        <div class="dimmable">
            <div class="dimmer" id="dimmer1">
                <div class="dimmer-inner">
                    <div class="dimmer-icon">
                        <!--					<svg class="icon icon-xl">-->
                        <!--						<use xlink:href="-->
                        <!--/img/sprite.svg#it-unlocked"></use>-->
                        <!--					</svg>-->
                    </div>
                </div>
            </div>
    
   
            <div class="tematiche mb-5">
                <div class="row">
            <div class="col-md-6">
                    <p class="text-muted font-weight-bold text-uppercase">tematiche</p>
                </div>
                <?php foreach (ArrayHelper::map(UserInterestTagUtility::getAllChannels(), 'id', 'title') as $key => $value) : ?>
                    <div class="col-md-2">
                        <p class="text-muted font-weight-bold d-none d-md-block text-center text-uppercase text-truncate"><?= $value ?></p>
                    </div>
                <?php endforeach; ?>
                </div>
                <?php

                foreach ($attributes as $attribute) :
                    $selectedTopic = 0;
                ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-group d-flex">
                                <label class="control-label order-2 ml-4 ml-sm-5 text-muted font-weight-bold"><?= $attribute->nome ?></label>
                                <?php
                                $selectedTopic = \preference\userprofile\utility\TopicTagUtility::isTopicSelectedForUser($attribute, $model->user_id);
                                echo SwitchInput::widget([
                                    'name' => 'topic[' . $attribute->id . ']',
                                    'options' => ['class' => 'topic', 'id' => 'topic-' . $attribute->id],
                                    'pluginOptions' => ['size' => 'mini'],
                                    'value' => $selectedTopic,
                                ]);
                                ?>
                            </div>
                        </div>
                        <?php

                        foreach (ArrayHelper::map(UserInterestTagUtility::getAllChannels(), 'id', 'title') as $key => $value) :

                            $checked = in_array($key, UserInterestTagUtility::getUserChannelsIds($attribute, $model->user_id)) ? 'checked' : '';
                        ?>
                            <div class="col-4 col-md-2">
                                <div class="form-group">
                                    <div class="form-check text-md-center">
                                        <input class="form-check-input topic-<?= $attribute->id ?>" id="checkbox-<?= $attribute->id . $key ?>" type="checkbox" name="TopicChannel[<?= $attribute->id ?>][]" value="<?= $key ?>" <?= $checked ?>>
                                        <label class="form-check-label" for="checkbox-<?= $attribute->id . $key ?>"><span class="label-text text-muted small"><?= $value ?></span></label>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                <?php
                endforeach;
                ?>

            <?php
        endforeach;
            ?>
            </div>
        </div>
    </div>
    <div id="messages-attributes-id"></div>