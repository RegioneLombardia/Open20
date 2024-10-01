<?php
/**
 * @var $favorites
 */
use yii\helpers\Html;
use open20\amos\favorites\AmosFavorites;

?>
<ul class="link-list">
    <?php if (count($favorites) > 0) { ?>
        <?php foreach ($favorites as $favorite) {
            $moduleIcon = "<strong>" . $favorite->getFavoriteType() . "</strong>";
            ?>
            <li>
                <?= Html::a("<span class='mdi mdi-bookmark icon-preferiti'></span><span class='label-text'>{$moduleIcon}{$favorite->title}</span>",
                    urldecode($favorite->url),
                    [
                        'class' => 'list-item',
                        'title' => $favorite->getFavoriteType() . $favorite->title,
                        'data-toggle' => 'tooltip',
                        'data-html' => 'true',
                        'target' => '_blank'
                    ]) ?>
            </li>
        <?php } ?>
    <?php } else { ?>
        <li>
            <span class='no-favorites label-text'><?= AmosFavorites::t('amosfavorites', 'Nessun segnalibro aggiunto') ?></span>
        </li>
    <?php } ?>
</ul>