<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\sondaggi
 * @category   CategoryName
 */

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\Sondaggi $model
 * @var \open20\amos\cwh\AmosCwh $moduleCwh
 * @var array $scope
 */

if (empty($model->id)) {
    $this->title = AmosSondaggi::t('amossondaggi', '#create_poll');
}
else {
    $this->title = AmosSondaggi::t('amossondaggi', '#poll_info');
}
 $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['sondaggi/index']];
 $this->params['breadcrumbs'][] = ['label' => $this->title];
 if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-create">
    <?=
    $this->render('_form'.$page, [
        'model' => $model,
        'url' => (isset($url)) ? $url : NULL,
        'public' => isset($public) ? $public : NULL,
        'moduleCwh' => $moduleCwh,
        'scope' => $scope
    ])
    ?>
</div>
