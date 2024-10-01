<?php
use yii\helpers\Url;
use open20\design\assets\BootstrapItaliaDesignAsset;

$currentAsset = BootstrapItaliaDesignAsset::register($this);


?>
<nav class="breadcrumb-container" aria-label="<?= Yii::t('amosplatform', 'Percorso di navigazione') ?>" >
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a class="text-decoration-none" href="<?= Yii::$app->menu->home->link ?>">
                <svg class="icon icon-sm align-top">
                    <use xlink:href=" <?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#home"></use>
                </svg>
            </a>
            <span class="separator" aria-hidden="true">/</span>
        </li>
        <?php
        foreach ($items as $item) {
            $current = Yii::$app->menu->current;

        ?>
        
           
                <?php if($current->id!=$item->id): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= $item->link ?>"><?= $item->title ?></a>
                        <span class="separator" aria-hidden="true">/</span>
                    </li>

                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $item->title ?>
                    </li>
                <?php endif; ?>

            <?php
        }

         ?>
    </ol>
</nav>