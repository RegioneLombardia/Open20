<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\widgets\graphics\views
 * @category   CategoryName
 *
 * @var $sondaggio \open20\amos\sondaggi\models\Sondaggi
 */

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;

ModuleSondaggiAsset::register($this);
$js = <<<JS
    function loadResult(id, pagina){
         $.ajax({
                   url: '/sondaggi/sondaggi/risultati-live',
                   type: 'get',
                   data: {
                            'id' :id,
                            'idPagina' : pagina
                         },
                   success: function (data) {
                     $('#sondaggio_html_'+id).html(data)
                   }
              });
    }
    
        function loadTemplate(id){
         $.ajax({
                   url: '/sondaggi/frontend/compila-senza-layout',
                   type: 'get',
                   data: {
                            'id' :id,
                            'isSondaggioLive': 1
                         },
                   success: function (data) {
                      
                     $('#sondaggio_html_'+id).html(data)
                   }
              });
    }


JS;
$this->registerJs($js);
?>
<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
        <?=
        $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'isSetScope' => true,
                'modelLabel' => 'sondaggi',
                'titleSection' => AmosSondaggi::t('amossondaggi', 'Sondaggio live'),
            ]
        );
        ?>
    </div>
    <?php
    foreach ($sondaggi as $sondaggio) {
        $compilato = $sondaggio->sondaggioLiveVoted();

        $modelLabel = (!empty($modelLabel) ? $modelLabel : '');
        $sondaggioId = $sondaggio->id;

        if (!empty($compilato)) {
            $pagina = $sondaggio->getSondaggiDomandePagines()->one()->id;
        } else {
            $js = <<<JS
           loadTemplate($sondaggioId);
JS;
        }
        $this->registerJs($js);

        ?>


        <?php //if ($listaModels) {
        ?>
        <div class="list-view">
            <div class="sondaggio-live-wrapper">
            <div class="linguetta-laterale">
            <span class="mdi mdi-frequently-asked-questions"></span>
            </div>
                <div class="sondaggio-body">
                    <div class="row">
                        <?php if ($sondaggio->how_show_live == 2) {
                            $pagina = $sondaggio->getSondaggiDomandePagines()->one()->id; ?>
                            <div id="sondaggio_html_<?= $sondaggioId ?>" class="col-md-6">
                            </div>
                            <div id="sondaggio_compilato_<?= $sondaggioId ?>" class="col-md-6">
                                <?php
                                echo \open20\amos\sondaggi\models\Sondaggi::renderSondaggiLive($sondaggio->id, $pagina, true);
                                ?>
                            </div>
                        <?php } else { ?>
                            <div id="sondaggio_html_<?= $sondaggioId ?>" class="col-sm-12">
                                <?php
                                if (!empty($compilato)) {
                                    echo \open20\amos\sondaggi\models\Sondaggi::renderSondaggiLive($sondaggio->id, $pagina);
                                } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
</div>

