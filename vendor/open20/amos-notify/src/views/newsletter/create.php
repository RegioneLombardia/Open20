<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\newsletter
 * @category   CategoryName
 */

use open20\amos\notificationmanager\AmosNotify;

/**
 * @var yii\web\View $this
 * @var open20\amos\notificationmanager\models\Newsletter $model
 */

$this->title = AmosNotify::t('amosnotify', 'Create newsletter');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="newsletter-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
