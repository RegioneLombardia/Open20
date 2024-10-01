<?php

//use app\modules\uikit\assets\FrontAsset;
use app\assets\ThumbSliderAsset;
use yii\web\View;
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



ThumbSliderAsset::register($this);


/**
 * @var $this object
 * @var $data array
 */

$attrs = $data['attrs'];

//gallery with thumb
if (empty($data['hidethumb'])) {
    $classGallery = 'galleryThumb';
}else {
    $classGallery = 'gallerySlider isGalleryClass';
}

//gallery NO thumb
if (!empty($data['class'])) {
    $class = 'wrap-lightslider '. $data['class'] . $classGallery;
}else {
    $class = 'wrap-lightslider '. $classGallery;
}

$i = 0;
$limit = 3;
?>

<?php if ($canSeeBlock): ?>
    <div class="thumbslider-container">
        <div <?= Uikit::attrs(compact('class'), $attrs) ?>>
            <ul id="lightSlider">
                <?php foreach ($data['items'] as $item) : ?>
                    <?= $this->render('thumbslider/item', compact('item', 'data')) ?>
                <?php 
                if(++$i >= $limit){
                    break;
                }
                endforeach ?>
            </ul>

                <div class="lightSliderAction">
                    <span class="am am-play play-btn" ></span>
                    <span class="am am-pause pause-btn"></span>
                </div>
        </div>
    </div>
<?php endif; ?>