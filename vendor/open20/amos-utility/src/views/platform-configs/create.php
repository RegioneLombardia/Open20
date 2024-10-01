<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-utility/src/views 
 */
/**
* @var yii\web\View $this
* @var open20\amos\utility\models\PlatformConfigs $model
*/

$this->title = Yii::t('amoscore', 'Crea', [
    'modelClass' => 'Platform Configs',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/utility']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Platform Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\Yii::$app->view->params['titleSection'] = $this->title;

?>
<div class="platform-configs-create">
    <?= $this->render('_form', [
    'model' => $model,
    'fid' => NULL,
    'dataField' => NULL,
    'dataEntity' => NULL,
    ]) ?>

</div>
