<?php 

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\models\PreferenceCampainContainer;
use backend\modules\campains\utility\ExternalContentUtility;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\forms\TextEditorWidget;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
$prefixIdElement = 'externalcontent-' . ((!empty($model->id)? $model->id .'-': ''));

// VarDumper::dump( $form->className(), $depth = 10, $highlight = true);
?>

<div class="newsletter-container-content">

    <!-- START hidden input --> 
    <?= $form->field($model, 'preference_campain_id')->hiddenInput(['value' => $model_preference_campain->id, 'id' => $prefixIdElement . 'preference_campain_id' . '-id' ])->label(false) ?>
    <?= $form->field($model, 'id')->hiddenInput(['value'=> $model->id, 'id' => $prefixIdElement . 'id' . '-id'])->label(false) ?>
    <?= $form->field($model, 'preference_campain_section_id')->hiddenInput(['class' => "preference_campain_section", 'id' => $prefixIdElement . 'preference_campain_section_id' . '-id', 'value'=> $model->preference_campain_section_id])->label(false) ?>
    <?= $form->field($model, 'preference_campain_container_type_id')->hiddenInput(['value'=> $model->preference_campain_container_type_id, 'id' => $prefixIdElement . 'preference_campain_container_type_id' . '-id'])->label(false) ?>
    <!-- END hidden input -->

    <div class="newsletter-container-content">

    <?php
        $model->setScenario(PreferenceCampainContainer::SCENARIO_CONTENUTO);
    ?>

        <?php 
            /** Record già Presente */
        ?>
        <?php if( !$model->isNewRecord ) : ?>
            <div class="row">
                <?php
                    $idCrop = ('content-image-id' . ((!empty($model->id))? ('-' . $model->id): ''));
                ?>
                <?=
                    $form->field($model, 'contentImage')->widget(
                        \open20\amos\attachments\components\CropInput::classname(),
                        [
                            'aspectRatioChoices' => $aspectRatioChoices,
                            'cropModalCloseButton' => false,
                            'jcropOptions' => [
                                'aspectRatio' => $aspectRatio, 
                                'placeholder' => $cropPlaceholder],
                            'options' => [
                                'id' => $idCrop,
                                'onclick' => "this.value=null",
                            ],
                        ]
                    )->label(Module::t('campains', "Inserisci l'immagine"))
                ?>
                <!-- <input type="file" id="content_image_path" name="content_image_path" accept="image/png, image/jpg">
                <img id="content_image_path_preview" src="#" alt="your image" /> -->
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <?= $form->field($model, 'content_align')->radioList( $type_content_align , ['id' => ($prefixIdElement . 'content_title-id')])->label('Allineamento') ?>
                </div>
            </div>
  
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'content_title')->textInput(['id' => ($prefixIdElement . 'content_title-id'), 'maxlength' => "255"])->hint('max 255 caratteri') ?>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <?= $form->field($model, 'content_news_link')->textInput(['placeholder' => Module::t('amosevents', 'Link Contenuto News'), 'id' => ($prefixIdElement . 'content_news_link-id')])->label('Link esterno alla news completta') ?>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <?=
                        $form->field($model, 'content_text')->widget(TextEditorWidget::className(), [
                            'options' => [
                                'id' => "content_text".$model->id,
                            ],
                            'clientOptions' => [
//                                'placeholder' => Yii::t('amosevents', 'Inserisci abstract (max 5000 char.)'),
                                'lang' => substr(Yii::$app->language, 0, 2),
                                'toolbar' => "bold italic | bullist numlist | alignleft",
                                "contextmenu" => false,
                                'forced_root_block' => false,
                                'chars' => 425,
                            ],
                        ])->hint('max 425 caratteri');
                    ?>
                </div>
            </div>
        <?php endif; ?>


        <?php 
            /** Nuovo Record */
        ?>
        <?php if($model->isNewRecord ) : 
            // VarDumper::dump(ExternalContentUtility::getExternalContentStructure(), $depth = 10, $highlight = true);
            ?>

            <div class="row">
                <div class="col-md-12">
                    <?= 
                        $form->field($model, 'external_source')->widget(Select2::classname(), [
                            'name' => 'profili',
                            'data' => $external_source,
                            
                            'class' => "external_source", 
                            'options' => [
                                'placeholder' => 'Seleziona...',
                                'multiple' => false,
                                'id' => "external_source-id", 
                            ],
                        ])->label("Scegli la fonte Esterna");
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-check form-check-inline mb-5">
                        <?= Html::hiddenInput('match-preference-checkbox-val', '0', ['id' => 'match-preference-checkbox-val-id']); ?>
                        <?= Html::hiddenInput('campain_id', $model_preference_campain->id, ['id' => 'campain_id-id']); ?>
                        <input id="match-preference-checkbox-id" name="match-preference-checkbox" type="checkbox" value="false">
                        <label for="match-preference-checkbox-id">Categorie coerenti alle tematiche scelte sulla campagna</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- choose content to insert -->
                    <?= $form->field($model, 'external_source_category')->widget(DepDrop::classname(), [
                            'type' => DepDrop::TYPE_SELECT2,
                            'options'=> [
                                'id'=>'external_source_category-id',
                                
                                'placeholder' => 'Seleziona...'
                            ],
                            'pluginOptions'=> [
                                'depends' => ['external_source-id'],
                                
                                'url' => Url::to(['/campains/preference-campain/get-souce-categories-ajax']),
                                'params' => ['match-preference-checkbox-val-id', 'campain_id-id']
                            ]
                        ])->label('Categoria'); 
                    ?>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <!-- choose content to insert -->
                    <?= $form->field($model, 'content_to_insert')->radioList([], ['class' => "content_to_insert" ] )->label("Scegli contenuto da inserire") ?>

                    <div class="col-md-12">
                        <div class="progress-spinner progress-spinner-active" hidden>
                            <span class="sr-only">Caricamento...</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!$model->isNewRecord ) : ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'social_sharing')->checkBox(['id' => "preferencecampaincontainer-social_sharing-external-content".$model->id]); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
    
</div>



<?php

/** PAGE SCRIPTS */
$script = <<< JS
(function(global){
    function updateFeedList(pageNumber = 1) {
        $(".content_to_insert").html('');
        
        $('.progress-spinner').removeAttr('hidden');
        if ($('#external_source-id').val() && $('#external_source_category-id').val()) {
            $.post({
                url: '/campains/preference-campain/get-souce-rss-ajax?page=' + pageNumber,
                type: 'post',
                data: {
                    source: '' + $('#external_source-id').val(),
                    category: '' + $('#external_source_category-id').val(),
                },
                success: function (data) {
                    if (data.status == 'ok') {
                        window.navBarObj.htmlContent(data); 
                    }  
                    
                    if (data.status == 'error') {
                        window.navBarObj.htmlError(data); 
                    }
                }
            });
        }
    }

    function htmlContent(data){
        var html_code = "";
        var numTotalFeed = parseInt(data.numTotalFeed);
        var pagSize = parseInt(data.pagSize);
        var page = parseInt(data.page);

        $.each(data.entries, function(i, item) {
            var urlImage = '';
            if (item.enclosure) {
                switch(item.enclosure.type) {
                case 'image/png':
                case 'image/jpeg':
                    urlImage = item.enclosure.url;
                    break;
                default:
                    urlImage = '';
                }
            }

            html_code += '<div class="custom-control custom-radio">';
            html_code += '<input type="radio" id="content_to_insert'+ i +'" class="custom-control-input" name="PreferenceCampainContainer[content_to_insert]" value="'+ item.title +"###"+ item.link +"###"+ item.description +"###"+ item.dateCreated +"###"+  urlImage +'">';
            html_code += '<label class="custom-control-label" for="content_to_insert'+ i +'">'+ item.title +' <a href="'+item.link+'" target="_blank" class="small ml-2">Leggi <svg class="icon icon-xs icon-primary"><use xlink:href="$spriteAsset->baseUrl/material-sprite.svg#ic_launch"></use></svg></a></label></div>';
        });

        if (numTotalFeed > pagSize) {
            var numberOfPage = Math.floor(numTotalFeed/pagSize);
            var rem = numTotalFeed % pagSize;
            if (rem > 0) {
                numberOfPage++;
            }

            var nextHref = "JavaScript:void(0);";
            if ((page + 1) <= numberOfPage) {
                nextHref = "javascript:window.navBarObj.updateFeedList("+ (page + 1) +");";
            }

            var prevHref = "JavaScript:void(0);";
            if ((page - 1) >= 1) {
                prevHref = "javascript:window.navBarObj.updateFeedList("+(page - 1)+");";
            }
            
            html_code += '<nav class="pagination-wrapper pagination-total mt-5" aria-label="Esempio di navigazione con total number"> \
                <ul class="pagination"> \
                <li class="page-item"> \
                <a class="page-link" href="'+prevHref+'">  \
                    <svg class="icon icon-primary"><use xlink:href="$spriteAsset->baseUrl/material-sprite.svg#ic_chevron_left"></use></svg> \
                    <span class="sr-only">Pagina precedente</span> \
                </a> \
                </li>';
            var i;
            for (i = 0; i < numberOfPage; i++) {
                var runtimePageNumber = (i + 1);
                if( runtimePageNumber == page) {
                    html_code += ' \
                        <li class="page-item active">  \
                        <a class="page-link" href="#" aria-current="page"> \
                            <span class="d-inline-block d-sm-none">Pagina </span> '+runtimePageNumber+' \
                        </a> \
                        </li> ';   
                } else {
                    html_code += '<li class="page-item "><a class="page-link" href="javascript:window.navBarObj.updateFeedList('+runtimePageNumber+');">'+runtimePageNumber+'</a></li>';
                }
            }

            html_code += ' \
                    <li class="page-item"> \
                    <a class="page-link" href="'+nextHref+'"> \
                        <span class="sr-only">Pagina successiva</span> \
                        <svg class="icon icon-primary"><use xlink:href="$spriteAsset->baseUrl/material-sprite.svg#ic_chevron_right"></use></svg> \
                    </a> \
                    </li> \
                </ul> \
                <p><span class="sr-only">Pagina</span> '+page+' di '+numberOfPage+'</p> \
                </nav>';
        }
        
        setTimeout(function(){
            if(numTotalFeed <= 0){
                html_code = '<div class="col-md-12 mt-5"><h5><strong>Nessun Contenuto da mostrare</strong></h5></div>';
            }
            $(".content_to_insert").html(html_code);
            $('.progress-spinner').attr('hidden', 'hidden');
        }, 2000);
    }

    function htmlError(data){
        var html_code = "";
        html_code += '<div class="alert alert-danger" role="alert">'+data.message+'</div>';
        
        setTimeout(function(){
            $(".content_to_insert").html(html_code);
            $('.progress-spinner').attr('hidden', 'hidden');
        }, 2000);
    }

    global.navBarObj = {
        updateFeedList: updateFeedList,
        htmlContent: htmlContent,
        htmlError: htmlError
    }

})(window);

$(document).ready(function(){
    $('#external_source_category-id').change(function(){

        if ($('#external_source-id').val() && $('#external_source_category-id').val()) {
            window.navBarObj.updateFeedList();
        }

    });

    $('#external_source-id').change(function(){
        $(".content_to_insert").html('');
    });

});


$('#match-preference-checkbox-id').click(function(){
    if ($(this).is(':checked')) {
        $('#match-preference-checkbox-val-id').val('1');
    } else {
        $('#match-preference-checkbox-val-id').val('0');
    }
    $('#external_source_category-id').val(null);
    $('#external_source_category-id').trigger('change');
    $('#external_source-id').trigger('select2:select');
    $(".content_to_insert").html('');

});

JS;

$this->registerJs($script,View::POS_READY);

?>