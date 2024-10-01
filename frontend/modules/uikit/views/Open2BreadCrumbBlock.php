<?php
use yii\helpers\Url;
use open20\design\assets\BootstrapItaliaDesignAsset;
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

$currentAsset = BootstrapItaliaDesignAsset::register($this);


?>
<?php if ($canSeeBlock): ?>
    <nav class="breadcrumb-container" aria-label="<?= Yii::t('amosplatform', 'Percorso di navigazione') ?>" >
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-decoration-none" href="<?= Yii::$app->menu->home->link ?>">
                    <svg class="icon icon-sm icon-primary align-top">
                        <use xlink:href=" <?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#home"></use>
                    </svg>
                </a>
                <span class="separator" aria-hidden="true">/</span>
            </li>
            <?php
            foreach ($items as $item) {
                $current = Yii::$app->menu->current;

            ?>
                <?php if(!($item->title=='Home' || $item->title=='home' || $item->title=='Homepage' || $item->title=='homepage')): ?>
                    <?php if($current!=$item->link): ?>
                        <li class="breadcrumb-item">
                            <a href="<?= $item->link ?>"><?= $item->title ?></a>
                            <span class="separator" aria-hidden="true">/</span>
                        </li>

                    <?php else: ?>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $item->title ?>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
            }

            ?>
        </ol>
    </nav>
<?php endif; ?>