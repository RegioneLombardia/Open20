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
use open20\amos\core\widget\WidgetAbstract;

//Pickup assistance params
$assistance = isset(\Yii::$app->params['assistance']) ? \Yii::$app->params['assistance'] : [];

// New dashboard case
$newDashboard = !empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS;

//Check if is in email mode
$isMail = ((isset($assistance['type']) && $assistance['type'] == 'email') || (!isset($assistance['type']) && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
$mailAddress = isset($assistance['email']) ? $assistance['email'] : (isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : '');
?>

<?php if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))): ?>
    <div class="assistance">
        <a id="toggle-assistance" class="animated" href="<?= $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : '') ?>">
    <?php  if($newDashboard): ?>
            <div class="wrapper">
    <?php  endif; ?>        
                <?= AmosIcons::show('email', ['class' => 'tooltip-icon']) ?>
                <span class="tooltip-label"><?= Yii::t('amoscore', 'Hai bisogno di assistenza?'); ?></span>
                <span class="sr-only"><?= Yii::t('amoscore', 'Verrà aperta una nuova finestra') ?></span>
    <?php  if($newDashboard): ?>    
            </div> 
    <?php  endif; ?>
        </a>
    </div>
<?php endif; ?>