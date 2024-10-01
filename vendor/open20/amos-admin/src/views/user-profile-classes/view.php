<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-admin/src/views 
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\admin\AmosAdmin;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\UserProfileClasses $model
 */
$this->title                   = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Profili'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-classes-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:html',
            'code',
            'enabled',
        ],
    ])
    ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(BaseAmosModule::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
