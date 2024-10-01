<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/cms-dashboard/src/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;

/**
* @var yii\web\View $this
* @var open20\cms\dashboard\models\CmsDashSidebarItem $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-dash-sidebar-item-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
            'link:html',
            'link_shortcut:html',
            'label',
            'description',
            'icon_name',
            'id_container',
            'class_container',
            'isVisible',
            'isTargetBlank',
            'position',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
