<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\core\module\BaseAmosModule;
use yii\helpers\Html;

?>
<?php if (isset(Yii::$app->params['socialConfigurations']) && is_array(Yii::$app->params['socialConfigurations']) && !empty(Yii::$app->params['socialConfigurations'])) : ?>
  <?php
  $socialConfigurations = Yii::$app->params['socialConfigurations'];
  ?>
  <div class="it-socials d-none d-md-flex">
    <span>Seguici su</span>
    <ul>
      <?php foreach ($socialConfigurations as $k => $socialConf) : ?>
        <?php if ($k == 'flickr') : ?>
          <li>
            <a href="<?= $socialConf ?>" aria-label="<?= 'Seguici su' . ' ' . $k ?>" target="_blank" title="<?= 'Seguici su' . ' ' . $k ?>">
              <svg class="icon">
                <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-<?= $k ?>"></use>
              </svg>
            </a>
          </li>
        <?php else : ?>
          <li>
            <a href="<?= $socialConf ?>" aria-label="<?= 'Seguici su' . ' ' . $k ?>" target="_blank" title="<?= 'Seguici su' . ' ' . $k ?>">
              <svg class="icon">
                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $k ?>"></use>
              </svg>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>