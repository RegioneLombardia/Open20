<?php
/**
 * @var $element \amos\sitemanagement\models\SiteManagementSliderElem
 */
?>


<div class="it-single-slide-wrapper">
    <figure>
        <img src="<?= (!empty($element->getFileImage())? $element->getFileImage()->getWebUrl() : "") ?>"  class="img-fluid" alt="immagine di galleria">
            <?php if (!empty($element->description)) { ?>
                <figcaption class="figure-caption mt-2"><?= $element->description ?></figcaption>
            <?php } ?>  
        </figure>
</div>

