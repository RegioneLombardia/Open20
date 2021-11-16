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
use open20\amos\moodle\helpers\MoodleHelper;

/**
 * @var yii\web\View $this
 * @var  $certificateDetails
 */
?>
<?php //pr($certificateDetails); ?>
<p>
    <strong>Emesso il </strong>
<?php echo date('d/m/Y H:i',$certificateDetails["timecreated"]) ?>
</p>

<p>Fai click sul pulsante sottostante per aprire il tuo certificato in una nuova finestra.</p>

<?php 

if (!empty($certificateDetails["fileurl"])) {?>
    <?php echo Html::a(Yii::t('cruds', 'Consegui il tuo certificato', [
                'modelClass' => 'Moodle Topic',
            ]), MoodleHelper::getMoodleOAuthLink($certificateDetails["fileurl"]), ['class' => 'btn btn-amministration-primary', 'target' => '_blank']);
}
 ?>


