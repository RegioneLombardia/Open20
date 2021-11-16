<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $model open20\amos\emailmanager\models\EmailSpool */

$this->title = 'Update Email Spool: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Email Spools', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="email-spool-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
