<?php

use app\modules\uikit\Uikit;

/**
 * @var $this
 * @var $data
 */
$id = $data['id'];
$class = $data['class'];

$attrs_image = [];
$attrs_link = [];




if ($data['link_block']) :
    // Link
    if ($data['link']) {
        $attrs_link = [];
        $attrs_link['href'] = $data['link'];
        $attrs_link['target'] = $data['link_target'] ? '_blank' : '';
        $attrs_link['uk-scroll'] = strpos($data['link'], '#') === 0;
        $attrs_link['class'][] = 'item-link el-link';
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
    <a<?= Uikit::attrs($attrs_link) ?>>
    <?php endif
    ?>
    <div<?= Uikit::attrs(compact('id', 'class')) ?>>
        <?php
        // Image
        if ($data['image']):
            $attrs_image['class'][] = 'el-image';
//        $attrs_image['class'][] = $data['image_border'] ? "uk-border-{$data['image_border']}" : '';

            $image = Uikit::image($data['image'], $attrs_image);
            ?>
            <?= $image ?>
        <?php endif ?>
        <div class="el-content">
            <?php
            // category
            if ($data['category']):
                ?>
                <p class = "category"><span><?= $data['category'] ?></span></p>
            <?php endif ?>

            <?php
            if (!$data['link_block']) :

                // Link
                if ($data['link']) {
                    $attrs_link = [];
                    $attrs_link['href'] = $data['link'];
                    $attrs_link['target'] = $data['link_target'] ? '_blank' : '';
                    $attrs_link['uk-scroll'] = strpos($data['link'], '#') === 0;
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

                if ($data['link_text']) :
                    ?>
                    <p><a<?= Uikit::attrs($attrs_link) ?>><?= $data['link_text'] ?></a></p>
                    <h2 class="el-title"><?= $data['title'] ?></h2>
                <?php elseif (!$data['link_text']) : ?>
                    <a<?= Uikit::attrs($attrs_link) ?>><h2 class="el-title"><?= $data['title'] ?></h2></a>
                <?php endif ?>
            <?php else : ?>
                <h2 class="el-title"><?= $data['title'] ?></h2>
            <?php endif ?>
            <?php
            // subtitle
            if ($data['subtitle']):
                ?>
                <h3 class="el-subtitle"><?= $data['subtitle'] ?></h3>
            <?php endif ?>

            <?php
            // startdate
            if ($data['startdate']):
                ?>
                <div class="date-start"><?= Yii::$app->getFormatter()->asDate($data['startdate']); ?></div>
            <?php endif ?>
            <?php
            // enddate
            if ($data['enddate']):
                ?>
                <div class="date-end"><?= Yii::$app->getFormatter()->asDate($data['enddate']); ?></div>
            <?php endif ?>

        </div>
    </div>
    <?php if ($data['link_block']) :
        ?>
    </a>

<?php endif
?>

