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
$this->title                   = AmosAdmin::t('amosadmin', 'Crea profilo');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Profili'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-classes-create">
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
