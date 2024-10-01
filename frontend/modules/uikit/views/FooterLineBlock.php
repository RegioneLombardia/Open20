<?php
/**
 * @var \luya\cms\base\PhpBlockView $this
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
    <hr style="border-top:<?= $this->varValue('lineWidth', '1px'); ?> 
    <?= $this->varValue('lineStyle', 'solid'); ?>" class="<?= $this->varValue('lineColor'); ?> <?= $this->varValue('lineSpace');?>" />
<?php endif; ?>