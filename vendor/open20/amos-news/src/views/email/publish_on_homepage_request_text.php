<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\email
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;

?>

<?= AmosNews::t('amosnews', '#hp_dear_user', [
    'whocan_name' => $whocan_name,
    'community' => $community
]); ?>

<?= AmosNews::t('amosnews', '#hp_row_1', [
    'user_request' => $user_request,
]) ?>

<div>
    <?= AmosNews::t('amosnews', '#hp_row_2_label') ?>
    <span style="font-size: 1.1rem;line-height: 1.25;font-weight: 600;">
        <?= AmosNews::t('amosnews', '#hp_row_2', [
            'news_title' => $news_title
        ]) ?>
    </span>
</div>


<?= AmosNews::t('amosnews', '#hp_row_3',[
    'news_categoria' => $news_categoria
]) ?>

<?= AmosNews::t('amosnews', '#hp_row_4', [
    'news_descr' => strip_tags($news_descr)
]) ?>

<?= AmosNews::t('amosnews', '#hp_row_5', [
    'tags' => $tags
]) ?>

<?= AmosNews::t('amosnews', '#hp_row_6') ?><a href ="<?= $news_url ?>"><?= $news_url ?></a>