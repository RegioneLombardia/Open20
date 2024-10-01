<?php

use yii\helpers\Html;
use open20\elasticsearch\Module;
use open20\amos\core\icons\AmosIcons;

?>

<div class="row">

    <?php

    foreach ($results as $result) {
        $classActive = '';
        $titleLink = Module::t('amoselasticsearch', "Filtra cronologia per testo");
        $urlLink = ['/elasticsearch/elasticsearch-history/index' , 'ElasticsearchHistorySearch[search_text]' => $result['search_text']];
        if($searchText == $result['search_text']) {
            $classActive =  'btn-active-filter';
            $urlLink = ['/elasticsearch/elasticsearch-history/index'];
            $titleLink = Module::t('amoselasticsearch', "Annulla filtro cronologia per testo");

        }?>
        <div class="col-xs-12 single-top-researched p-t-5">
            <p class="p-r-15">
                <?=
                Module::t('amoselasticsearch', "Ricercata") . ' ' .
                    Html::tag('span', $result['n'], ['class' => 'label label-secondary']) . ' ' .
                    Module::t('amoselasticsearch', "volte il testo") . ' ' .
                    Html::tag('strong', $result['search_text'], ['class' => ''])
                ?>
            </p>
            <p>
                <?= Html::a(
                    //AmosIcons::show('filter-list'),
                    Html::tag('span', '', ['class' => 'mdi mdi-filter-outline']),
                    $urlLink,
                    [
                        'title' => $titleLink . ' "' . $result['search_text'] . '"',
                        'class' => 'btn btn-outline-secondary'.' '.$classActive
                    ]
                ) ?>
            </p>
        </div>
    <?php } ?>

</div>