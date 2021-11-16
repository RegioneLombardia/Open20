<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\modules\campains\Module;
use open20\amos\events\AmosEvents;
use preference\userprofile\utility\UserInterestTagUtility;
use backend\modules\campains\models\PreferenceCampainTagMm;
use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\operators\models\Operator;
use yii\web\View;

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */

$moduleCwh = \Yii::$app->getModule('cwh');
$scope = null;
if (!empty($moduleCwh)) {
	$scope = $moduleCwh->getCwhScope();
}
?>
<!-- TAGIT WIDGET STYLE -->
<style>
	ul.tagit>li.tagit-new {
		float: none;
	}

	ul.tagit>li.tagit-new>input {
		width: 100%;
	}
</style>

<!-- START SECTION titolo campagna -->
<div class="info-base my-4">
	<div class="py-4 d-inline-flex w-100 title-substeps affix-top">
		<div class="mr-2">
			<svg class="icon">
				<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_info"></use>
			</svg>
		</div>

		<h5 class="font-weight-bold">
			<?= Module::t('campains',  "Titolo della campagna") ?>
		</h5>
	</div>

	<div class="row variable-gutters my-6">
		
		<div class="col-md-8">
			<?= $form->field($model, 'titolo')
                ->textInput(['id' => 'campain-title', 'maxlength' => "255"])
                ->label('Titolo')
                ->hint('max 255 caratteri') ?>
		</div>
		
	</div>


	<?php
	/** @var Operator $op */
	$op = Operator::findOne(['user_id' => Yii::$app->user->id]);
	if (empty($op->pc_structure_id)):
	?>
		<div class="row variable-gutters my-6">

			<div class="col-md-8">
				<?= $form->field($model, 'pc_structure_id')->widget(Select2::classname(), [
                        'options' => [
                            'placeholder' => 'Struttura',
                            'disabled' => $structureReadOnly,
                            'id' => 'pc-structure',
                        ],
                        'data' => ArrayHelper::map(\backend\modules\operators\models\PreferenceStructure::find()->orderBy('name')->asArray()->all(), 'id', 'name')
					])->label('<div class="py-4 d-inline-flex w-100 title-substeps affix-top"><div class="mr-2"><svg class="icon"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_info"></use></svg></div>
<h5 class="font-weight-bold">Struttura di appartenenza</h5></div>Struttura');
				?>

			</div>
			
		</div>

	<?php
	else:
		$model->pc_structure_id = $op->pc_structure_id;
	?>

		<?= $form->field($model, 'pc_structure_id')->hiddenInput()->label('') ?>

	<?php
	endif;
	?>


</div>
<!-- END SECTION titolo campagna -->


<hr class="dashed">


<!-- START SECTION Seleziona Utenti -->
<div class="preferences-center">

	<div class="py-4 d-inline-flex w-100 title-substeps affix-top">
		<div class="mr-2">
		<svg class="icon ">
			<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_mail"></use>
		</svg>
		</div>
		<h5 class="font-weight-bold ">
			<?= Module::t('amosevents', "Seleziona utenti") ?>
		</h5>
	</div>

	<!-- START SECTION select option profilo -->
	<div class="row">
		
		<div class="col-md-8">
			<?=
				$form->field($model, 'profili')->widget(Select2::classname(), [
					'name' => 'profili',
					'data' => ArrayHelper::map($profili, 'id', 'nome'),
					'options' => [
						//'placeholder' => 'Select...',
						'multiple' => false,
						'value' => $profili->nome
					],
				]);
			?>
		</div>
		
	</div>

	<!-- END SECTION select option profilo -->

	<div id='topic-section-id'>
	<!-- START checkbox list cittadino  -->
	<div class="row py-4 div_checkbox_tematiche " id="id_div_cittadino" hidden>

		<?php foreach ($tag['cittadino'] as $value) : ?>

			<?php
				// check if Tag exist and check the checkbox
				$preference_campain_tag_mm = PreferenceCampainTagMm::find()->andWhere([
					'preference_campain_id' => $model->id,
					'tag_id' => $value->id
				])->one();
			?>
			
			<div class="col-md-4 d-flex mb-2">
				<div class="bg-custom-tag-green h-100 w-100 px-3 py-2 font-weight-bold">
					<div class="form-check">
						<input type="checkbox" name="tag_cittadino[]" id="<?= $value->id ?>" value="<?= $value->id ?>" <?= (null != $preference_campain_tag_mm ? 'checked' : '') ?>>
						<label for="<?= $value->id ?>" class="d-flex w-100 text-blu-regione">
							<img src="/img/icone-tematiche/<?= $value->icon ?>" class="mr-2">
							<?= $value->nome ?>
						</label>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

	</div>
	<!-- END checkbox list cittadino -->

	<!-- START checkbox list impresa  -->
	<div class="row py-4 div_checkbox_tematiche" id="id_div_impresa" hidden>

		<?php foreach ($tag['impresa'] as $value) : ?>
		
			<?php
				// check if Tag exist and check the checkbox
				$preference_campain_tag_mm = PreferenceCampainTagMm::find()->andWhere([
					'preference_campain_id' => $model->id,
					'tag_id' => $value->id
				])->one();
			?>
			
			<div class="col-md-4 d-flex mb-2">
				<div class="bg-custom-tag-green h-100 w-100 px-3 py-2 font-weight-bold">
					<div class="form-check">
						<input type="checkbox" name="tag_impresa[]" id="<?= $value->id ?>" value="<?= $value->id ?>" <?= (null != $preference_campain_tag_mm ? 'checked' : '') ?>>
						<label for="<?= $value->id ?>" class="d-flex w-100 text-blu-regione">
							<img src="/img/icone-tematiche/<?= $value->icon ?>" class="mr-2">
							<?= $value->nome ?>
						</label>
					</div>
				</div>
			</div>

		<?php endforeach; ?>

	</div>
	<!-- END checkbox list impresa -->

	<!-- START checkbox list ente e operatore -->
	<div class="row py-4 div_checkbox_tematiche" id="id_div_ente_operatore" hidden>

		<?php foreach ($tag['ente_operatore'] as $value) : ?>

			<?php
				// check if Tag exist and check the checkbox
				$preference_campain_tag_mm = PreferenceCampainTagMm::find()->andWhere([
					'preference_campain_id' => $model->id,
					'tag_id' => $value->id
				])->one();
			?>

			<div class="col-md-4 d-flex mb-2">
				<div class="bg-custom-tag-green h-100 w-100 px-3 py-2 font-weight-bold">
					<div class="form-check">
						<input type="checkbox" name="tag_ente_operatore[]" id="<?= $value->id ?>" value="<?= $value->id ?>" <?= (null != $preference_campain_tag_mm ? 'checked' : '') ?>>
						<label for="<?= $value->id ?>" class="d-flex w-100 text-blu-regione">
							<img src="/img/icone-tematiche/<?= $value->icon ?>" class="mr-2">
							<?= $value->nome ?>
						</label>
						<!-- TODO GRAFICA: cambiare le icon giuste -->
						<!-- $value->icon  -->

					</div>
				</div>
			</div>

		<?php endforeach; ?>

	</div>
	<!-- END checkbox list ente e operatore -->
	</div>	
	
	
	<div class="row">
		
		<div class="col-md-8">
		<div class="row variable-gutters">
            <div class="col-md-12">
            
               
                <?php \yii\widgets\Pjax::begin(['id' => 'search-container', 'timeout' => 2000]);

                $count = 0;
                if (!empty($modelSearch['field']) && count($modelSearch['field']) > 0) {
                    $count = count($modelSearch['field']);
                }
                ?>
				<?= $this->render('_search_users', ['modelSearch' => $modelSearch, 'form' => $form, 'count' => $count]) ?>
				<div style="display:none">
					<?= $form->field($model, 'search_params')->hiddenInput(['id' => 'result-search-params']) ?>
				</div>
                <?php \yii\widgets\Pjax::end(); ?>

                <div class="my-5">
                    <?= Html::a(Module::t('campains',  "+ AGGIUNGI REGOLA"), [], ['id' => 'btn-add-rule']) ?>
                </div>
                
            </div>
        </div>
		</div>
		
	</div>


</div>
<!-- END SECTION Seleziona Utenti -->




<hr class="dashed my-4">



<!-- START SECTION Canale di invio -->
<div class="info-base my-4" id='channels-section-id'>
	<div class="py-4 d-inline-flex w-100 title-substeps affix-top">
		<div class="mr-2">
			<svg class="icon">
				<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_info"></use>
			</svg>
		</div>
		<h5 class="font-weight-bold">
			<?= Module::t('amosevents', "Seleziona il canale di invio") ?>
		</h5>
	</div>

	<!-- START checkbox list canali di invio -->

	<?php foreach (UserInterestTagUtility::getAllChannels() as $value) : ?>

		<?php 
			/**
			 * controllo l'esistenza dei canali per la PrefrenceCampain
			 */
			$preference_campain_channel_mm = PreferenceCampainChannelMm::find()->where([
				'preference_campain_id' => $model->id,
				'preference_channel_id' => $value->id,
				'deleted_at' => null
			])->one();
		?>

		<div class="row">
			<div class="col-md-8 mb-3 p-3 d-flex justify-content-between checkbox-canali-invio">
				<div class="d-flex align-items-center">
					<div>
						<div class="form-check">
							<input id="<?= $value->id ?>" type="checkbox" name="channel[]" value="<?= $value->id ?>" <?= (null != $preference_campain_channel_mm) ? 'checked' : '' ?>>
							<label for="<?= $value->id ?>">

						<?php 



							switch ($value->id) {
								case 1:
									?>
										<span class="p-1">
											<svg class="icon icon-black"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_email"></use></svg>
										</span>
									<?php
									break;
								case 2:
									?>
										<span class="p-1">
											<svg class="icon icon-black"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_chat"></use></svg>
										</span>
									<?php
									break;

								case 3:
									?>
										<span class="p-1">
											<svg class="icon icon-black"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_phone"></use></svg>
										</span>
									<?php
									break;
								case 4:
									?>
										<span class="p-1">
											<svg class="icon icon-black"><use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_smartphone"></use></svg>
										</span>
									<?php
									break;
							}

							?>
								<span class="font-weight-bold">
									<?= $value->title ?>
								</span>
							</label>
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center">
					<p class="mb-0"><div id='number-recipients-<?= $value->id ?>'>-</div>&nbsp;Destinatari</p>
				</div>
			</div>
		</div>

	<?php endforeach; ?>
	<!-- END checkbox list canali di invio -->
	

	<div class="row my-4">
		<div class="col-md-8 p-0">
			<?= Html::a('CALCOLA DESTINATARI', ['#'], ['class' => 'btn btn-primary btn-block', 'id' => 'calculate-redipients-action-id']) ?>
		</div>
		
	</div>

	<div id='error-caculate-data'></div>
	

</div>
<!-- END SECTION Canale di invio -->

<?php

$this->registerJs(
<<<JS

function realoadSearch(container, plus){
	$.pjax.reload({
		container: container,
		timeout: 20000,
		replace: false,
		url: '/campains/preference-campain/render-search-ajax',
		data: {
			'data': $('#form-search').serialize(),
			'plus': plus,
			},
		method: 'post'
	})
}
	
$(document).on('click','#btn-add-rule',function(e){
	e.preventDefault();
	realoadSearch('#search-container', 1);
});

$(document).on('click', '#btn-remove-rule',function(e){
	e.preventDefault();
	var value = $(this).attr('data');
	$('#row-search-'+value).remove();
	realoadSearch('#search-container', 0);
});

check_number_of_recipients();

$("#calculate-redipients-action-id").click(function(event) {
	event.preventDefault();
	check_number_of_recipients();
});

function check_number_of_recipients() {
    var allTags = [];
	$('#topic-section-id :checked').each(function() {
		allTags.push($(this).val());
	});

	var allChannels = [];
	$('#channels-section-id :checked').each(function() {
		allChannels.push($(this).val());
	});

	$.post({
		url: '/campains/preference-campain/calculate-recipients-ajax',
		type: 'post',
		data: {
				tags: allTags,
				channels: allChannels,
				form: $('#form-search').serialize(),
		},
		success: function (data) {
			$("[id^='number-recipients-']").text('-');
			if(data['status'] == 'ok'){                     
				$.each(data['data'], function( index, value ) {
					$('#number-recipients-' + index).text(value);
				});		

				$('#error-caculate-data').hide();			
			}

			if(data['status'] == 'ko'){                     
				$('#error-caculate-data').html('<div id="box-error-caculate-data" class="alert alert-danger alert-dismissible" role="alert">' + data['data'] + ' \
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button> \
				</div>');			
			}

		}
	});
}

JS
, View::POS_READY );
?>









<?php
/**
 * check check the value from the profile selection option 
 * and view the corresponding preference list
 */

$js = <<<JS
	$('#preferencecampain-profili').change(function(){

		var select_option_text = $("#preferencecampain-profili option:selected").html();

		if(select_option_text == "Cittadino"){

			$('.div_checkbox_tematiche').attr('hidden', true);
			$('#id_div_cittadino').removeAttr('hidden');
		
		}else if(select_option_text == "Impresa"){

			$('.div_checkbox_tematiche').attr('hidden', true);
			$('#id_div_impresa').removeAttr('hidden');
		
		}else if(select_option_text == "Ente e Operatore"){

			$('.div_checkbox_tematiche').attr('hidden', true);
			$('#id_div_ente_operatore').removeAttr('hidden');
		}

	});

	$("#preferencecampain-profili").val($("#preferencecampain-profili option:first").val()).change();
JS;
$this->registerJs($js, View::POS_READY);
?>