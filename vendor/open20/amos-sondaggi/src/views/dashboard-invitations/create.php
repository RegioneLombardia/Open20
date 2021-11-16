<?php

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomande $model
 */

$this->title = AmosSondaggi::t('amossondaggi', '#insert_invitation_list');
// $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/index']];
//
// if (isset($url)) {
//     $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', '#invitation_lists'), 'url' => $url];
// }
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="sondaggi-invitations">
    <?=
    $this->render('_form', [
        'model' => $model,
        'url' => (isset($url)) ? $url : NULL,
    ])
    ?>
</div>
