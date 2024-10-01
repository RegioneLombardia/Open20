<?php
\open20\socialwall\assets\ModuleSocialWallAsset::register($this);
$jsFuncInputName = str_replace("-", "_", $inputId);
?>

<div class="row">
    <div class="col-xs-12">
        <?=
            $form->field($model, 'socialwallLiveChoices')->checkboxList(\open20\socialwall\utility\Social::getSocialwallTokensEnabled(), [
                'id' => $jsFuncInputName . '-live-choices-list',
            ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div id="<?= $jsFuncInputName ?>-search-tags-list">
            <?=
                $form->field($model, 'socialwallLiveTags')->widget(\xj\tagit\Tagit::classname(), [
                    'clientOptions' => [
                        'singleField' => true,
                        'id' => $jsFuncInputName . '-search-tags-list',
                        'onTagRemoved' => new \yii\web\JsExpression(<<<EOF
function() {
if(window.removeTagErrorMessage !== undefined) {
setTimeout(function () {
window.checkAndAddRequiredFieldsFilled();
}, 600);
}
}
EOF),
                        'onTagAdded' => new \yii\web\JsExpression(<<<EOF
function() {
if(window.removeTagErrorMessage !== undefined) {
window.checkAndAddRequiredFieldsFilled();
}
}
EOF),
                    ],
                    'id' => $jsFuncInputName . 'search-tags-list_ids', //sembran doppi gli id ma servono per come lavora il plugin
                    'options' => ['id' => $jsFuncInputName . 'search-tags-list_ids'],
                ])
            ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?=
        $form->field($model, 'update')->checkbox();
        ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?=
            \open20\amos\core\helpers\Html::button('Mostra anteprima', [
                'id' => $jsFuncInputName . '-btn-live-preview',
                'class' => 'btn btn-primary',
                'onclick' => $jsFuncInputName . "previewLiveSocialwall()",
            ]);
        ?>
    </div>
</div>

<?php
$errorTagMessage = \open20\socialwall\Module::t('socialwall', 'Scegliere almeno una chiave di ricerca');
$formId = $form->id;
$socialwallTypeLiveSelector = \open20\socialwall\utility\Social::LIVE_WALL;
$this->registerJs(<<<JS
//Socialwall[socialwallLiveChoices]
$("input[name^=\"Socialwall[socialwallLiveChoices]\"]").on("change", function () {
  window.checkAndAddRequiredFieldsFilled();
});

window.checkAndAddRequiredFieldsFilled = function () {
    window.removeTagErrorMessage();
    if(checkRequiredFieldsFilled()) {
      window.addRequiredToFields();
    } else {
      window.removeRequiredToFields();
    }
}

window.checkRequiredFieldsFilled = function () {
  var keywords = $("#{$jsFuncInputName}-search-tags-list input[type=\"hidden\"]").val();
  if($("input[id^=\"Socialwall[socialwallLiveChoices][]\"]:checked").length > 0) {
    return true;
  }
  if(keywords !== undefined && keywords !== null && keywords !== "") {
    return true;
  }
  return false;
}

window.addRequiredToFields = function () {
  $("div.form-group.field-{$jsFuncInputName}search-tags-list_ids").addClass("required");
  $("div.form-group.field-{$jsFuncInputName}-live-choices-list").addClass("required");
};
window.removeRequiredToFields = function () {
  $("div.form-group.field-{$jsFuncInputName}search-tags-list_ids").removeClass("required");
  $("div.form-group.field-{$jsFuncInputName}-live-choices-list").removeClass("required");
};
window.addTagErrorMessage = function () {
  $("div.form-group.field-{$jsFuncInputName}search-tags-list_ids").find(".tooltip-error-field .help-block-error").text("$errorTagMessage");
};
window.removeTagErrorMessage = function () {
  $("div.form-group.field-{$jsFuncInputName}search-tags-list_ids").find(".tooltip-error-field .help-block-error").text("");
};
window.scrollToLiveSelector = function (){
    var aTag = $("select#{$inputId}-socialwall-type-selector");
    $('html,body').animate({scrollTop: aTag.offset().top},'slow');
};

// SOCIALWALL PREVIEW LIVE MODAL
{$jsFuncInputName}previewLiveSocialwall = function () {
  // $("#{$jsFuncInputName}-live-choices-list");
  var keywords = $("#{$jsFuncInputName}-search-tags-list input[type=\"hidden\"]").val();
  if($("input[id^=\"Socialwall[socialwallLiveChoices][]\"]:checked").length > 0 &&
    keywords !== undefined && keywords !== null && keywords !== "") {
    window.removeTagErrorMessage();
  var socialwallTokensIds = "";
  $("input[id^=\"Socialwall[socialwallLiveChoices][]\"]:checked").each(function (index) {
    socialwallTokensIds+=$($("input[id^=\"Socialwall[socialwallLiveChoices][]\"]:checked")[index]).val() + ",";
  });
  socialwallTokensIds=socialwallTokensIds.substring(0, socialwallTokensIds.length-1);
  
  var data = {'socialwallTokensIds':socialwallTokensIds};
  
  // var keywords = $("#{$jsFuncInputName}-search-tags-list input[type=\"hidden\"]").val();
  if(keywords !== undefined && keywords !== null && keywords !== "") {
    data['keywords'] = keywords;
  }

  $("#{$jsFuncInputName}-btn-live-preview").addClass("disabled");
  $("#{$jsFuncInputName}-btn-live-preview").attr("disabled", "disabled");
  $.ajax({
    timeout: 30000,
    url: '/socialwall/socialwall/preview-socialwall',
    method: 'GET',
    // data: {'socialwallTokensIds':$(elem).parent().attr('data-post-id')}
    data: data,
  }).done(function (res) {
    $("#{$jsFuncInputName}preview-modal .socialwall-js-load-container").html(res);
    $("#{$jsFuncInputName}preview-modal").modal('show');
  })
  .always(function () {
    $("#{$jsFuncInputName}-btn-live-preview").removeClass("disabled");
    $("#{$jsFuncInputName}-btn-live-preview").removeAttr("disabled");
  });
  } else {
    window.addTagErrorMessage();
  }
}

$("form#{$formId}").on("beforeValidate", function () {
  if($("select#{$inputId}-socialwall-type-selector").val() === "{$socialwallTypeLiveSelector}") {
    var keywords = $("#{$jsFuncInputName}-search-tags-list input[type=\"hidden\"]").val();
    if($("input[id^=\"Socialwall[socialwallLiveChoices][]\"]:checked").length > 0) {
      if(keywords !== undefined && keywords !== null && keywords !== "") {
        window.removeTagErrorMessage();
      } else {
        window.addRequiredToFields();
        window.addTagErrorMessage();
        window.scrollToLiveSelector();
        return false;
      }
    } else {
      if(keywords !== undefined && keywords !== null && keywords !== "") {
        window.addRequiredToFields();
        window.addTagErrorMessage();
        window.scrollToLiveSelector();
        return false;
      } else {
        window.removeTagErrorMessage();
      }
    }
  }
});

JS
    );

$this->registerJs(<<<JS
window.checkAndAddRequiredFieldsFilled();
JS
, \yii\web\View::POS_LOAD);

    $modalTitle = \open20\socialwall\Module::t('socialwall', 'Anteprima Social Wall');
    echo <<<HTML
<div class="modal fade" id="{$jsFuncInputName}preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="exampleModalLabel">{$modalTitle}</h5>
            <div class="socialwall-js-load-container"></div>
          </div>
        </div>
    </div>
</div>
HTML;

?>