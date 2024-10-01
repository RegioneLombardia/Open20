<?php

$jsToggleMenu = <<< JS

$('#toggleMenuSidebar').click(function(event) {

if($(this).prop("checked") == true) {
  console.log('vero');
$('#jsMenu').toggleClass('d-none');
$('#jsMain').toggleClass('col-lg-12');
$('.dev-tool-container').removeClass("mb-4 pb-3");
} else {
  console.log('falso');
  $('#jsMenu').toggleClass('d-none');
  $('#jsMain').toggleClass('col-lg-12');
  $('.dev-tool-container').addClass("mb-4 pb-3");
}

});

JS;

$this->registerJs($jsToggleMenu);

?>

<?php

$jsToggleContainer = <<< JS

$('#toggleContainer').click(function(event) {
$('#mainContent').toggleClass('container');

});

JS;

$this->registerJs($jsToggleContainer);

?>

<div class="dev-tool-container container-fluid fixed-bottom design-theme-bg-light mb-4 mb-lg-0 pb-3 pb-lg-0">
  <div class="row mt-2">
    <div class="col-12 col-lg-4 pl-lg-4">
      <?=
        $this->render(
          '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-toggle',
          [
            'label' => 'Nascondi menu',
            'id' => 'toggleMenuSidebar'
          ]
        );
      ?>
    </div>
    <div class="col-12 col-lg-4 pl-lg-4">
      <?=
        $this->render(
          '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-toggle',
          [
            'label' => 'Abilita container',
            'id' => 'toggleContainer'
          ]
        );
      ?>
    </div>
  </div>
</div>