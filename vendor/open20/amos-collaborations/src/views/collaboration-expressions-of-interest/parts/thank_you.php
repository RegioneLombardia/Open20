<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor\open20\amos\collaborations\views/views
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;

/**
 * @var yii\web\View $this
 * @var open20\amos\collaborations\models\CollaborationExpressionsOfInterest $model
 */

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/collaborations']];

$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Proposte di collaborazione'),  'url' => ['/collaborations/collaboration-proposals/index']];
$this->params['forceBreadcrumbs'][] = ['label' => \Yii::t('amoscollaborations', 'Manifesta interesse')];
?>

<div>
<div class="alert alert-success" role="alert">
        <?= \Yii::t('amoscollaborations', 'Il form è stato processato correttamente') ?>
    </div>
    <br>
    <div>
        <?= Html::a(
            \Yii::t('amoscollaborations', 'Torna alla proposta di collaborazione'),
            Url::previous(),
            ['class' => 'btn btn-secondary']) ?>
    </div>
</div>
