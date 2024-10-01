<?php

use Yii;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\core\forms\ContextMenuWidget;
use yii\helpers\Url;

//$activeClass = ($isActive) ? 'active' : '';
//$cardBG=($activeClass)? '' : 'bg-card-preference-bg'; 
//$isActionDisabledClass = ($isActionDisabled) ? 'action-disabled' : '';
//$isEmptyCard = (isset($emptyCard));
//$textEmptyTitleMessage = ($isEmptyCard) ? $emptyCard['emptyTitleMessage'] : '';
//$textEmptyMessage = ($isEmptyCard) ? $emptyCard['emptyMessage'] : '';

//$idWithCounter = $baseId . $counter;
//$anchorId = $baseId . "anchor-id-" . $cardId;

$url = Url::to(['/documenti/documenti-ajax/file-explorer']);
?>

<div class="card-wrapper card-preference pb-3" id="<?= $model['id'] ?> "> 
    
    <div class="card rounded">
        <div class="card-body">
            <div class="categoryicon-top">            
                  <svg class="icon icon-folder" role="img" aria-label="Icona <?= $model['titolo'] ?>">  
                      <use xlink:href="<?= $iconBaseUrl . '/sprite/material-sprite.svg#folder' ?>"></use>
                  </svg>
                  <span class="h6"><?= Yii::t('amosdocumenti','#folder') ?></span>
            </div>

            <?= Html::a(Html::tag('h6',$model['titolo'],['class'=>'card-title mb-2 title-three-line']),'#',['class'=>'link-list-title','onclick'=>"

                    event.preventDefault();
                    var url = '$url'; 
                    var id = '{$model['id']}'; 
                    sendData(url,id);

                "]) 
            ?>

            <div class="ml-auto doc-actions d-flex">           
                <div>
                    <?php /*ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                        'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                        'modelValidatePermission' => 'DocumentValidate',
                        //'additionalButtons' => $additionalButtons,
                        'mainDivClasses' => 'manage-documents'
                    ]) */?>
                </div>
            </div>
        </div>
                    
    </div>
  
</div>