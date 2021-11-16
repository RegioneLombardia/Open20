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
    <div class="it-socials  flexbox">
        <span class="text-primary follow-text">Seguici su</span>
        <ul class="social-list list-unstyled flexbox">
            <?php foreach ($socialConfigurations as $k => $socialConf) : ?>
                <li>
                    <a href="<?= $socialConf ?>" aria-label="<?= 'Seguici su' . ' ' . $k ?>" target="_blank" title="<?= 'Seguici su' . ' ' . $k ?>">

                        <span class="mdi mdi-<?= $k ?> icon"> </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>