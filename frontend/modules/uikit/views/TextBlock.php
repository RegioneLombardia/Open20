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


$cssClass = $this->cfgValue('cssClass');
?>
<?php if ($canSeeBlock): ?>
    <div class="<?= $cssClass ?>">
        <?= $data['content'] ?>
    </div>
<?php endif; ?>
