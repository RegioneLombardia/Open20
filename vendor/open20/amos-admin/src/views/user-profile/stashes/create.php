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
 * @var bool $permissionSave
 */

$this->title = AmosAdmin::t('amosadmin', 'Crea');
$this->params['breadcrumbs'][] = ['label' => AmosAdmin::t('amosadmin', 'Utenti'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile-create">
    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
        'permissionSave' => $permissionSave,
    ]) ?>
</div>
