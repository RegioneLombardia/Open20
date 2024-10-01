<?php
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */

//pr($model, '$model');exit;

?>


<div class="listview-container news-item grid-item nop">
    <div class="post col-xs-12">
        <div class="post-content col-xs-12 nop">
            <div class="post-title col-xs-10">
                <?= Html::tag('h2', $model["name"]) ?>
            </div>
            <div class="post-image col-xs-12">
                <?php 
                $imgUrl = $model["badgeurl"];
                $contentImage = Html::img($imgUrl, [
                    'class' => 'img-responsive',
                    'alt' => $model["description"]
                ]);
                ?>
                <?=$contentImage ?>
            </div>
            <div class="post-text col-xs-12">
                <p>
                    <?= $model["description"] ?>
                   
                </p>
            </div>
          
        </div>
    </div>
</div>