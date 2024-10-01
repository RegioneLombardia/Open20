<?php

/**
 * Aria S.p.A.
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
    <div class="it-socials  flexbox">
        <span class="text-primary follow-text"><?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su') ?></span>
        <ul class="social-list list-unstyled flexbox">
            <?php foreach ($socialConfigurations as $k => $socialConf) : ?>
                <?php if ($k == 'twitter-x-brand') : ?>
                    <li>
                    <a href="<?= $socialConf ?>" aria-label="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su X') ?>" target="_blank" title="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su Twitter')?>">

                        <span class="mdi-2 mdi-<?= $k ?> icon"> </span>
                    </a>
                </li>
                <?php else: ?>
                <li>
                    <a href="<?= $socialConf ?>" aria-label="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su') . ' ' . $k ?>" target="_blank" title="<?= \open20\amos\layout\Module::t('amoslayout', 'Seguici su') . ' ' . $k ?>">

                        <span class="mdi mdi-<?= $k ?> icon"> </span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>