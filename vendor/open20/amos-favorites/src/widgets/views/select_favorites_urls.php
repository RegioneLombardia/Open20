<?php
/**
 * @var $content_id
 * @var $url
 * @var $title
 * @var $classname
 * @var $module
 * @var $controller
 * @var $is_selected
 * @var $idWidget
 */

use yii\helpers\Html;
use \open20\amos\favorites\AmosFavorites;

$js = <<<JS

    $(document).on('click','#select-favorite-url-$idWidget', function(e){
        e.preventDefault();
        var url = $('#favorite-url-id-$idWidget').val();
        var title = $('#favorite-title-id-$idWidget').val();
        var classname = $('#favorite-classname-id-$idWidget').val();
        var content_id = $('#favorite-content_id-id-$idWidget').val();
        var module = $('#favorite-module-id-$idWidget').val();
        var controller = $('#favorite-controller-id-$idWidget').val();
          $.ajax({
                    url: '/favorites/favorite/select-unselect-favorite-url-ajax',
                    type: 'post',
                    data: {
                         favoriteUrl: url,
                         favoriteTitle: title, 
                         favoriteModule: module, 
                         favoriteController: controller, 
                         favoriteClassname: classname, 
                         favoriteContentId: content_id
                     },
                    success: function (data) {
                        // console.log(data);
                        if(data['action'] == 'selected'){
                            $('#select-favorite-url-$idWidget span').attr('class', 'mdi mdi-bookmark');
                            $('#select-favorite-url-$idWidget span').attr('title', 'Rimuovi dai segnalibri');
                            $('#select-favorite-url-$idWidget').attr('data-original-title', 'Rimuovi dai segnalibri');
                            $('#select-favorite-url-$idWidget').addClass('added-to-favourites');
                        }else{
                            $('#select-favorite-url-$idWidget span').attr('class', 'mdi mdi-bookmark-outline');
                            $('#select-favorite-url-$idWidget').attr('title', 'Aggiungi ai segnalibri');
                            $('#select-favorite-url-$idWidget').removeClass('added-to-favourites');
                        }
                    
                    }
                });
    });
JS;
$this->registerJs($js);
$this->registerCss("
.favorite-relative{
}
");

$cmsClass = 'favorite-from-backend';
if($widget->positionRelative){
    $cmsClass = 'favorite-relative';
}else if($widget->isCms){
    $cmsClass = 'favorite-from-cms';
}
?>

<div id="widget-favorite-<?=$idWidget?>" class="container-favourites <?= $cmsClass ?>">
    <?php
    $classSelected = '';
    $icon = 'mdi mdi-bookmark-outline';
    $label = AmosFavorites::t('amosfavorites', "Aggiungi ai segnalibri");
    if ($is_selected) {
        $classSelected = ' added-to-favourites';
        $icon = 'mdi mdi-bookmark';
        $label = AmosFavorites::t('amosfavorites', "Rimuovi dai segnalibri");
    }


    $iconFavorites = '<span title="'.$label.'" class="' . $icon . '"></span>';
    echo Html::a($iconFavorites, '#', [
        'id' => 'select-favorite-url-'.$idWidget,
        'title' => $label,
        'class' => 'favourite-btn' . $classSelected,
        'data-toggle' => 'tooltip',
        'data-placement' => 'bottom'
    ])
    ?>
    <div class="favorites-inputs">
        <?= Html::hiddenInput('favoriteUrl', $url, ['id' => 'favorite-url-id-'.$idWidget]) ?>
        <?= Html::hiddenInput('favoriteTitle', $title, ['id' => 'favorite-title-id-'.$idWidget]) ?>
        <?= Html::hiddenInput('favoriteModule', $module, ['id' => 'favorite-module-id-'.$idWidget]) ?>
        <?= Html::hiddenInput('favoriteController', $controller, ['id' => 'favorite-controller-id-'.$idWidget]) ?>
        <?= Html::hiddenInput('favoriteClassname', $classname, ['id' => 'favorite-classname-id-'.$idWidget]) ?>
        <?= Html::hiddenInput('favoriteContentId', $content_id, ['id' => 'favorite-content_id-id-'.$idWidget]) ?>
    </div>
</div>

