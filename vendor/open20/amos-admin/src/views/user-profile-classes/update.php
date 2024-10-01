<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-admin/src/views 
 */
use open20\amos\admin\AmosAdmin;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\UserProfileClasses $model
 */
$this->title                   = AmosAdmin::t('amosadmin', 'Aggiorna');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Profili'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-classes-update">

    <?=
    $this->render('_form',
        [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ])
    ?>

</div>
