<?php
use app\modules\uikit\Module;
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




$imgClass = "it-hero-wrapper";

$overlay = $data['overlay'] ? $imgClass . ' it-dark it-overlay' : $imgClass;

$smallSize = $data['smallSize'] ? ' it-hero-small-size' : '';



?>
<?php if ($canSeeBlock): ?>
  <div class="<?= $data['class'][0] ?> <?= $overlay ?> <?= $smallSize ?>">
    <!-- - img-->
    <div class="img-responsive-wrapper">
      <div class="img-responsive">
          <div class="img-wrapper">
              <?php if($data['image']): ?>
                <img src="<?= $data['image'] ?>" alt="<?= Module::t('Immagine del banner fotografico') ?>">
              <?php endif; ?>
          </div>
      </div>
    </div>
    <!-- - texts-->
    <div class="container">
      <div class="row">
          <div class="col-12">
            <div class="it-hero-text-wrapper bg-dark">
                <?php if($data['label_category']): ?>
                  <span class="it-category"><?= $data['label_category'] ?></span>
                <?php endif; ?>
                <?php if($data['title']): ?>
                  <<?= $data['title_element'] ?>><?= $data['title'] ?></<?= $data['title_element']?>>
                <?php endif; ?>
                <?php if($data['text']): ?>
                  <p class="d-none d-lg-block"><?= $data['text'] ?></p>
                <?php endif; ?>

            </div>
          </div>
      </div>
    </div>
  </div>
<?php endif; ?>