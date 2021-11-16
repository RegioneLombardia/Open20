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

use yii\bootstrap4\ActiveForm;
use open20\amos\core\helpers\Html;

if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
$this->title = Yii::t('campains', 'Aggiorna', [
    'modelClass' => 'Preference Campain',
]);
endif;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Campain'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>

<div class="preference-campain-update">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'needs-validation']]); ?>  

        <?= 
            $this->render('../wizard/_parts/_form_newsletter_detail', [
                'model' => $model,
                'form' => $form,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
                'template' => $template
            ]) 
        ?>

    <?php
	if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model->preferenceCampain])) :
	?>
		<div class="row mt-5">
			<div class="col-md-12 text-right">
				<div class="form-group">
					<?= Html::submitButton('Salva', ['class' => 'btn btn-primary'])?>
				</div>
			</div>
		</div>
	<?php
	endif;
	?>

    <?php ActiveForm::end(); ?>

</div>
