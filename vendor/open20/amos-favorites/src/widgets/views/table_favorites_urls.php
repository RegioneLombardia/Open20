<?php

use open20\amos\favorites\AmosFavorites;
use yii\helpers\Html;

/**
 * @var $favorites
 */

$js = <<<JS
    $('.delete-favorite-confirm').click(function(){
        var link = $(this);
        var id = $(this).attr('data-key');
        $('#favoriteModal-'+id).modal('hide');
        $.ajax({
                    url: '/favorites/favorite/select-unselect-favorite-url-ajax',
                    type: 'post',
                    data: {
                         favoriteId: id
                    },
                    success: function (data) {
                        if(data['action'] == 'unselected'){
                            var row = $(link).parents('.itemRowDashboardTab');
                            var tabFavorites = row.parent();
                            
                            $(row).fadeOut("normal", function() {
                                $(this).remove();
                            });
                            
                            var numItems = $('.delete-favorite-confirm').length;                       
                            if (numItems <= 1) {
                                tabFavorites.html('<small class="no-favorites">{$noFavouriteLabel}</small>').fadeIn(1000);
                            }
                            
                            $('.modal-backdrop').hide();
                        }
                    
                    }
                });
    });
JS;

$this->registerJs($js);
?>

<?php
if (count($favorites) > 0) {
    foreach ($favorites as $favorite) { ?>
        <div class="row itemRowDashboardTab">
            <div class="col">
                <span class="title">
                    <?php
                    $type = $favorite->getFavoriteType(false);
                    if (!empty($type)) { ?>
                        <span class="badge badge-secondary"><?= $type ?></span>
                    <?php } ?>
                    <a class="" href="<?= $favorite->url ?>" title="<?= AmosFavorites::t('amosfavorites', 'Visualizza') . ' ' . $favorite->title . ' ' . AmosFavorites::t('amosfavorites', '[Apre in nuova finestra]') ?>" target="_blank"><?= $favorite->title ?></a>
                </span>
            </div>
            <div class="col-2 text-right">
                <?php
                $icon = '<span class="it-close sr-only">' . AmosFavorites::t('amosfavorites', "Rimuovi dai segnalibri") . '</span><span class="text-danger mdi mdi-close-circle-outline"></span>' ?>
                <?= Html::button($icon, [
                    'data-toggle' => 'modal',
                    'data-target' => '#favoriteModal-' . $favorite->id,
                    'class' => 'btn btn-icon p-0',
                    'title' => AmosFavorites::t('amosfavorites', "Rimuovi dai segnalibri")
                ]) ?>

                <!-- Modal -->
                <div class="modal fade" id="favoriteModal-<?= $favorite->id ?>" tabindex="-1" role="dialog" aria-labelledby="favoriteModalLabel-<?= $favorite->id ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="favoriteModalLabel-<?= $favorite->id ?>">
                                    <?= AmosFavorites::t('amosfavorites', "Rimuovi segnalibro") ?>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-left">
                                <?= AmosFavorites::t('amosfavorites', 'Sei sicuro di voler rimuovere "{x}" dai segnalibri?', [
                                        'x' => $favorite->title
                                ]); ?>
                            </div>
                            <div class="modal-footer mt-3">
                                <?= Html::button(AmosFavorites::t('amosfavorites', "Annulla"), [
                                    'data-dismiss' => 'modal',
                                    'class' => 'btn btn-sm btn-secondary',
                                    'title' => AmosFavorites::t('amosfavorites', "Annulla")
                                ]); ?>
                                <?= Html::button(AmosFavorites::t('amosfavorites', "Rimuovi"), [
                                    'id' => 'delete-favorite-confirm-' . $favorite->id,
                                    'class' => 'delete-favorite-confirm btn btn-sm btn-primary',
                                    'data-key' => $favorite->id,
                                    'title' => AmosFavorites::t('amosfavorites', "Rimuovi dai segnalibri")
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <small class='no-favorites'><?= $noFavouriteLabel ?></small>
<?php } ?>