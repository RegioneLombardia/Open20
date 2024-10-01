<?php
/**
 * @var \luya\cms\base\PhpBlockView $this
 *
*/
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




?>
<?php if ($canSeeBlock): ?>
    <?php if (!empty($this->varValue('content'))): ?>
    <<?= $this->varValue('headingType', 'h2') . $this->cfgValue('cssClass', null, ' class="{{cssClass}}"'); ?>>
    <?= $this->varValue('content'); ?>
    <?= $this->varValue('headingType', 'h2', '</{{headingType}}>'); ?>
    <?php endif; ?>
<?php endif;
