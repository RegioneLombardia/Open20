<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/compains/views
 */
/**
 * @var yii\web\View $this
 * @var backend\modules\campains\models\PreferenceCampain $model
 */

use open20\amos\core\helpers\Html;
use yii\bootstrap4\ActiveForm;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\campains\Module;
use Yii;

$this->title = Yii::t('amoscore', 'Aggiorna', [
    'modelClass' => 'Preference Campain',
]);

$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Campain'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');

?>

<div class="preference-campain-update">
	<?php $form = ActiveForm::begin(['id' => 'form-search']);?>

	<?=
		$this->render('@backend/modules/campains/views/wizard/_parts/_base_info',
			[
				'model' => $model,
				'form' => $form,
				'tag' => $tag,
				'profili' => $profili,
				'modelSearch' => $modelSearch,
			])
	?>

	<?php
	if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
	?>

		<div class="d-flex mt-5">
			<div class="ml-auto">
				<div class="form-group">
					<?= Html::submitButton('Salva', ['class' => 'btn btn-primary'])?>
				</div>
			</div>
		</div>
	<?php
	endif;
	?>

	<?php ActiveForm::end();?>
</div>
