<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views
 */
/**
 * @var yii\web\View $this
 * @var open20\agid\organizationalunit\models\AgidOrganizationalUnit $model
 */

use open20\agid\organizationalunit\Module;

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Agid Organizational Unit',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/app']];
$this->params['breadcrumbs'][] = ['label' => Module::t('amosorganizationalunit', 'Organizational Unit'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>
<div class="agid-organizational-unit-update">

	<?=
		$this->render('_form', [
			'model' => $model,
			'fid' => null,
			'dataField' => null,
			'dataEntity' => null,
		])
	?>

</div>
