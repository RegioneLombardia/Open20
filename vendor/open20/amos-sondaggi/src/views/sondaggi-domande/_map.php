use open20\amos\core\helpers\Html;
    
/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */

<div id="listViewListContainer">
    <div class="bk-listViewElement">        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2><?= $model ?></h2>            
            <p>####### personalizzare l&#39;html a piacimento #######</p>
        </div>
        <div class="bk-elementActions">
            <a href="/<?= $this->context->module->id ?>/sondaggi/view?id=<?= $model->id ?>"><button class="btn btn-success">
                    <?= AmosSondaggi::t('amossondaggi', 'Visualizza') ?></button>
            </a>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>