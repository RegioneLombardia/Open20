<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$required = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';

?>

<?php
$enableGroup = false;
$tmp = reset($items);
if($tmp){
    if(is_array($tmp)){
        $enableGroup = true;
    }
}
?>

    <div class="position-absolute z-index-1">
        <div class="progress-spinner progress-spinner-double size-sm progress-spinner-active z-index-1 position-relative"
             id="spinner-<?= $inputId ?>" style="display: none;">
            <div class="progress-spinner-inner"></div>
            <div class="progress-spinner-inner"></div>
            <span class="sr-only">Caricamento...</span>
        </div>
    </div>

<?php
$attrMultiple = '';
if($multiple){
    $attrMultiple = 'multiple="true"';
}
?>

    <div class="bootstrap-select-wrapper" id="<?= $inputId ?>-wrapper">
        <label><?= $label ?><?= ($required) ? Html::tag('span', '*', ['class' => 'required-asterisk']) : '' ?></label>
        <select <?=$attrMultiple?> title="<?= $placeholder ?>" data-live-search="true" data-live-search-placeholder="Cerca" name="<?= $name ?>" id="<?= $inputId ?>">
<!--            <option value="" title="Scegli una opzione" data-content="Annulla <span class='reset-label'></span>"></option>-->

            <?php if ($enableGroup) { ?>
                <?php foreach ($items as $labelGroup => $itemsValue) {?>
                        <optgroup label="<?= $labelGroup ?>">
                            <?php foreach ($itemsValue as $key => $optionLabel) {
                                ?>
                                <?php $isSelected = (is_array($value)) ? in_array($key, $value) : ($value == $key); ?>
                                <option value="<?= $key ?>" <?= $isSelected ? 'selected' : '' ?>><?= $optionLabel ?></option>
                            <?php } ?>
                        </optgroup>
                <?php } ?>
            <?php } else { ?>
                <?php foreach ($items as $key => $optionLabel) { ?>
                    <?php
                    $isSelected = (is_array($value)) ? in_array($key, $value) : ($value == $key);?>
                    <option value="<?= $key ?>" <?= $isSelected ? 'selected' : '' ?>><?= $optionLabel ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>

<?php
if (isset($dataAction)) :
    $csrParam = Yii::$app->request->csrfParam;
    $csrValue = Yii::$app->request->getCsrfToken();

    $this->registerJs(
        <<<JS

let onSelectChange = function() {
    $('.dropdown-menu li.selected')
      .find('input[type="checkbox"]')
      .prop('checked', true);
    $('.dropdown-menu li:not(.selected)')
      .find('input[type="checkbox"]')
      .prop('checked', false)
  };

// Allows to rewrite options dynamically with an object in the form of
jQuery.fn.setOptionsToSelect = function(optionsData) {
    var selectElement = $(this).find('select');

    // Destroying selectElement
    $(selectElement)
      .off('changed.bs.select')
      .selectpicker('destroy')
      .empty();

    // Appending options according to the optionsData object
    optionsData.forEach(function(x) {
      $(selectElement).append(
        $('<option>', {
          value: x.value,
          text: x.text,
          selected: x.selected,
        })
      )
    });

    // Refreshing selectElement
    $(selectElement)
      .selectpicker('refresh')
      .on('changed.bs.select', onSelectChange);

    return this
  };


  var addOptionsByDataActiion = function(relatedValue = '') {
    var mydata = {
        related_value: relatedValue,
        {$csrParam}: '{$csrValue}',
    };

    $('#spinner-{$inputId}').toggle();
    $.post({
        url: '{$dataAction}',
        type: 'post',
        data: mydata,
        success: function (data, textStatus, jqXHR) {
            
            if(textStatus == 'success') {   
                var options = [];
                $.each(data, function(index, element) {
                    var myValue = element.value;
                    var label = element.label;
                    if(myValue == '{$value}') {
                        options.push({value: myValue, text: label, selected: true});
                    } else {
                        options.push({value: myValue, text: label, selected: false});
                    }
                });
                $('#{$inputId}-wrapper').setOptionsToSelect(options);
                enableSelectElement('{$inputId}');
            }
        
        },
        complete: function() {
          $('#spinner-{$inputId}').toggle();
        },
        error: function (data, textStatus, jqXHR) {
            // console.log(data);
            // console.log(textStatus);
            // console.log(jqXHR);

        }

    });

  };

  var relatedElement = $('#{$relatedId}');
  var refreshSelectElement = function(idToRefresh = '') {
      var selectElement =  $('#' + idToRefresh);
      $(selectElement)
        .selectpicker('refresh')
        .on('changed.bs.select', onSelectChange);
  };

  var disableSelectElement = function(elementId = '') {
      var selectElement =  $('#' + elementId);
      var wrapperElement = $('#' + elementId + '-wrapper'); 
      selectElement.prop('disabled', 'disabled');
      wrapperElement.addClass('disabled');
      refreshSelectElement(elementId);
  };

  var enableSelectElement = function(elementId = '') {
      var selectElement =  $('#' + elementId);
      var wrapperElement = $('#' + elementId + '-wrapper');
      $(selectElement).prop('disabled', false);
      wrapperElement.removeClass('disabled');
      refreshSelectElement(elementId);
  };


  if(relatedElement.length) {
    if(relatedElement.val().length) {
      addOptionsByDataActiion(relatedElement.val());
    } else {
      disableSelectElement('{$inputId}');
    }

    relatedElement.change(function() {
      addOptionsByDataActiion($( this ).val());

      });  
  } else {
    addOptionsByDataActiion();
  }


JS
        ,
        View::POS_READY
    );

endif;

?>