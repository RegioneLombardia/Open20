<?php

use open20\amos\comments\AmosComments;
use yii\web\View;
use open20\amos\comments\assets\CommentsDesignAsset;
use open20\design\components\bootstrapitalia\Input; 

CommentsDesignAsset::register($this);

$js = "
$('#contribute-btn').on('click', function (event) {
    if (typeof tinymce != 'undefined') {
        tinymce.triggerSave();
    }  
    Comments.saveComment(".$widget->model->id.", '".addslashes($widget->model->className())."', '".\Yii::$app->request->csrfParam."', '".\Yii::$app->request->csrfToken."')
}); 
";
$this->registerJs($js, View::POS_READY);

$class= $widget->model->className();

/** @var AmosComments $commentsModule */
$commentsModule = Yii::$app->getModule(AmosComments::getModuleName());

if (Yii::$app->getUser()->can('COMMENT_CREATE', ['model' => $widget->model])) {
    
    $textArea = Input::widget([
            'name' => 'contribute-area',
            'type' => 'textarea',
            'value' => null,
            'options' => [
                'id' => 'contribute-area',
                'class' => 'form-control'
            ],
    ]);
    ?>
    <div class="media mt-5" id="bk-contribute">
        <div class="media-body">
            <div class="form-group mb-2">
                <?=
                $textArea
                ?>
            </div>
            <div class="d-flex">
                <button id="contribute-btn" type="button" class="btn btn-outline-secondary btn-xs">
                    <?= AmosComments::t('amoscomments', 'Aggiungi commento') ?>
                </button>
            </div>

        </div>

    </div>
<?php } ?>