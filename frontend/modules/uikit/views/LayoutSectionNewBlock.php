<?php

use trk\uikit\Uikit;

/**
 * @var $this object
 * @var $data array
 */

$id = $data['id'];
$class = $data['class'][0];
$style = [];

$canSeeBlock = true;

$visibility = $data['visibility'];
switch($visibility){
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;          
    break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest;  
    break;
}

$permissions = $data['rbac_permissions'];
if (!empty($permissions)) {
    $canSeeBlock = false;
    foreach ($permissions as $permission) {
        if (Yii::$app->user->can($permission['rbac_permission'])) {
            $canSeeBlock = true;
            break;
        }
    }
}
?>
<?php if ($canSeeBlock): ?>
    <div class="uk-section <?=$class ? $class : '';?>"
        <?php if(!empty( $data['image'])): ?>
            style="
            <?=$data['image'] ? 'background-image:url('.$data['image'].');'  : '';?>
            <?=$data['image_size'] ? 'background-size:'.$data['image_size'].';'  : '';?>
            <?=$data['image_position'] ? 'background-position:'.$data['image_position'].';'  : '';?>
            <?=$data['image_repeat'] ? 'background-repeat: no-repeat;'  : 'background-repeat: no-repeat;';?>
            "
        <?php endif; ?>
    
    >
    <?php if(!$data['not_embed_container']): ?>
        <div class="uk-container">
    <?php endif; ?>
        <?= $data['content'] ?>
    <?php if(!$data['not_embed_container']): ?>
        </div>
    <?php endif; ?>


      
    </div>
<?php endif; 
