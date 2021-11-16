<?php

/**
 * Lombardia Informatica S.p.A.
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
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Utenti'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = AmosAdmin::t('amosadmin', 'Il mio profilo');
?>
<div class="user-profile-update">
    <?= $this->render('_form', [
        'user' => $user,
        'model' => $model,
        'tipologiautente' => $tipologiautente,
        'permissionSave' => $permissionSave,
    ]) ?>
</div>
