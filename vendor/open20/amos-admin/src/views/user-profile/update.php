<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 * @var string $tipologiautente
 * @var string $permissionSave
 */

$this->title = $model;
$this->params['titleSection'] = AmosAdmin::t('amosadmin', 'Il mio profilo');
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Partecipanti'), 'url' => ['/'.AmosAdmin::getModuleName().'/user-profile/validated-users']];
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Il mio profilo'), 'url' => ['/'.AmosAdmin::getModuleName().'/user-profile/view','id' => $model->id]];
$this->params['breadcrumbs'][] = AmosAdmin::t('amosadmin', 'Aggiorna');

//$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];

?>

<div class="user-profile-update">
    <?= $this->render('_form', [
        'user' => $user,
        'model' => $model,
        'tipologiautente' => $tipologiautente,
        'permissionSave' => $permissionSave,
        'profiles' => $profiles,
        'tabActive' => $tabActive
    ]) ?>
</div>
