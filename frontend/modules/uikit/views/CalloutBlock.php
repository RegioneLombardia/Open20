<?php

use trk\uikit\Uikit;

/**
 * @var $this object
 * @var $data array
 */

 use app\modules\uikit\BaseUikitBlock;
 use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;


$currentAsset = BootstrapItaliaDesignAsset::register($this);

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch($visibility){
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;          
    break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest; 
		$n_class = $this->varValue('addclass');
		if($canSeeBlock && !empty($n_class)){
			$canSeeBlock = BaseUikitBlock::visivility($n_class);
		}
    break;
}
$class = $data['class'][0];
$icon = '';
switch($data['callout_style']){
    case 'success':
        $icon = 'check-circle-outline';
    break;
    case 'warning':
        $icon = 'help-circle-outline';
    break;
    case 'danger':
        $icon = 'close-circle-outline';
    break;
    default:
        $icon = 'information-outline';
        break;

}
?>
<?php if ($canSeeBlock): ?>
    <?php if((!empty($data['content']) || !empty($data['additional_content']))): ?>
        <div 
            class="callout 
            <?=$data['callout_style'] ? 'callout-'.$data['callout_style']  : 'callout-info';?>
            <?= $class ?>"
            role="callout"    
        >

            <?php if(!empty($data['content'])): ?>
                <div class="callout-title">
                    <?= DesignIcon::show($icon, DesignIcon::ICON_MD, 'icon ', $currentAsset)?>
                    <?= $data['content'] ?>
                </div>
            <?php endif; ?>
       
            <?php if(!empty($data['additional_content'])): ?>
                <p><?= $data['additional_content'] ?></p>
            <?php endif; ?>

         
        </div>
        
    <?php endif; ?>

<?php endif; ?>