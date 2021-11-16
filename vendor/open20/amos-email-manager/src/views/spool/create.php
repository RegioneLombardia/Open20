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

$this->title = 'Create Email Spool';
$this->params['breadcrumbs'][] = ['label' => 'Email Spools', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-spool-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
