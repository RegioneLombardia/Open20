<?php

namespace open20\amos\comments\widgets;

use yii\base\Widget;

class SelectCommentEvidence extends Widget
{
    public $asset;
    public $model;
    public $module;

    public function run()
    {
       return $this->render('select_comment_evidence',[
           'comment' => $this->model,
           'currentAsset' => $this->asset,
           'module' => $this->module,
       ]);
    }

    /**
     * @param $currentAsset
     * @return void
     */
    public static function registerJsWidget($currentAsset){
        \Yii::$app->controller->view->registerJsVar('labelAdd',"Metti in evidenza");
        \Yii::$app->controller->view->registerJsVar('labelRemove',"Rimuovi da evidenza");
        $pinIconUrl = $currentAsset->baseUrl ."/sprite/material-sprite.svg#ic_pin-outline";
        $pinOffIconUrl = $currentAsset->baseUrl ."/sprite/material-sprite.svg#ic_pin";
        $js = <<<JS
    $(document).on('click','.add-remove-pin',function(e){
        e.preventDefault();
        var commentId = $(this).attr('data-key');
        $.ajax({
               url: '/comments/comment/add-remove-evidence',
               type: 'get',
               data: {
                         id: commentId
                     },
               success: function (data) {
                   var linkComment = $('a[data-key="'+commentId+'"]');
                    if(data['action'] == 'selected'){
                            $(linkComment).attr('title', labelRemove);
                            $(linkComment).attr('data-original-title', labelRemove).tooltip('hide').tooltip('show');
                            $(linkComment).parents('.media').addClass('added-to-evidence');
                            $(linkComment).find('svg use').attr('xlink:href', '$pinOffIconUrl');
                            
                    }else{
                            $(linkComment).attr('title', labelAdd);
                            $(linkComment).attr('data-original-title', labelAdd).tooltip('hide').tooltip('show');
                            $(linkComment).parents('.media').removeClass('added-to-evidence');
                            $(linkComment).find('svg use').attr('xlink:href', '$pinIconUrl');
                    }               
               }
        });
    })
JS;
        \Yii::$app->controller->view->registerJs($js);
        \Yii::$app->controller->view->registerCss(".added-to-evidence {
background-color: #eefaf6; padding-left:8px; padding-top:8px
}");
    }

}