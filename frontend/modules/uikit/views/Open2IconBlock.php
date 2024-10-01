<?php
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;
use app\modules\uikit\BaseUikitBlock;

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






$currentAsset = BootstrapItaliaDesignAsset::register($this);
?>
<?php if ($canSeeBlock): ?>
    <?= DesignIcon::show($data['icon_name'], DesignIcon::ICON_MD, 'icon icon-'.$data['icon_color']. ' '.$data['icon_class'], $currentAsset);?>
<?php endif;
