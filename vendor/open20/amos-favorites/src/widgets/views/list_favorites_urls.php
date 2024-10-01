<?php
/**
 * @var $favorites
 * @var $listIcon
 * @var $listTitle
 * @var $listDescription
 */

use yii\helpers\Html;
use \open20\amos\favorites\AmosFavorites;

$labelCaricamento = AmosFavorites::t('amosfavorites', "Caricamento ...");
$this->registerJsVar('labelCaricamento', $labelCaricamento);
$js = <<<JS
$('#open-dropdown-favorites').click(function(){
    $('#favorite-list-id').html('<span class="label-caricamento">'+labelCaricamento+'</span>');
     // $('#container-favorite-list-id').hide();
    $.ajax({
        url: '/favorites/favorite/favorite-list-ajax',
        type: 'get',
        success: function (data) {
            $('#favorite-list-id').html(data);
            // $('#container-favorite-list-id').show();
        }
    });
});

JS;
$this->registerJs($js);
?>

<div class="container-list-favorites">
    <div class="dropdown dropright">
        <a id="open-dropdown-favorites" href="#" class="nav-item-link" role="button" id="dropdownMenuDropright"
           data-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false"
           title="<?= AmosFavorites::t('amosfavorites', $listDescription) ?>">
            <?php if ($listIcon) { ?>
                <span class="mdi mdi-<?= $listIcon ?> icon-sidebar"></span>
            <?php } ?>
            <span class="nav-label-link">
                <?= AmosFavorites::t('amosfavorites', $listTitle) ?>
            </span>
            <span class="mdi mdi-chevron-right icon-expand icon"></span>
        </a>
        <div id="container-favorite-list-id" class="dropdown-menu dark dropdown-sidebar"
             aria-labelledby="dropdownMenuDropright">
            <div id="favorite-list-id" class="link-list-wrapper">
                <span class="label-caricamento"><?= $labelCaricamento ?></span>
            </div>
        </div>
    </div>
</div>
