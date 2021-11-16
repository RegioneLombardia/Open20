<?php

use app\modules\uikit\Uikit;

/**
 * @var $this
 * @var $data
 */
$id = $data['id'];
$class = $data['class'];
$modalID = 'mb-' . substr(uniqid(), -3);

$attrs_image = [];
$attrs_link = [];
$attrs_link_modal = [];

// Link
if ($data['linkModal']) {
    $attrs_link_modal['href'] = $data['linkModal'];
    $attrs_link_modal['target'] = $data['link_target'] ? '_blank' : '';
    $attrs_link_modal['uk-scroll'] = strpos($data['linkModal'], '#') === 0;
    $attrs_link_modal['class'][] = 'el-link';
    switch ($data['link_style']) {
        case '':
            break;
        case 'link-muted':
        case 'link-text':
            $attrs_link_modal['class'][] = "uk-{$data['link_style']}";
            break;
        default:
            $attrs_link_modal['class'][] = "uk-button uk-button-{$data['link_style']}";
            $attrs_link_modal['class'][] = $data['link_size'] ? "uk-button-{$data['link_size']}" : '';
    }
}

if ($data['link_block']) : ?>
<a class="" data-toggle="modal" data-target="<?= '#' . $modalID ?>" title="<?= $data['text_hover'] ?>">
    <?php endif ?>
    <div class="wrap-modal-box">

        <?php if ($data['image']): ?>
            <figure>
                <?php
                $attrs_image['class'][] = 'el-image';
                $image = Uikit::image($data['image'], $attrs_image);
                ?>
                <?= $image ?>
                <figcaption><p><?= $data['text'] ?></p></figcaption>
            </figure>
        <?php else: ?>
            <p><?= $data['text'] ?></p>
            <?php if ((!$data['link_block']) && ($data['link_text'])) : ?>
            <a class="uk-button uk-button-default btn-landing btn-landing-grey" data-toggle="modal"
               data-target="<?= '#' . $modalID ?>" title="<?= $data['text_hover'] ?>">
                <?= $data['link_text'] ?>
            </a>
            <?php endif ?>
        <?php endif ?>

        <div class="overlay">
            <div class="text"><?= $data['text_hover'] ?></div>
        </div>

    </div>
    <?php if ($data['link_block']) : ?>
</a>
<?php endif ?>
<!--MODALE-->
<div class="modal fade" id="<?= $modalID ?>" tabindex="-1" role="dialog"
     aria-labelledby="<?= strip_tags($data['text']) ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-size: cover;background-image: url(<?= $data['backgroundImg'] ?>)">
            <div class="modal-header">
                <h1 class="title"><?= $data['titleModal'] ?></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"> ×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                if ($data['imageModal']):
                    $attrs_image['class'][] = 'el-image';
                    $image = Uikit::image($data['imageModal'], $attrs_image);
                    ?>
                    <div class="wrap-img-modal"><?= $image ?></div>
                <?php endif ?>
                <div class="wrap-text-modal"><p><?= $data['textModal'] ?></p></div>
                <?php if($data['link_text_modal']) : ?>
                <div><a class="uk-button uk-button-default btn-landing btn-landing-grey" <?= Uikit::attrs($attrs_link_modal) ?>><?= $data['link_text_modal'] ?></a></div>
                <?php endif; ?>
            </div>
            <!--            <div class="modal-footer"></div>-->
        </div>
    </div>
</div>