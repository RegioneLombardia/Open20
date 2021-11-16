use open20\amos\core\helpers\Html;
/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */
 
<div id="listViewListContainer">
    <div class="row">
        <div id="bk-listViewElementSondaggi-domande" class="col-xs-12 bk-listViewElementSondaggi-domande">
            <div class="col-xs-8 col-md-8">
                <h2><?= $model ?></h2>              
                <div class="bk-infoElementList">
                    <p>################# PERSONALIZZARE A PIACIMENTO L&#39;HTML ################</p>                    
                </div>
                <div class="col-xs-4 col-md-4">
                    <?= $buttons ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="col-xs-4 col-md-4">
                <p>### PERSONALIZZA ###</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>