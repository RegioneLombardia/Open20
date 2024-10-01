<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;

\open20\socialwall\assets\ModuleSocialWallAsset::register($this);
$jsFuncInputName = str_replace("-", "_", $inputId);

$tableRow = $this->render('picked-posts-selector/row');
$postPreview = $this->render('picked-posts-selector/post-preview');

$this->registerCss(<<<CSS
#$parentContainerId .summary-design {
display: none !important;
}
CSS
);
?>

<div style="display: none;">
    <?=
        $form->field($socialwallModel, 'socialwallPickedPosts')->hiddenInput([
            'id' => $jsFuncInputName . '-picked-post-list',
            'value' => $selectedList,
        ]);
    ?>
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div style="display:flex;align-items: center;" class="m-b-10">
                <h5 class="mt-auto"><?= \open20\socialwall\Module::t('socialwall', 'Contenuti social correlati'); ?></h5>
                <div class="ml-auto">
                    <?= \yii\helpers\Html::button(\open20\socialwall\Module::t('socialwall', 'Aggiungi'), [
                        'id' => $jsFuncInputName . '-btn-add-picked-post',
                        'class' => 'btn btn-primary',
                        'onClick' => "socialwallSelectPostsOpen()"
                    ]); ?>
                </div>
            </div>
            <div id="<?= $jsFuncInputName ?>-posts-container">
                <?= \open20\amos\core\views\DataProviderView::widget([
                    'dataProvider' => $selectedPostsDataProvider,
                    'currentView' => [
                        'name' => 'grid',
                        'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                        'url' => '?currentView=grid'
                    ],
                    'gridView' => [
                        'columns' => [
                            [
                                'attribute' => 'publication_date',
                                'value' => function ($model) {
                                    return Yii::$app->formatter->asDate(
                                        strtotime($model->publication_date),
                                        isset(
                                            \Yii::$app->components['formatter']['datetimeFormat']) ?
                                            \Yii::$app->components['formatter']['datetimeFormat'] :
                                            'd-m-Y H:i:s');
                                }
                            ],
                            [
                                'attribute' => 'text',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return \yii\helpers\StringHelper::truncate(str_replace("&amp;", "&", strip_tags($model->text)), 200, '...');
                                },
                            ],
                            [
                                'attribute' => 'type',
                                'format' => 'raw',
                                'label' => $model->attributeLabels()['type'],
                                'value' => function ($model) {
                                    switch ($model->type) {
                                        case \open20\socialwall\utility\Social::FACEBOOK:
                                            return '<span class="icon icon-lg icon-white mdi mdi-facebook" style="font-size: 40px;"></span>';
                                        case \open20\socialwall\utility\Social::INSTAGRAM:
                                            return '<span class="icon icon-lg icon-white mdi mdi-instagram" style="font-size: 40px;"></span>';
                                        case \open20\socialwall\utility\Social::TWITTER:
                                            return '<span class="icon icon-lg icon-white mdi mdi-twitter" style="font-size: 40px;"></span>';
                                    }
                                },
                            ],
                            [
                                'format' => 'raw',
                                'label' => $model->attributeLabels()['post_image'],
                                'value' => function ($model) {
                                    if(!empty($model->hasOneFile('post_image')->one())) {
                                        return "<img src=\"{$model->getPostImage()->getWebUrl('square_small')}\" width='100px' />";
                                    } else {
                                        return "";
                                    }
                                },
                            ],
                            [
                                'format' => 'raw',
                                'label' => '',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::button(
                                        'x',
                                        [
                                            'class' => 'btn btn-danger',
                                            'onClick' => "removeSocialwallPostAction({$model->id})",
                                        ]
                                    );
                                },
                                'contentOptions' => [
                                    'class' => 'action-buttons',
                                ]
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php
// ASSET SOCIAL MODAL
$modalTitle = \open20\socialwall\Module::t('socialwall', 'Asset Social');
echo <<<HTML
<div class="modal fade" id="{$jsFuncInputName}-socialwall-select-posts" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="exampleModalLabel">{$modalTitle}</h5>
            <div class="socialwall-select-posts-search-fields m-b-5">
                <div class="col-md-8">
HTML
    .
    $form->field($modelSocialwallPosts, 'searchPostTags')->widget(\xj\tagit\Tagit::classname(), [
        'clientOptions' => [
            'singleField' => true,
        ],
        'id' => 'socialwallposts-searchposttags_ids',//sembran doppi gli id ma servono per come lavora il plugin
        'options' => ['id' => 'socialwallposts-searchposttags_ids'],
    ]) .
    <<<HTML
                </div>
        
                <!-- type -->
                <div class="col-md-8">
HTML
    .
    $form->field($modelSocialwallPosts, 'type')->inline(true)->checkboxList(\open20\socialwall\utility\Social::getSocialsEnabled())->label(\open20\socialwall\Module::t('socialwall', "Social configurati"))
    .
    <<<HTML
                </div>
        
                <!-- publication_date -->
                <div class="col-md-8"> 
HTML
    .
    $form->field($modelSocialwallPosts, 'fromPublicationDate')->widget(\kartik\widgets\DatePicker::class,
        [
            'pluginOptions' => [
                "endDate" => "0d",
                'format' => 'dd-mm-yyyy',
            ],
        ]
    ) .
    <<<HTML
                </div>
                
                        <div class="col-xs-12">
                        <div class="pull-right">
HTML
    . \yii\helpers\Html::button(Yii::t('amoscore', 'Reset'), ['class' => 'reset-btn btn btn-secondary', 'onclick' => 'resetFieldsInSocialwallPostsModal()']) .
    \yii\helpers\Html::button(Yii::t('amoscore', 'Search'), ['class' => 'search-btn btn btn-navigation-primary', 'onclick' => 'searchPostsInSocialwallPostsModal()']) .
    <<<HTML
                        </div>
                    </div>
            
                    <div class="clearfix"></div>
            </div>
            <div class="socialwall-select-posts-js-load-container"></div>
          </div>
        </div>
    </div>
</div>
HTML;

$labelPreviewPost = \open20\socialwall\Module::t('socialwall', 'Anteprima post');

// LISTA ANTEPRIMA POST
echo '<div id="'.$jsFuncInputName.'-posts-previews-pool"></div>';
foreach($selectedPostsDataProvider->getModels() as $model) {
    $post = $model->attributes;
    $recordId = $model->id;
    $previewPost = '<div class="it-grid-list-wrapper it-image-label-grid it-masonry">' . \open20\socialwall\widgets\SocialwallItemWidget::widget([
            "post" => \open20\socialwall\utility\Social::formatPost(
                $post['type'],
                $model->socialwallPostsProfile->profile_name,
                !empty($model->profile_picture)
                    ? $model->profile_picture
                    : null,
                $post['text'],
                !empty($model->hasOneFile('post_image')->one())
                    ? $model->getPostImage()->getWebUrl()
                    : null,
                $post['permalink'],
                $post['publication_date'],
                $model->socialwallPostsProfile->profile_url,
                $post['media_type']
            ),
            "postContainerId" => "post-preview-" . $model->id,
        ]) . '</div>';

    echo <<<HTML
<div class="modal fade" id="modal-post-{$recordId}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{$recordId}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="modalLabel{$recordId}">{$labelPreviewPost}</h5>
                {$previewPost}
            </div>
        </div>
    </div>
</div>
HTML;

    // JS PER ANTEPRIMA POST + MODALI ANTEPRIMA POST

    $this->registerJs(<<<JS
$("#$jsFuncInputName-posts-container table tr[data-key={$recordId}] td:not(.action-buttons)").attr("data-toggle", "modal");
$("#$jsFuncInputName-posts-container table tr[data-key={$recordId}] td:not(.action-buttons)").attr("data-target", "#modal-post-{$recordId}");
$($("#post-preview-{$recordId}").children()[0]).width("500px");
JS
    );

}
?>

<?php
    $labelPublicationDate = \open20\socialwall\Module::t('socialwall', 'Data creazione');
    $labelText = \open20\socialwall\Module::t('socialwall', 'Testo');
    $labelType = \open20\socialwall\Module::t('socialwall', 'Social');
// JS FUNCTIONS/UTILS
$this->registerJs(<<<JS
socialwallSelectPostsOpen = function () {
  $("#{$jsFuncInputName}-btn-add-picked-post").addClass("disabled");
  $("#{$jsFuncInputName}-btn-add-picked-post").attr("disabled", "disabled");
  
  $.ajax({
    timeout: 30000,
    url: '/socialwall/socialwall-posts/socialwall-select-posts',
    method: 'GET',
    data: {},
  }).done(function (res) {
    $("#{$jsFuncInputName}-socialwall-select-posts .socialwall-select-posts-js-load-container").html(res);
    $("#{$jsFuncInputName}-socialwall-select-posts").modal('show');
  })
  .always(function () {
    $("#{$jsFuncInputName}-btn-add-picked-post").removeClass("disabled");
    $("#{$jsFuncInputName}-btn-add-picked-post").removeAttr("disabled");
  });
}

window.loadPageInSelectPostsModal = function (url) {
  $("#socialwall-select-posts-grid .pagination li").addClass("disabled");
  $("#socialwall-select-posts-grid .thead_static a").addClass("bg-info");
  $.ajax({
    timeout: 30000,
    url: url,
    method: 'GET'
  }).done(function (res) {
    $("#{$jsFuncInputName}-socialwall-select-posts .socialwall-select-posts-js-load-container").html(res);
  })
  .fail(function () {
    $("#socialwall-select-posts-grid .pagination li").removeClass("disabled");
    $("#socialwall-select-posts-grid .thead_static a").removeClass("bg-info");
  });
}

removeSocialwallPostAction = function (postId) {
  $("#$jsFuncInputName-posts-container table tr[data-key="+postId+"]").remove();
  
  var selectedPostList = $("#$jsFuncInputName-picked-post-list").val().split(",");
  var index = selectedPostList.indexOf(postId.toString());
  if(index > -1) {
    selectedPostList.splice(index, 1);
  }
  
  $("#$jsFuncInputName-picked-post-list").val(selectedPostList.join(","));
}

window.selectPostAction = function (postId) {
  $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").addClass("disabled");
  $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").attr("disabled", "disabled");
  
  var alreadySelectedPosts = $("#{$jsFuncInputName}-picked-post-list").val();
  
  var addPost = true;
  if(alreadySelectedPosts !== undefined && alreadySelectedPosts !== null && alreadySelectedPosts !== "") {
    var postsList = alreadySelectedPosts.split(",");
    addPost = postsList.find(function (el) {return el == postId}) === undefined;
  }
  
  if(addPost) {
        $.ajax({
        timeout: 10000,
        url: '/socialwall/socialwall-posts/socialwall-selected-post-to-append',
        method: 'GET',
        data: {postId: postId},
      }).done(function (res) {
          res = JSON.parse(res);
          
          if($("#$jsFuncInputName-picked-post-list").val() == '') {
            $("#$jsFuncInputName-picked-post-list").val(postId);
          } else {
            $("#$jsFuncInputName-picked-post-list").val($("#$jsFuncInputName-picked-post-list").val() + ',' + postId);
          }
    
          let labelPublicationDate = "{$labelPublicationDate}";
          let labelText = "{$labelText}";
          let labelType = "{$labelType}";
          let labelPreviewPost = "{$labelPreviewPost}";
    
          let {
              id,
              publication_date,
              typeIcon,
              text,
              image,
              preview
          } = res;
          
          if($("#{$jsFuncInputName}-posts-container table tbody").has("p.grid-view-empty").length > 0) {
            $("#{$jsFuncInputName}-posts-container table tbody").empty();
          }
          
          // Table Row
          $("#{$jsFuncInputName}-posts-container table tbody").append(`$tableRow`);
          // Preview Post
          $("#{$jsFuncInputName}-posts-previews-pool").append(`$postPreview`);
          $($("#{$jsFuncInputName}-posts-previews-pool #post-preview-" + id).children()[0]).width("500px");
          $("#{$jsFuncInputName}-posts-previews-pool #post-preview-" + id + " .img-responsive-wrapper .img-responsive .img-wrapper img").width("100%");
          
          $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").html("&check;");
          $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").addClass("btn-outline-primary");
    //      setTimeout(function () {
    //        $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").html("+");
    //        $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").removeClass("btn-outline-primary");
    //      }, 1500);
      })
      .fail(function () {
        $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").html("+");
        $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").removeClass("btn-outline-primary");
        $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").removeClass("disabled");
        $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").removeAttr("disabled");
      })
      .always(function () {
    //    $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").removeClass("disabled");
    //    $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").removeAttr("disabled");
      });
  } else {
    $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").html("&check;");
    $("#{$jsFuncInputName}-socialwall-select-posts button[data-button-model-id="+postId+"]").addClass("btn-outline-primary");
  }
  
  
}

resetFieldsInSocialwallPostsModal = function () {
  $("#$jsFuncInputName-socialwall-select-posts #socialwallposts-searchposttags_ids").tagit("removeAll");
  $("#$jsFuncInputName-socialwall-select-posts #socialwallposts-frompublicationdate").val("");
  $("#$jsFuncInputName-socialwall-select-posts .field-socialwallposts-type input[type=checkbox]").each(function (el) { 
    var elem = $($("#$jsFuncInputName-socialwall-select-posts .field-socialwallposts-type input[type=checkbox]")[el]);
    elem.prop('checked', false);
  });
  
  $("#$jsFuncInputName-socialwall-select-posts .reset-btn").addClass("disabled");
  $("#$jsFuncInputName-socialwall-select-posts .reset-btn").attr("disabled", "disabled");
  $("#$jsFuncInputName-socialwall-select-posts .search-btn").addClass("disabled");
  $("#$jsFuncInputName-socialwall-select-posts .search-btn").attr("disabled", "disabled");
  $.ajax({
    timeout: 20000,
    url: '/socialwall/socialwall-posts/socialwall-select-posts',
    method: 'GET'
  }).done(function (res) {
    $("#{$jsFuncInputName}-socialwall-select-posts .socialwall-select-posts-js-load-container").html(res);
  })
  .always(function () {
    $("#$jsFuncInputName-socialwall-select-posts .reset-btn").removeClass("disabled");
    $("#$jsFuncInputName-socialwall-select-posts .reset-btn").removeAttr("disabled");
    $("#$jsFuncInputName-socialwall-select-posts .search-btn").removeClass("disabled");
    $("#$jsFuncInputName-socialwall-select-posts .search-btn").removeAttr("disabled");
  });
}

searchPostsInSocialwallPostsModal = function () {
  var fieldSearchKeys = "SocialwallPostsSearch[searchPostTags]";
  var searchKeys = $("#$jsFuncInputName-socialwall-select-posts #socialwallposts-searchposttags_ids").val();
  var fieldType = "SocialwallPostsSearch[type][]";
  var fieldFromPublicationDate = "SocialwallPostsSearch[fromPublicationDate]";
  var fromPublicationDate = $("#$jsFuncInputName-socialwall-select-posts #socialwallposts-frompublicationdate").val();
  
  var urlParams = new URLSearchParams();
  
  if(searchKeys !== "") {
    urlParams.append(fieldSearchKeys, searchKeys);
  }
  
  $("#$jsFuncInputName-socialwall-select-posts .field-socialwallposts-type input[type=checkbox]").each(function (el) { 
    var elem = $($("#$jsFuncInputName-socialwall-select-posts .field-socialwallposts-type input[type=checkbox]")[el]);
    if(elem.is(':checked')) {
      urlParams.append(fieldType, elem.val());
    }
  });
  
  if(fromPublicationDate !== "") {
    urlParams.append(fieldFromPublicationDate, fromPublicationDate);
  }
  
  urlParams = urlParams.toString();
  
  if(urlParams !== "") {
    urlParams = "?" + urlParams;
  }
  
  $("#$jsFuncInputName-socialwall-select-posts .reset-btn").addClass("disabled");
  $("#$jsFuncInputName-socialwall-select-posts .reset-btn").attr("disabled", "disabled");
  $("#$jsFuncInputName-socialwall-select-posts .search-btn").addClass("disabled");
  $("#$jsFuncInputName-socialwall-select-posts .search-btn").attr("disabled", "disabled");
  $.ajax({
    timeout: 30000,
    url: '/socialwall/socialwall-posts/socialwall-select-posts' + urlParams,
    method: 'GET'
  }).done(function (res) {
    $("#{$jsFuncInputName}-socialwall-select-posts .socialwall-select-posts-js-load-container").html(res);
  })
  .always(function () {
    $("#$jsFuncInputName-socialwall-select-posts .reset-btn").removeClass("disabled");
  $("#$jsFuncInputName-socialwall-select-posts .reset-btn").removeAttr("disabled");
  $("#$jsFuncInputName-socialwall-select-posts .search-btn").removeClass("disabled");
  $("#$jsFuncInputName-socialwall-select-posts .search-btn").removeAttr("disabled");
  });
}
JS
);

// CSS PER ANTEPRIMA POST + MODALI ANTEPRIMA POST

$this->registerCss(<<<CSS
#{$jsFuncInputName}-posts-container table td { cursor: pointer; }
CSS
);
?>
