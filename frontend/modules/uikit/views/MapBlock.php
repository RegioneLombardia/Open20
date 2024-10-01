<?php
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
/**
 * @var $this \luya\cms\base\PhpBlockView
*/

$identifier = $this->extraValue('identifier', 0);

?>
<?php if ($canSeeBlock): ?>
    <?php if ($this->extraValue('embedUrl')):?>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe src="<?= $this->extraValue('embedUrl'); ?>" width="600" height="450" frameborder="0" style="border:0"></iframe>
    </div>
    <?php endif; ?>
<?php endif; 
