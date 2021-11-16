<?php

$singleElementsWidthFullsize = 12 / $elementsForRow;

$singleElementsWidthMediumsize = 12 / ($elementsForRow-1);


?>


<div class="row variable-gutters users-list-container justify-content-start">

  <?=
    $this->render(
      '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
      [
        'imageOrganizzatore' => 'https://picsum.photos/1600/900?random=1',
        'organizzatore' => 'Fidagel',
        'removeLink' => true,
        'showCtaEsplora' => true,
        'additionalClass' => 'border-light border-bottom py-3',
        'singleElementsWidthFullsize' => 'col-xl-' . $singleElementsWidthFullsize . ' col-md-' . $singleElementsWidthMediumsize,
      ]
    );
  ?>

  <?=
    $this->render(
      '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
      [
        'imageOrganizzatore' => 'https://picsum.photos/1600/900?random=2',
        'organizzatore' => 'Pesce Azzurro Cefalù',
        'removeLink' => true,
        'showCtaEsplora' => true,
        'additionalClass' => 'border-light border-bottom py-3',
        'singleElementsWidthFullsize' => 'col-xl-' . $singleElementsWidthFullsize . ' col-md-' . $singleElementsWidthMediumsize,
      ]
    );
  ?>

  <?=
    $this->render(
      '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
      [
        'imageOrganizzatore' => 'https://picsum.photos/1600/900?random=3',
        'organizzatore' => 'Ittica Taddeo Lucia & Co',
        'removeLink' => true,
        'showCtaEsplora' => true,
        'additionalClass' => 'border-light border-bottom py-3',
        'singleElementsWidthFullsize' => 'col-xl-' . $singleElementsWidthFullsize . ' col-md-' . $singleElementsWidthMediumsize,
      ]
    );
  ?>

</div>