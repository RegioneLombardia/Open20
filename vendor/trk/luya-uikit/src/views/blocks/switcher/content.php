<?php

use trk\uikit\Uikit;

/**
 * @var $this object
 * @var $data array
 * @var $item array
 */

$attrs_title = [];
$attrs_content = [];
$attrs_meta = [];
$attrs_link = [];

// Title
$attrs_title['class'][] = 'el-title uk-margin';
$attrs_title['class'][] = $data['title_style'] ? "uk-{$data['title_style']}" : '';
$attrs_title['class'][] = $data['title_decoration'] ? "uk-heading-{$data['title_decoration']}" : '';
$attrs_title['class'][] = $data['title_color'] && $data['title_color'] != 'background' ? "uk-text-{$data['title_color']}" : '';
// Meta
$attrs_meta['class'][] = 'el-meta uk-margin';
switch ($data['meta_style']) {
    case '':
        break;
    case 'meta':
    case 'muted':
        $attrs_meta['class'][] = "uk-text-{$data['meta_style']}";
        break;
    default:
        $attrs_meta['class'][] = "uk-{$data['meta_style']}";
}
if ($data['meta_align'] == 'top') {
    $attrs_meta['class'][] = 'uk-margin-remove-adjacent';
    $attrs_meta['class'][] = $data['meta_margin'] ? "uk-margin-{$data['meta_margin']}-bottom" : '';
}
if ($item['meta'] && $data['meta_align'] == 'bottom') {
    $attrs_title['class'][] = 'uk-margin-remove-adjacent';
    $attrs_title['class'][] = $data['meta_margin'] ? "uk-margin-{$data['meta_margin']}-bottom" : '';
}
// Content
$attrs_content['class'][] = 'el-content uk-margin';
$attrs_content['class'][] = $data['content_style'] ? "uk-text-{$data['content_style']}" : '';
// Link
if ($item['link']) {
    $attrs_link['href'] = $item['link'];
    $attrs_link['target'] = $data['link_target'] ? '_blank' : '';
    $attrs_link['uk-scroll'] = strpos($item['link'], '#') === 0;
    $attrs_link['class'][] = 'el-link';

    switch ($data['link_style']) {
        case '':
            break;
        case 'link-muted':
        case 'link-text':
            $attrs_link['class'][] = "uk-{$data['link_style']}";
            break;
        default:
            $attrs_link['class'][] = "uk-button uk-button-{$data['link_style']}";
            $attrs_link['class'][] = $data['link_size'] ? "uk-button-{$data['link_size']}" : '';
    }
}
?>
<?php if ($item['meta'] && $data['meta_align'] == 'top') : ?>
    <div<?= Uikit::attrs($attrs_meta) ?>><?= $item['meta'] ?></div>
<?php endif ?>
<?php if ($item['title']) : ?>
    <<?= $data['title_element'] . Uikit::attrs($attrs_title) ?>>
        <?php if ($data['title_color'] == 'background') : ?>
        <span class="uk-text-background"><?= $item['title'] ?></span>
        <?php elseif ($data['title_decoration'] == 'line') : ?>
        <span><?= $item['title'] ?></span>
        <?php else : ?>
        <?= $item['title'] ?>
        <?php endif ?>
    </<?= $data['title_element'] ?>>
<?php endif ?>
<?php if ($item['meta'] && $data['meta_align'] == 'bottom') : ?>
    <div<?= Uikit::attrs($attrs_meta) ?>><?= $item['meta'] ?></div>
<?php endif ?>
<?php if ($item['content']) : ?>
    <div<?= Uikit::attrs($attrs_content) ?>><?= $item['content'] ?></div>
<?php endif ?>
<?php if ($item['link'] && ($item['link_text'] || $data['link_text'])) : ?>
    <p><a<?= Uikit::attrs($attrs_link) ?>><?= $item['link_text'] ? $item['link_text'] : $data['link_text'] ?></a></p>
<?php endif ?>