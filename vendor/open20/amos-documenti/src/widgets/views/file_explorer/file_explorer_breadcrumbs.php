<?php

use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;

?>

<div class="breadcrumb-widget">
    <ul>
        <?php foreach($breadcrumbs as $breadcrumb): 
            $titolo = substr($breadcrumb['titolo'], 0, 15);

            if (strlen($titolo) >= 15)
                $titolo .= '...';

        ?>
            <li>
                <?= isset($breadcrumb['is_home']) ? '<span class="mdi mdi-home" />' : ''?>
                <?= Html::a($titolo,['/documenti/documenti-ajax/file-explorer'],['data-toggle'=>'tooltip','title'=>$breadcrumb['titolo'],'onclick'=>'
                
                        event.preventDefault();
                        var form = $(this).closest(".file-explorer-container").find("form")[0];
                        var formData = new FormData(form);
                        var url = $(this).attr("href");
                        var id = '.$breadcrumb["id"].';

                        sendData(url,id,formData);
                      
                    ']) 
                ?>
               
            </li>
        <?php endforeach; ?>
    </ul>
</div>