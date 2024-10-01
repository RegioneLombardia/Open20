<?php

use app\modules\uikit\Uikit;

/**
 * @var $this
 * @var $data
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

$id    = $data['id'];
$class = $data['class'][0];
$attrs = $data['attrs'];

// Accordion
?>
<?php if ($canSeeBlock): ?>
    <div id="collapseDiv<?= $id ?>" class="collapse-div <?= $class ?>">
        <?php foreach ($data['items'] as $key=>$item) : ?>
            <?= $this->render('open2accordionplus/item', compact('item', 'data', 'id')) ?>
        <?php endforeach ?>
    </div>
<?php endif; ?>