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
 * @var $this object
 * @var $data array
 */

$id    = $data['id'];
$imgClass = $data['class'][0];
$linkClass = $data['link_class'];
$linkTarget = $data['link_target'];

// Shadow
$imgClass = $data['image_box_shadow'] ? $imgClass . ' shadow' : $imgClass;


?>
<?php if($canSeeBlock): ?>
    <div class="image-block">
        <?php if ($data['link']) : ?>
            <a class="<?= $linkClass ?>" href="<?= $data['link'] ?>" target="<?= $linkTarget ?>" title="<?= $data['link_title'] ?>">
                <div>
                    <img src="<?= $data['image'] ?>" class="<?= $imgClass ?>" alt="<?= $data['image_alt'] ?>">
                </div>
                <?php if ($data['text']) : ?>
                    <span><?= $data['text'] ?></span>
                <?php endif; ?>
            </a>
        <?php else : ?>
            <div>
                <img class="img-class" src="/img_default.png" alt="immagine placeholder"/>
            </div>
            <?php if ($data['text']) : ?>
                <span><?= $data['text'] ?></span>
            <?php endif; ?>
        <?php endif ?>
    </div>
<?php endif; ?>