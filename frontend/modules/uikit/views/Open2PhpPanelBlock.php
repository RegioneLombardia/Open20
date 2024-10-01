<?php

use app\modules\uikit\Uikit;
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
 * @var $this
 * @var $data
 */

$id    = $data['id'];
if(!empty($data['class'][0])) {
    $class = $data['class'][0];
}
else {
    $class = '';
}

?>
<?php if ($canSeeBlock): ?>
    <div id="<?php echo $id ?>" class="<?php echo $class ?>">
        <?= eval($data['content']); ?>
    </div>
<?php endif; ?>