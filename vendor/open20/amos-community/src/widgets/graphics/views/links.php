<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\helpers\Html;
use open20\amos\community\models\Bookmarks;
use open20\amos\core\forms\ActiveForm;
use open20\amos\layout\Module;


$this->registerJsVar('alertSuccess', $this->render('parts/_alert-success'));
$this->registerJsVar('alertError', $this->render('parts/_alert-danger'));

$js = <<< JS
    let isSubmitting = false; // Per impedire chiamate doppie
    // get the form id and set the event                         
    $('#formFastInsert').on('beforeSubmit', function(e) {
        e.preventDefault();
        
        if(isSubmitting) return; // Elimina la chiamata doppia
        
        isSubmitting = true;
        $('#submitFastBookmark').prop("disabled", true);
        var form = $(this);
        if(form.find('.has-error').length || $('#titolo').val() == '' || $('#link').val() == '') {     
            return false;       
        }

        $.ajax({  
            url: form.attr('action'),    
            type: 'post',   
            data: form.serialize(),
            success: function(response) {  
                $('#container-links').html(response);
                if($('.alert-container').children().length > 0) {
                    $('.alert-container').children().remove();
                }
                var title = $('#container-links .item-link-list .item-link-header p strong a').html();
                $('.alert-container').append(alertSuccess);
                $('.alert-container').children().first().html('Bookmark "'+title+'" pubblicato correttamente');
                resetForm();
                $('#submitFastBookmark').prop("disabled", false);
                isSubmitting = false;
            },
            error: function() {  
                $('.alert-container').append(alertError)  
            }       
        });                                          
    }).on('submit', function(e) {
      e.preventDefault();  
    });

    // per resettare il form rapido
    function resetForm(){
        $('#titolo').val(null);
        $('#link').val(null);
    }
JS;
$this->registerJs($js);

$aContent = '<span class="am am-plus-circle-o"></span>
            <span>Nuovo</span>';
if($isMemberActive) { // L'utente non admin ha il ruolo READER e quindi non può creare contenuti
    if(!Yii::$app->user->can('BOOKMARKS_CREATE') && !Yii::$app->user->can("ADMIN")) {
        $nuovoButton = Html::button($aContent, [
            'class' => 'cta link-create-bookmarks flexbox align-items-center btn btn-xs btn-primary disabled disabled-with-pointer-events',
            'data-toggle' => 'tooltip',
            'title' => '',
            'data-original-title' => Module::t('amoslayout', '#reader_user_cannot_create')
        ]);
    } else { // L'utente può creare contenuti
        $nuovoButton = Html::a($aContent, ['bookmarks/create', 'id' => $model->id], [
            'class' => 'cta link-create-bookmarks flexbox align-items-center btn btn-xs btn-primary',
        ]);
    }
} else {
    $nuovoButton = Html::button($aContent, [
        'class' => 'cta link-create-bookmarks flexbox align-items-center btn btn-xs btn-primary disabled disabled-with-pointer-events',
        'data-toggle' => 'tooltip',
        'title' => '',
        'data-original-title' => 'Per creare un contenuto iscriviti alla community ' . $model->name
    ]);
}

$modelLink = new Bookmarks();
?>
<div class="widget-graphic-cms-bi-less list-link container">
    <div class="page-header">
        <div class="bi-plugin-header">
            <div class="flexbox title-heading-plugin">
                <div class="m-r-10">
                    <div class="h2 text-uppercase">ULTIMI BOOKMARK</div>
                </div>


                <a href="/community/bookmarks/index?id=<?= $model->id ?>" class="link-all-discussioni text-uppercase align-items-center small">
                    <span>Tutti i bookmark</span>
                    <span class="icon mdi mdi-arrow-right-circle-outline"></span>
                </a>
            </div>
            <div class="cta-wrapper">
                <div class="flexbox manage-cta-container">
                    <?= $nuovoButton ?>
                </div>

            </div>
        </div>

    </div>

    <?php
    # Elenco degli ultimi link
    echo $this->render('parts/_lastLinks', [
        'data' => $data,
        'limit' => $limit,
        'model' => $model
    ]);
    ?>

    <div class="alert-container"></div>

    <?php
    /*  Inserimento rapido:
        Bisogna essere membri, senza moderazione nella community e senza ruolo READER per poter vedere la pubblicazione veloce dei Bookmarks
    */
    if ($isMemberActive && !$model->force_workflow && Yii::$app->user->can('BOOKMARKS_CREATE')) { ?>
        <div class="content-insert-link">
            <p><b>INSERISCI BOOKMARK RAPIDO</b></p>
            <?php $form = ActiveForm::begin([
                'action' => ['/community/bookmarks/create-ajax'],
                'id' => 'formFastInsert',
            ]); ?>
            <div class="bookmarks">


                <div class="content-statisticframe">
                    <div class="row">
                        <div class="col-md-6"><?= $form->field($modelLink, 'titolo', ['inputOptions' => ['id' => 'titolo']])->textInput(['maxlength' => true]) ?></div>
                        <div class="col-md-6"><?= $form->field($modelLink, 'link', ['inputOptions' => ['id' => 'link']])->textInput(['maxlength' => true]) ?></div>
                        <?= $form->field($modelLink, 'community_id')->hiddenInput(['value' => $model->id])->label(false); ?>
                        <div class="receiver-section m-b-10" style="display:none">
                            <div class="tag-section">
                                <div>
                                    <!-- TAG -->
                                    <?php
                                    $moduleTag = \Yii::$app->getModule('tag');
                                    $moduleCwh = \Yii::$app->getModule('cwh');
                                    isset($moduleCwh) ? $showReceiverSection = true : null;
                                    isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
                                    if ($moduleTag->behaviors) {
                                        echo \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                                            'model' => $modelLink,
                                            'moduleCwh' => $moduleCwh,
                                            'scope' => $scope,
                                        ]);
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p>La notifica di pubblicazione verrà inviata a tutti i partecipanti della community.</p>
                            <p>Se desideri invece notificare solo ad utenti su tag specifici utilizza il pulsante <?= Html::a('Nuovo', ['/community/bookmarks/create', 'id' => $model->id]) ?> in alto.</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <?= Html::submitButton('Pubblica', ['id' => 'submitFastBookmark', 'class' => 'btn btn-primary']) ?>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php
        ActiveForm::end();
    }
    ?>
</div>