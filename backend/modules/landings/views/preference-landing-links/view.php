<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;

/**
* @var yii\web\View $this
* @var backend\modules\landings\models\PreferenceLandingLink $model
*/

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/landings']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Landing Link'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preference-landing-link-view">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
//                'preference_landing_id',
//            'sort_value',
            'title',
            'url',
    ],    
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::to([
        '/landings/preference-landing/update',
        'id' => $model->preference_landing_id,
        '#' => 'pos-links',
    ]), ['class' => 'btn btn-secondary']); ?></div>
