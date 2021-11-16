<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle
 * @category   CategoryName
 */
/** @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard * */

/** @var \yii\web\View $this * */
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\ServiceCall;
use open20\amos\moodle\helpers\MoodleHelper;

/**
 * @var yii\web\View $this
 * @var  $scormDetails
 */
?>
<?= $scormDetails['scormstatus']; ?>
<?php 

if (!empty($scormDetails['playerurl'])) {
    if (!$close) {
        echo Html::a(Yii::t('cruds', 'Entra', [
                    'modelClass' => 'Moodle Topic',
                ]), 
                MoodleHelper::getMoodleOAuthLink($scormDetails['playerurl']), 
                [
                    'class' => 'btn btn-amministration-primary js-btn-entra',
                    'target' => '_blank']);
    }
}
if ($close) {
    echo Html::a(Yii::t('cruds', 'Chiudi', [
                'modelClass' => 'Moodle Topic',
            ]), 
            null, 
            [
                'class' => 'btn btn-amministration-primary js-btn-close', 
                'data-dismiss' => "modal",
                'target' => '_blank']
            );
}
 ?>