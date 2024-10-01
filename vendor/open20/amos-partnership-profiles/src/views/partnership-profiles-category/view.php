<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-partnership-profiles/src/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;

/**
* @var yii\web\View $this
* @var open20\amos\partnershipprofiles\models\PartnershipProfilesCategory $model
*/

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/partnershipprofiles']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Partnership Profiles Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partnership-profiles-category-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'title',
            'subtitle',
            'short_description',
            'description:html',
            'color_text',
            'color_background',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
