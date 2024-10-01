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
 * @var $this object
 * @var $data array
 */
// Uikit::trace($data); return;
$id    = $data['id'];
$class = $data['class'][0];
$attrs = $data['attrs'];
$attrs_link = [];


//$data['content'] = Uikit::anchor($data['content'], $data['content'] );

?>
<?php if ($canSeeBlock): ?>
        <<?= $data['title_element']?> id="<?=$data['content']?>" class="anchor-offset <?= ($class)? $class : '' ?>">
                <?= $data['content'] ?>
        </<?= $data['title_element'] ?>>
<?php endif; ?>