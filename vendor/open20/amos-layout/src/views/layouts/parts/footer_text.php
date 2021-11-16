<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use yii\helpers\Html;

?>
<?php if ((isset(Yii::$app->params['footerText'])) && (Yii::$app->params['footerText'])): ?>
<div class="footer-space">
    <div class="footer-text-container">
        <div class="footer-text">
            <div class="container">
                <p class="power-by-open">Powered by OPEN 2.0</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$socialModule = \Yii::$app->getModule('social');
if (!is_null($socialModule) && class_exists('\kartik\social\GoogleAnalytics')):
    if (!empty($socialModule->googleAnalytics)):
        echo \kartik\social\GoogleAnalytics::widget([]);
    endif;
endif;
?>
