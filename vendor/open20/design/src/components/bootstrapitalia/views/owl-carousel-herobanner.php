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
$currentAsset = BootstrapItaliaDesignAsset::register($this);

?>

<div class="owl-carousel-design owl-carousel-herobanner-design it-carousel-wrapper">
   <div class="it-carousel-all owl-carousel">

      <!-- START SLIDE -->
      <div class="it-single-slide-wrapper">
         <div class="it-hero-wrapper it-dark it-overlay">
            <div class="img-responsive-wrapper">
               <div class="img-responsive">
                  <?php if (isset($imgUrl) && !empty($imgUrl)) : ?>
                     <div class="img-wrapper"><img src="<?= $imgUrl ?>" alt="<?= $imgName ?>"></div>
                  <?php endif; ?>
               </div>
            </div>
            <div class="container">
               <div class="row">
                  <div class="col-12">
                     <div class="it-hero-text-wrapper bg-dark">
                        <?php if (isset($category) && !empty($category)) : ?>
                           <span class="it-category"><?= $category ?></span>
                        <?php endif; ?>
                        <?php if (isset($title) && !empty($title)) : ?>
                           <h1><?= $title ?></h1>
                        <?php endif; ?>
                        <?php if (isset($subtitle) && !empty($subtitle)) : ?>
                           <p class="d-none d-lg-block"><?= $subtitle ?></p>
                        <?php endif; ?>
                        <?php if (isset($ctaUrl) && !is_empty($ctaUrl)) : ?>
                           <div class="it-btn-container">
                              <a class="btn btn-sm btn-outline-primary" href="<?= $ctaUrl ?>" title="<?= $ctaTitleLink ?>">
                                 <?= $ctaLabel ?>
                              </a>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- END SLIDE -->

   </div>
</div>