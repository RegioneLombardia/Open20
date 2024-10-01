<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\design\Module;

?>
<?php if (isset(Yii::$app->params['socialConfigurations']) && is_array(Yii::$app->params['socialConfigurations']) && !empty(Yii::$app->params['socialConfigurations'])) : ?>
  <?php
  $socialConfigurations = Yii::$app->params['socialConfigurations'];
  ?>

  <div class="d-flex link-list-wrapper footer-social">
    <ul class="footer-list link-list clearfix">
      <?php foreach ($socialConfigurations as $k => $socialConf) : ?>

        <?php if ($k == 'twitter-x-brand') : ?>
          <li>
            <a href="<?= $socialConf ?>" aria-label="<?= Module::t('amosdesign', 'Seguici su') . ' X' ?>" target="_blank" title="<?= Module::t('amosdesign', 'Seguici su') . ' X' ?>">
              <svg class="bg-white icon icon-tertiary icon-padded rounded-circle">
                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $k ?>"></use>
              </svg>
            </a>
          </li>
        <?php else : ?>
          <li>
            <a href="<?= $socialConf ?>" aria-label="<?= Module::t('amosdesign', 'Seguici su') . ' ' . $k ?>" target="_blank" title="<?= Module::t('amosdesign', 'Seguici su') . ' ' . $k ?>" class="social-icon">
          
              <span class="bg-white icon icon-tertiary icon-padded rounded-circle mdi mdi-<?= $k ?>"></span>

            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>

<?php endif; ?>