<?php

use trk\uikit\Uikit;
use yii\helpers\Html;
/**
 * @var $this object
 * @var $data array
 * @var $item array
 */


?>

<div class="post-container post-container  card card-bg p-3 no-after mb-4">
    
    <?php if(!empty($post['image'])): ?>
        <div class="img-responsive-wrapper">
            <div class="img-responsive">
                <?= Html::img($post['image'],['alt'=>'']) ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="avatar-wrapper avatar-extra-text mb-1">
        
        <?php if(!empty($post['user_icon'])): ?>
            <div class="avatar size-lg">
                <?= Html::img($post['user_icon'],['alt'=>'']) ?>
            </div>
        <?php endif; ?>
        
        <div class="extra-text">
            <?= Html::a($post['user'],$post['link'],['target'=>'_blank']) ?>
            <p class="public_date"><?= date('d/m/Y H:i',strtotime($post['date'])) ?></p>
        </div>
       
    </div>
    
    <div class="description pb-4">
        <?= $post['value'] ?>
    </div>
    
</div>
