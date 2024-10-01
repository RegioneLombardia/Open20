<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-groups
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\widgets\GroupOrganizationsWidget;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var \open20\amos\organizzazioni\models\ProfiloGroups $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<form class="profilo-groups-view col-xs-12 m-t-5 nop">
    <div class="row">
        <div class="col-xs-12 m-b-15">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'description:raw'
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= GroupOrganizationsWidget::widget([
                'model' => $model,
                'isUpdate' => false,
            ]); ?>
        </div>
    </div>
    <div class="btnViewContainer pull-right">
        <?= Html::a(Module::t('amosorganizzazioni', '#close'), Yii::$app->session->get(Module::beginCreateNewSessionKey()), ['class' => 'btn btn-secondary']); ?>
    </div>
</form>
