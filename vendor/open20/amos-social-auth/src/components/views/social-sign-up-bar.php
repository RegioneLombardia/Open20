<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth
 * @category   CategoryName
 */

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
?>
<div class="social-auth-bar">
    <?php
    foreach ($providers as $providerName=>$config) {
        $lowCaseName = strtolower($providerName);

        echo Html::a(AmosIcons::show($lowCaseName), '/socialauth/social-auth/sign-up?provider=' . $lowCaseName, ['class' => 'btn btn-default']);
    }
    ?>
</div>
