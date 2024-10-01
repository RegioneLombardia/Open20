<?php
/**
 * @var $this \luya\cms\base\PhpBlockView
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





$breakpoint=$this->varValue('breakpoint', 'md');
?>
<?php if ($canSeeBlock): ?>
<div class="row<?= $this->cfgValue('rowDivClass', null, ' {{rowDivClass}}');?>">
    <div class="<?= $this->extraValue('leftWidth', null, 'col-'.$breakpoint.'-{{leftWidth}}') . $this->cfgValue('leftColumnClasses', null, ' {{leftColumnClasses}}'); ?>">
        <?= $this->placeholderValue('left'); ?>
    </div>
    <div class="<?= $this->extraValue('rightWidth', null, 'col-'.$breakpoint.'-{{rightWidth}}') . $this->cfgValue('rightColumnClasses', null, ' {{rightColumnClasses}}'); ?>">
        <?= $this->placeholderValue('right'); ?>
    </div>
</div>
<?php endif; 