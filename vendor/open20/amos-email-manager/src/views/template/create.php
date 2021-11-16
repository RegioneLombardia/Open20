<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\emailmanager\AmosEmail;
use open20\amos\emailmanager\models\EmailTemplate;


/* @var $this yii\web\View */
/* @var $model EmailTemplate */

$this->title = 'Create Email Template';
$this->params['breadcrumbs'][] = ['label' => AmosEmail::t('amosemail', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-template-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
