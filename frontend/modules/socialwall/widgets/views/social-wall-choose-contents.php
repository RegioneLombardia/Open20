<?php

\frontend\modules\socialwall\assets\ModuleSocialWallAsset::register($this);

$socialTemplates = [
  "instagram" => $this->render('../views/jsTemplates/instagram_detail'),
  "twitter" => $this->render('../views/jsTemplates/twitter_detail'),
];

?>

<div class="row">
  <div class="col-xs-12">
    <div class="input-group">
      <?php
      $tooltipSocialWall = '<span class="mdi mdi-information-outline" title="' . Yii::t('socialwall', 'La ricerca distingue tra lettere Maiuscole o minuscole') . '" data-toggle="tooltip"></span>';
      ?>
      <?=
      \yii\helpers\Html::textInput($name, '', array_merge($inputOptions, [
        'id' => $inputId . "-search-box",
        'placeholder' => Yii::t('socialwall', "Inserisci chiave di ricerca contenuti social"),
        'class' => 'form-control',
        'label' => Yii::t('socialwall', "Tag di interesse"),
        'hint' => Yii::t('socialwall', "Inserisci un hashtag specifico o una parola da ricercare all'interno dei contenuti social {tooltipWall}", ['tooltipWall' => $tooltipSocialWall])
      ]));
      ?>
      <div class="input-group-btn">
        <?=
        \yii\bootstrap\Button::widget([
          'label' => Yii::t('socialwall', "Cerca"),
          'options' => array_merge($buttonOptions, [
            'id' => $inputId . "-search-button",
            'class' => 'btn-primary',
            'style' => 'height:45px;',
          ]),
        ]);
        ?>
      </div>
    </div>
  </div>
</div>

<div id="<?= $inputId ?>-social-wall-choose-contents-container" class="row m-b-30">
  <div class="col-xs-12 m-b-30 flexbox" style="align-items:center;">
    <p id="<?= "$inputId-label-search-history" ?>" class="m-r-10" style="display:none;"><?= Yii::t('socialwall', 'Ultime ricerche') . ':' . ' ' ?></p>
    <div id="<?= "$inputId-search-history" ?>">
        <div id="<?= "$inputId-loading-search-history" ?>">
            <span class="glyphicon glyphicon-refresh spinning" role="status"><span class="sr-only">Loading...</span></span>
        </div>
    </div>
    <?= \yii\helpers\Html::hiddenInput($name . "[socialwallSearchHistory]", null, ['id' => $inputId . "-search-history-data"]); ?>
    
  </div>
  <?php
  foreach ($enabledSocialFeeds as $socialFeed) :
  ?>
    <div class="col-xs-12 col-md-6">
      <p class="h5 p-t-5"><?= $socialLabels[$socialFeed]; ?></p>
      <div class="box-social">
        <div id="<?= "$inputId-loading-$socialFeed" ?>" style="height: inherit;background-color: rgba(0,0,0,0.3);position: absolute;width: 92%;object-fit: initial;margin: -5px;z-index: 9;">
          <span class="glyphicon glyphicon-refresh spinning" style="position: absolute; top: 50%; left: 40%; transform: translate(-50%,-50%); font-size: 80px;color:#fff;" role="status"><span class="sr-only">Loading...</span></span>
        </div>
        <div class="it-grid-list-wrapper it-image-label-grid it-masonry">
          <div class="card-columns social-wall-container" id="<?= "$inputId-box-$socialFeed" ?>">
          </div>
        </div>
        <div id="<?= "$inputId-posts-not-found-$socialFeed" ?>" class="<?= $inputId ?>-posts-not-found bg-danger" style="position: absolute;width: 91.8%;object-fit: initial;bottom:1px;height:auto;margin-left:-5px;padding: 5px;z-index:1;display:none;">
          <span><?= \Yii::t('socialwall', 'Non sono stati trovati nuovi post per questo social con le chiavi di ricerca che hai inserito.'); ?></span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php
$moduleId = "";
$moduleRecordId = !empty($model->id) ? $model->id : "";
if (array_key_exists($model::className(), Yii::$app->getModule('socialwall')->modulesIdClassMap)) {
  $moduleId = Yii::$app->getModule('socialwall')->modulesIdClassMap[$model::className()];
}

// Overriding fields with explicit data if set
if(!empty($explicitModuleId)) {
    $moduleId = $explicitModuleId;
}
if(!empty($explicitModuleRecordId)) {
    $moduleRecordId = $explicitModuleRecordId;
}

$this->registerJs(
  <<<JS
      // RETRIEVE ALREADY SELECTED POSTS
      const moduleId = "{$moduleId}";
      const moduleRecordId = "{$moduleRecordId}";

      if(moduleId !== "" && moduleRecordId !== "") {
        retrieveSearchHistory(moduleId, moduleRecordId);
        
        $.ajax({
            url: "/socialwall/api/selected-wall-posts-per-social",
            data: {
              moduleId: moduleId,
              moduleRecordId: moduleRecordId,
            },
            method: "GET",
        })
        .success(function (res) {
          res = JSON.parse(res);
          // console.log(res);
          
          if(typeof res === 'object' &&
            !Array.isArray(res) &&
            res !== null) {
          //$("#{$inputId}-social-wall-choose-contents-container").fadeIn();
          
          const boxInstagram = $("#{$inputId}-box-instagram");
          // boxInstagram.empty();
          if(res["instagram"] !== undefined) {
            // console.log(boxInstagram);
            // console.log(res["instagram"]);
            
            for(const contentId in res["instagram"]) {
              
              let {
                  media_type,
                  media_url,
                  userinfo,
                  username,
                  caption,
                  timestamp,
                  permalink,
                  id,
                  posting_date_formatted,
              } = JSON.parse(res["instagram"][contentId]);
              
              let jsonContent = res["instagram"][contentId];
              
              let checked = true;
              
              let fieldName = "{$name}";
              let fieldId = "{$inputId}";
              let blockId = fieldId + "-instagram-" + id;
              
              if($("#"+blockId).length === 0) {
                boxInstagram.append(`{$socialTemplates['instagram']}`);
              }
              
            }
            
          }
          
          const boxTwitter = $("#{$inputId}-box-twitter");
          // boxTwitter.empty();
          if(res["twitter"] !== undefined) {
            // console.log(boxTwitter);
            // console.log("res:");
            // console.log(res["twitter"]);
            
            for(const contentId in res["twitter"]) {
              
              // console.log("sing:");
              // console.log(JSON.parse(res["twitter"][contentId]));
              // console.log(res["twitter"][contentId]);
              
              let {
                  text,
                  userinfo,
                  created_at,
                  id,
                  posting_date_formatted,
              } = JSON.parse(res["twitter"][contentId]);
              
              let jsonContent = res["twitter"][contentId];
              
              let checked = true;
              
              let fieldName = "{$name}";
              let fieldId = "{$inputId}";
              let blockId = fieldId + "-twitter-" + id;
              
              if($("#"+blockId).length === 0) {
                boxTwitter.append(`{$socialTemplates['twitter']}`);
              }
              
            }
            
          }
          
          }
          
          $("#{$inputId}-loading-instagram").hide();
          $("#{$inputId}-loading-twitter").hide();
          
        });
      } else {
        $("#{$inputId}-loading-search-history").hide();
        // Retrieve automatically post with keys "regione lombardia"
        retrievePostsFromApi("regione lombardia");
      }

      
      
      
      // ON SEARCH BUTTON CLICK
      $("#{$inputId}-search-button").click(function (e) {
        e.preventDefault();
        
        if(!$("#{$inputId}-social-wall-choose-contents-container").is(":visible")) {
          $("#{$inputId}-social-wall-choose-contents-container").fadeIn();
        }
        
        doSearch($("#{$inputId}-search-box").val());
        
      });

    $('form').on('beforeSubmit', function(e) {
      removeUnselectedContents();
      searchHistoryToJson();
    });
    
    function doSearch(keys, saveSearchHistory = true) {
        $("#{$inputId}-loading-instagram").show();
        $("#{$inputId}-loading-twitter").show();
        $(".{$inputId}-posts-not-found").hide();
        removeUnselectedContents();
        
        if(keys == ''){
            keys = "regione lombardia";
            saveSearchHistory = false;
        }
        
        retrievePostsFromApi(keys);
        if(saveSearchHistory) {
          addSearchHistory(keys);
        }
    }
    
    function retrievePostsFromApi(keys) {
      $.ajax({
            url: "/socialwall/api/wall-posts-per-social",
            data: {
              tag: keys,
            },
            method: "GET",
        })
        .done(function () {
          $("#{$inputId}-loading-instagram").hide();
          $("#{$inputId}-loading-twitter").hide();
        })
        .success(function (res) {
          res = JSON.parse(res);
          // console.log(res);
          
          const boxInstagram = $("#{$inputId}-box-instagram");
          // boxInstagram.empty();
          if(res["instagram"] !== undefined) {
            // console.log(boxInstagram);
            // console.log(res["instagram"]);
            
            for(const contentId in res["instagram"]) {
              
              let {
                  media_type,
                  media_url,
                  userinfo,
                  username,
                  caption,
                  timestamp,
                  permalink,
                  id,
                  posting_date_formatted,
              } = res["instagram"][contentId];
              
              let jsonContent = JSON.stringify(res["instagram"][contentId]);
              
              let checked = false;
              
              let fieldName = "{$name}";
              let fieldId = "{$inputId}";
              let blockId = fieldId + "-instagram-" + id;
              
              if($("#"+blockId).length === 0) {
                boxInstagram.append(`{$socialTemplates['instagram']}`);
              }
              
            }
            
            
          } else {
            $("#{$inputId}-posts-not-found-instagram").show();
          }
          
          const boxTwitter = $("#{$inputId}-box-twitter");
          // boxTwitter.empty();
          if(res["twitter"] !== undefined) {
            // console.log(boxTwitter);
            // console.log("res:");
            // console.log(res["twitter"]);
            
            for(const contentId in res["twitter"]) {
              
              // console.log("sing:");
              // console.log(res["twitter"][contentId]);
              // console.log(JSON.stringify(res["twitter"][contentId]));
              
              let {
                  text,
                  userinfo,
                  created_at,
                  id,
                  posting_date_formatted,
              } = res["twitter"][contentId];
              
              let jsonContent = JSON.stringify(res["twitter"][contentId]);
              
              let checked = false;
              
              let fieldName = "{$name}";
              let fieldId = "{$inputId}";
              let blockId = fieldId + "-twitter-" + id;
              
              if($("#"+blockId).length === 0) {
                boxTwitter.append(`{$socialTemplates['twitter']}`);
              }
              
            }
          } else {
            $("#{$inputId}-posts-not-found-twitter").show();
          }
          
        });
    }
    
    setHistoryKeyClick();
    
    function setHistoryKeyClick(element) {
        var el = null;
        
        if(element !== null && element !== undefined) {
          el = $(element);
        } else {
          el = $("#{$inputId}-search-history li");
        }
        
        // ON SEARCH HISTORY KEYWORD CLICK
        el.click(function (e) {
          e.preventDefault();
          e.stopPropagation();
          //console.log($(this).text());
          setKeyInSearchBoxAndSearch($(this).text());
        });
    }
    
    function setKeyInSearchBoxAndSearch(key) {
      $("#{$inputId}-search-box").val(key);
      doSearch(key, false);
    }

    function removeUnselectedContents() {
      var contentSelect = $("input[class*='content-select']");
      $(contentSelect).each(function () {
          if(!$(this).is(":checked")) {
            $("#"+$(this).data("post-id")).remove();
          }
      });
    }
    
    function addSearchHistory(key) {
      var element = $("<a href='javascript:void(0)' title='Ricerca di nuovo' class='sw-tags' style='padding-right:15px;'>"+key+"</a>");
      setHistoryKeyClick(element);
      
      $("#{$inputId}-search-history").prepend(element);
      $("#{$inputId}-label-search-history").show();
      
      if($("#news-socialwall-search-history > a").length > 10) {
        $("#{$inputId}-search-history > a:last-child").remove();
      }
    }
    
    function searchHistoryToJson() {
        var searchHistory = [];
        
        $($("#{$inputId}-search-history a").get().reverse()).each(function() {
            searchHistory.push($(this).text());
        });
        
        $("#{$inputId}-search-history-data").val(JSON.stringify(searchHistory));
    }
    
    function retrieveSearchHistory(moduleId, moduleRecordId) {
        $.ajax({
            url: "/socialwall/api/search-history",
            data: {
              moduleId: moduleId,
              moduleRecordId: moduleRecordId,
            },
            method: "GET",
        })
        .done(function () {
          $("#{$inputId}-loading-search-history").hide();
        })
        .success(function (res) {
          res = JSON.parse(res);
          
          for(const histSearchKey of res) {
            addSearchHistory(histSearchKey);
          }
        });
    }

JS
);

$this->registerCss(
  <<<CSS
.select-social-post {
    position: absolute;
    z-index: 1;
    left: 5px;
}

.pos-rel {
    position: relative;
}

.select-social-post input {
    width: 20px;
    height: 20px;
}

.glyphicon.spinning {
    animation: spin 1s infinite linear;
    -webkit-animation: spin2 1s infinite linear;
}

.social-search-history ul {
  list-style-type: none;
  padding: 0;
  margin: 5px;
}

.social-search-history li {
    cursor: pointer;
    text-decoration: underline;
    margin: 15px 0;
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg); }
    to { transform: scale(1) rotate(360deg); }
}

@-webkit-keyframes spin2 {
    from { -webkit-transform: rotate(0deg); }
    to { -webkit-transform: rotate(360deg); }
}
CSS
);
?>








<?php

/**
 *
 * NECESSARIO DA IMPORTAZIONE
 *
 */

?>


<?php
$js = <<<JS

        $(document).ready(function() {
  (function() {
    var showChar = 350;
    var ellipsestext = "...";

    $(".truncate").each(function() {
      var content = $(this).html();
      if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content;
        var html =
          '<div class="truncate-text" style="display:block">' +
          c +
          '<span class="moreellipses">' +
          ellipsestext +
          '&nbsp;&nbsp;<a href="" class="moreless more">Leggi tutto</a></span></span></div><div class="truncate-text" style="display:none">' +
          h +
          '<a href="" class="moreless less">Chiudi</a></span></div>';

        $(this).html(html);
      }
    });

    $(".moreless").click(function() {
      var thisEl = $(this);
      var cT = thisEl.closest(".truncate-text");
      var tX = ".truncate-text";

      if (thisEl.hasClass("less")) {
        cT.prev(tX).toggle();
        cT.slideToggle();
      } else {
        cT.toggle();
        cT.next(tX).fadeToggle();
      }
      return false;
    });
    /* end iffe */
  })();

  /* end ready */
});

JS;
$this->registerJs($js);
?>

<?php
$this->registerCss(
  <<<CSS
    .it-grid-list-wrapper.it-masonry > .card-columns {
      column-count: 1;
    }

    a.moreless {
        font-weight: bold;
        color: #000 !important;
        white-space: nowrap;
    }
    
    .box-social {
        border: 1px solid #003354;
        border-radius: 4px;
        padding: 5px;
        height: 500px;
        overflow-y: scroll;
    }

.it-grid-item-wrapper {
  position: relative;
}
.it-grid-item-wrapper .img-responsive-wrapper {
  border-radius: 4px;
}
.it-grid-item-wrapper .img-responsive-wrapper .img-responsive {
  padding-bottom: 66.8122270742%;
}
.it-grid-item-wrapper .it-griditem-text-wrapper {
  display: flex;
  justify-content: space-between;
  padding: 16px 8px;
  align-items: center;
}
.it-grid-item-wrapper .it-griditem-text-wrapper .it-griditem-text {
  font-size: 0.8rem;
  color: #435a70;
  font-weight: 600;
}
.it-grid-item-wrapper .it-griditem-text-wrapper .icon {
  flex-shrink: 0;
  position: relative;
  right: -8px;
  fill: #45cc5e;
  width: 24px;
  height: 24px;
}
.it-grid-item-wrapper.it-grid-item-overlay .it-griditem-text-wrapper {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(23, 50, 77, 0.85);
  padding: 8px 16px;
}
.it-grid-item-wrapper.it-grid-item-overlay .it-griditem-text-wrapper .it-griditem-text {
  color: #fff;
}
.it-grid-item-wrapper.it-grid-item-overlay .it-griditem-text-wrapper .icon {
  fill: #fff;
}
.it-grid-item-wrapper a:hover .it-griditem-text {
  text-decoration: underline;
}
.it-grid-item-wrapper.it-grid-item-double-w .img-responsive-wrapper .img-responsive {
  padding-bottom: calc(33.4061135371% - 2px);
}
.it-grid-item-wrapper.it-grid-item-overlay {
  position: relative;
}
.it-grid-item-wrapper.it-grid-item-overlay .it-griditem-text-wrapper {
  position: absolute;
  bottom: 0;
  right: 0;
  left: 0;
  color: #fff;
  text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
  background-color: transparent;
}
.it-grid-list-wrapper {
  padding-left: 4px;
  padding-right: 4px;
}
.it-grid-list-wrapper div[class^='col-'] {
  padding: 0 2px 4px 2px;
}
.it-grid-list-wrapper .grid-row {
  display: flex;
  margin-left: -2px;
  margin-right: -2px;
  flex-wrap: wrap;
}
.it-grid-list-wrapper.it-image-label-grid {
  padding-left: 16px;
  padding-right: 16px;
}
.it-grid-list-wrapper.it-image-label-grid div[class^='col-'] {
  padding: 0 8px 16px 8px;
}
.it-grid-list-wrapper.it-image-label-grid .grid-row {
  margin-left: -20px;
  margin-right: -20px;
}
.it-grid-list-wrapper.it-masonry .card-columns {
  margin-left: -20px;
  margin-right: -20px;
  column-gap: 0;
}
.it-grid-list-wrapper.it-masonry .card-columns .it-grid-item-wrapper {
  break-inside: avoid-column;
}
.it-grid-list-wrapper.it-masonry .card-columns .it-grid-item-wrapper .img-responsive {
  padding-bottom: initial;
  height: auto;
}
.it-grid-list-wrapper.it-masonry .card-columns .it-grid-item-wrapper .img-responsive .img-wrapper {
  position: inherit;
  left: initial;
  right: initial;
  width: initial;
  height: initial;
}
.it-grid-list-wrapper.it-masonry .card-columns .it-grid-item-wrapper .img-responsive .img-wrapper img {
  height: auto;
  object-fit: initial;
}
@media (min-width: 992px) {
  .it-grid-item-wrapper.it-grid-item-overlay .it-griditem-text-wrapper {
    padding: 16px 24px;
  }
  .it-grid-item-wrapper.it-grid-item-overlay .it-griditem-text-wrapper .it-griditem-text {
    font-size: 1rem;
  }
  .it-grid-item-wrapper .it-griditem-text-wrapper .it-griditem-text {
    font-size: 1rem;
  }
  .it-grid-item-wrapper .it-griditem-text-wrapper .icon {
    width: 32px;
    height: 32px;
  }
}

.p-1 {
  padding: 4px !important;
}

.col-12 {
  position: relative;
  width: 100%;
  padding-right: 6px;
  padding-left: 6px;
  flex: 0 0 100%;
  max-width: 100%;
}

.img-responsive-wrapper .img-responsive .img-wrapper img {
  width: 100%;
  display: block;
  height: 100%;
  object-fit: cover;
}

.icon.icon-lg {
  width: 48px;
  height: 48px;
}

.d-none {
display: none !important;
}

.avatar-wrapper.avatar-extra-text {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}

.avatar-wrapper {
  position: relative;
}

.px-4 {
padding-left: 24px !important;
padding-right: 24px !important;
}

.pt-3 {
padding-top: 16px !important;
}

.avatar-wrapper.avatar-extra-text .extra-text {
  line-height: 1.2em;
  margin-left: 16px;
}

.m-0 {
  margin: 0 !important;
}

.avatar-wrapper.avatar-extra-text .extra-text p, .avatar-wrapper.avatar-extra-text .extra-text time {
  margin: 0;
  text-transform: uppercase;
  font-size: 0.778rem;
}

.pb-1 {
    padding-bottom: 4px !important;
}

.text-right {
  text-align: right !important;
}

.px-2 {
padding-left: 8px !important;
padding-right: 8px !important;
}

.pb-2 {
padding-bottom: 8px !important;
}

.p-3 {
    padding: 16px !important;
}

.avatar-wrapper.avatar-extra-text .avatar {
  flex-shrink: 0;
}

.avatar.size-lg {
  width: 40px;
  height: 40px;
}

.social-wall-container .avatar {
  border: 1px solid #666;
}

.avatar {
  border-radius: 50%;
  width: 100%;
  background-color: #fff;
}

.avatar {
  display: inline-flex;
  overflow: hidden;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  justify-content: center;
  align-items: center;
  background: #ebeced;
    background-color: rgb(235, 236, 237);
  color: #5c6f82;
  box-sizing: content-box;
  transition: background-color 0.2s;
}

CSS
);
?>