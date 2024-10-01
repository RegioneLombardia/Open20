<?php

use trk\uikit\Uikit;
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

$id    = $data['id'];
$class = $data['class'];
$attrs = $data['attrs'];

// Style
$class[] = $data['list_style'] ? "uk-list uk-list-{$data['list_style']}" : 'uk-list';
// Size
$class[] = $data['list_size'] ? 'uk-list-large' : '';
?>
<?php if ($canSeeBlock): ?>
    <ul<?=Uikit::attrs(compact('id', 'class'), $attrs) ?>>
        <?php foreach ($data["items"] as $item) : ?>
            <li class="el-item"><?= $this->render('description-list/item', compact('item', 'data')) ?></li>
        <?php endforeach ?>
    </ul>
<?php endif; ?>