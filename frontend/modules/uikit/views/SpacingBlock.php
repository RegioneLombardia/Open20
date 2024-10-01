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


?>
<?php if ($canSeeBlock): ?>
    <p class="spacing-block">
        <?php for ($i=1;$i<=$this->varValue('spacing', 1);$i++): ?>
        <br><?php endfor; ?>
    </p>
<?php endif; ?>