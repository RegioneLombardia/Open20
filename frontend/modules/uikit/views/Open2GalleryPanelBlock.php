<?php

use app\modules\uikit\assets\FrontAsset;
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


/**
 * @var $this object
 * @var $data array
 */

$attrs = $data['attrs'];
$class = $data['class'];

$externalGridClass=$class.' it-grid-list-wrapper it-image-label-grid';
$internalGridClass='';
$columnClass='';
$extraExternalAttributes= '';


switch($data['gallery_type']){
    case 2: 
        $externalGridClass= $externalGridClass.' '.'it-masonry';
        $internalGridClass= $internalGridClass.'card-columns';
        $columnClass= $columnClass.'col-12';
        $extraExternalAttributes= 'data-bs-toggle="masonry"';
    break;
    default:
        $internalGridClass= $internalGridClass.'grid-row';
        $columnClass= $columnClass.'col-xs-12 col-sm-6 col-lg-4';

    break;
}

FrontAsset::register($this);

$c = 0;
?>
<?php if($canSeeBlock): ?>
    <div class="<?= $externalGridClass ?>" <?= $extraExternalAttributes?>>
        <div class="<?= $internalGridClass ?>">
            <?php foreach ($data['items'] as $item) : 
                $c++; 
                $item['contatore'] = $c; 
                $item['lightbox'] = $data['lightbox'];
            ?>
                <div class="<?= $columnClass ?>">
                    <?= $this->render('open2gallerypanel/item', compact('item', 'data')) ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif; ?>



