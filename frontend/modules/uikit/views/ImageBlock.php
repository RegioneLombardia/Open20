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


$id    = $data['id'];
$imgClass = $data['class'][0];
$linkClass = $data['link_class'];
$linkTarget = $data['link_target'];

// Shadow
$imgClass = $data['image_box_shadow'] ? $imgClass . ' shadow' : $imgClass;
$adatta = (empty($data['image_id_filter'])? 'w-100 ' : ' ');
$linkTitle="";
if($linkTarget=="_blank"){
    $linkTitle=Yii::t('amosplatform', 'Apri in nuova scheda la pagina').' '.$data['link'];
}else{
    $linkTitle=Yii::t('amosplatform', 'Vai alla pagina').' '.$data['link'];
}
?>
<?php if ($canSeeBlock): ?>
    <div class="image-block">
        <?php if ($data['link']) : ?>
            <a href="<?= $data['link'] ?>" target="<?= $linkTarget ?>" title="<?= $linkTitle ?>">
                <div>
                    <img src="<?= $data['image'] ?>" class="img-fluid <?= $adatta . $imgClass ?> <?= $data['rounded_image'] ?>" alt="<?= $data['image_alt'] ?>">
                </div>
                
            </a>
        <?php else : ?>
            <div>
                <img src="<?= $data['image'] ?>" class="img-fluid  <?= $adatta . $imgClass ?> <?= $data['rounded_image'] ?>" alt="<?= $data['image_alt'] ?>">
            </div>
        
        <?php endif ?>
    </div>
<?php endif; ?>