<?php

/**
 * @param string $inputId
 * @param Model $model
 */
use yii\web\View;

$defaultLabelOn = (isset($labelOn)) ? $labelOn : 'Attivo';
$defaultLabelOff = (isset($labelOff)) ? $labelOff : 'Disattivo';
$defaultLabel = (isset($label)) ? $label : 'Toggle';
$idToggle = (isset($id)) ? $id : 'toggleDefautId';


//js aggiornamento label toggle al caricamento della pagina
$this->registerJs(<<<JS
$(document).ready(function(event) {
    var allToggle = $(".toggles");
    allToggle.each(function(){
        var input = $(this).find('input');
        if((input).prop("checked") == true){
            $(this).find('.jsSwitchLabel').text(' $defaultLabelOn ');
        } else {
            $(this).find('.jsSwitchLabel').text('$defaultLabelOff');
        }
    })
});
$(".toggles").click(function(event) {
    if($(this).find('input').prop("checked") == true) {
        $(this).find('.jsSwitchLabel').text('$defaultLabelOn');
    }else{
        if($(this).find('input').prop("checked") == false) {
            $(this).find('.jsSwitchLabel').text('$defaultLabelOff');
        }
    }
});

JS
, View::POS_READY);

?>



<div>
  <div class="row">
    <div class="col-12">
      <div class="form-check form-check-group">
        <div class="toggles">
          <label class="toggleLabel" for="<?=$idToggle?>">
            <?= $defaultLabel ?>
            <input type="checkbox" id="<?=$idToggle?>">
            <span class="lever"></span>
            <span class="d-block float-right jsSwitchLabel"></span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
