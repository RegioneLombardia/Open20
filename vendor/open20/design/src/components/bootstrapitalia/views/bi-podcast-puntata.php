<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
?>

<div class="podcast-item-container my-3 col-12 row">
    <?php if(isset($podcastCover)) : ?>
        <div class="col-md-3 mb-3 mb-md-0">
            <a href="<?= $url ?>" title="Vai alla puntata">
                <img class="img-fluid" alt="immagine documento" src="<?= $podcastCover ?>" >
            </a>
        </div>
    <?php endif; ?>
    <div class="<?= (isset($podcastCover)) ? 'col-md-9' : 'col-md-12' ?> d-flex flex-column"> 
        <a href="<?= $url ?>" title="Vai alla puntata" class="title-one-line  link-list-title  mr-3 mb-0"><h3 class="h4"><?= $podcastName ?></h3></a>
        <p class="lead  mb-2"><?php if($episodeDuration){ ?> (<?=$episodeDuration ?>') - <?php } ?> <?= $episodeDate?></p>  
        <p class="mb-0 title-two-line-desktop"><?= $podcastDescription ?></p>
    </div>
</div>