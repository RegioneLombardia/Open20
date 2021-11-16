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

// $this->title = Yii::t('amoscore', 'Aggiorna', [
//     'modelClass' => 'Preference Campain',
// ]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Campain'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Aggiorna');
?>


<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="mb-0 pr-2 text-muted text-uppercase">
                <?= "Aggiorna Campagna: Newsletter" ?>
            </h3>
        </div>
        <div>
            <div class="step">4</div>
        </div>
    </div>
</div>

<div class="preference-campain-update">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'needs-validation']]); ?>  

        <?= 
            $this->render('../wizard/_parts/_form_newsletter_content', [
                'model' => $model,
                'form' => $form,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
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
