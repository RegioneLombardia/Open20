<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

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
          <a href="<?= $socialConf ?>" aria-label="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su X') ?>" target="_blank" title="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su Twitter')?>">

              <span class="mdi-2 mdi-<?= $k ?> icon"> </span>
            </a>
          </li>
        <?php else : ?>
        <li>
          <a href="<?= $socialConf ?>" aria-label="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su') . ' ' . $k ?>" target="_blank" title="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su') . ' ' . $k ?>" class="social-icon">
            <span class="mdi mdi-<?= $k ?>"></span>
          </a>
        </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div>

<?php endif; ?>