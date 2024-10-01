use backend\components\helpers\Html; 
    
/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */

<div id="listViewListContainer">       
        ############ PERSONALIZZARE A PIACIMENTO L'HTML ##############
        <div class="bk-listViewElement-info">
            <h2><?= $model ?></h2>            
            <?= $buttons ?>
            <div class="clear"></div>            
        </div>   
</div>