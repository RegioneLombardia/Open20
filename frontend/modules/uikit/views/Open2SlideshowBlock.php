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
$attrs_slideshow_items = [];
$attrs_container = [];
// Slideshow
?>
<?php if ($canSeeBlock): ?>
    <div class="it-carousel-wrapper it-carousel-landscape-abstract-four-cols owl-carousel-design">
        <div class="it-carousel-all owl-carousel owl-loaded owl-drag">
            <div class="owl-stage-outer">
                <div class="owl-stage">
                    <?php foreach ($data['items'] as $i => $item) : ?>
                        <div class="owl-item ">
                            <?= $this->render('open2slideshow/item', compact('item', 'i', 'data')) ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
