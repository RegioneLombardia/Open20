<?php

use app\modules\uikit\Uikit;

/**
 * @var $this
 * @var $data
 */

$id    = $data['id'];
$class = $data['class'];
$attrs = $data['attrs'];

// Accordion
$attrs['uk-accordion'] = Uikit::json(Uikit::pickBool($data, ['multiple', 'collapsible']));
?>
<div<?= Uikit::attrs(compact('id', 'class'), $attrs) ?>>
    <?php foreach ($data['items'] as $item) : ?>
        <div class="el-item">
            <?php
            if ($item['accordion_image']) {

                $attrs_image['class'][] = 'el-image';

                if (Uikit::isImage($item['image']) == 'gif') {
                    $attrs_image['uk-gif'] = true;
                }
                $item['accordion_image'] = Uikit::image($item['accordion_image'], $attrs_image);
            }
            ?>
            <a class="el-title uk-accordion-title <?= $item['accordion_image_class'] ?>" href="#">
            <?= $item['accordion_image'] ?>
            <p><?= $item['title'] ?></p></a>
            <div class="uk-accordion-content">
                <?= $this->render('accordionplus/item', compact('item', 'data')) ?>
            </div>
        </div>
    <?php endforeach ?>
</div>
