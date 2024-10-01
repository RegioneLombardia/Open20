<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-utility/src/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;

/**
* @var yii\web\View $this
* @var open20\amos\utility\models\PlatformConfigs $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/utility']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Platform Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="platform-configs-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'module',
            'key',
            'value:html',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
