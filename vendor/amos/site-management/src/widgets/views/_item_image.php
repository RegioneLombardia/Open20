<?php
/**
 * @var $element \amos\sitemanagement\models\SiteManagementSliderElem
 */
?>
<div class="item item-image <?= $active ? "active" : "" ?>">
    <div class="item-overlay"></div>
    <img src="<?= (!empty($element->fileImage) ? $element->fileImage->getWebUrl() : "") ?>" alt="" style="width:100%;">
    <div class="carousel-caption <?= !empty($element->text_position) ? 'carousel-caption-grid-3 ' . $element->getTexPositionClasses() : '' ?>">
        <?php if (!empty($element->link)) { ?>
            <?php if (!empty($element->title)) { ?>
                <h1 class="title"><a title="<?= $element->title ?>" href="<?= $element->link ?>"><?= $element->title ?></a></h1>
            <?php } ?>
            <?php if (!empty($element->description)) { ?>
                <p class="description"><a href="<?= $element->link ?>"><?= $element->description ?></a></p>
            <?php } ?>

        <?php } else { ?>
            <?php if (!empty($element->title)) { ?>
                <h1 title="<?= $element->title ?>" class="title"><?= $element->title ?></h1>
            <?php } ?>
            <?php if (!empty($element->description)) { ?>
                <p class="description"><?= $element->description ?></p>
            <?php } ?>

        <?php } ?>
    </div>
</div>