<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\events\assets\WizardEventAsset;
use backend\modules\campains\models\PreferenceCampainCopy;
use backend\modules\campains\utility\CampainsChannelUtility;
use backend\modules\campains\models\base\PreferenceCampainSection;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

// $this->title                   = Yii::t('campains', 'Nuova Campagna: destinatari e canali');
// $this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
// $this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
$channel = CampainsUtility::campainGetNewsletterModel($model);
?>
<?php
	if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model]) && CampainsUtility::campainHaveNewsletterChannel($model)) :
?>
<div class="row mt-2">

	<div class="col-md-12 ">
		<?php
		$options = [
			'class' => 'btn btn-xs btn-primary', 
			'id' => 'copia-contenuti-button-id',
			'data-toggle' => "modal",
			'data-target' => "#modal-content-copy-id"
		];
		
		?>
		<?= Html::button('Copia contenuto da Email', $options) ?>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id='modal-content-copy-id'>
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Copia contenuto da Email
					</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<svg class="icon">
							<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use>
						</svg>
					</button>
				</div>
				
				<?php 
					$formCopy = ActiveForm::begin([
						'options' => [
							'id' => 'id-form-copy',
							'class' => 'needs-validation', 
							'enctype' => 'multipart/form-data'
						],
						'action' =>['/campains/preference-campain/copy-app-content-from-email?id='.$model->id]
					]); 
				?> 

					<div class="modal-body">
						<?php
						// VarDumper::dump( $newsletter_content[0]['container'], $depth = 3, $highlight = true); 
						if (isset($newsletter_content[0]['container']) && count($newsletter_content[0]['container']) > 0): ?>
						<div class="container-messages container">
							<div id="w5" class="alert-warning alert alert-dismissible" role="alert">
								ATTENZIONE SE HAI GIA' DEI CONTENUTI INSERITI, DOPO LA CONFERMA VERRANNO SOSTITUITI CON QUELLI NUOVI
								<!-- <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button> -->
							</div>
						</div> 
						<?php endif;?>

						<?php 
							$model_preference_campain_copy = new PreferenceCampainCopy(['scenario' => PreferenceCampainCopy::SCENARIO_COPY_CHANNEL]);
							
						?>

						<?= $formCopy->field($model_preference_campain_copy, 'preference_campain_id')->hiddenInput(['value'=> $model->id])->label(false); ?>

						<div class="row mt-5">
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<div class="progress-spinner progress-spinner-active" hidden>
									<span class="sr-only">Caricamento...</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<?php 
									$model_preference_campain_copy->type_copy = "solo_struttura";
								?>
								<?= 
									$formCopy->field($model_preference_campain_copy, 'type_copy')->radioList(
										[
											'solo_struttura' => 'Solo struttura',
											'struttura_e_contenuti' => 'Struttura e contenuti'
										], 
									['class' => "" ] )->label("Scegli contenuto da inserire") 
								?>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary btn-xs" data-dismiss="modal" type="button">Annulla</button>
						<?= Html::submitButton('Copia', ['id' => "id-btn-modal-copia", 'class' => 'btn btn-primary  btn-xs', 'data-confirm' => "ATTENZIONE SE HAI GIA' DEI CONTENUTI INSERITI, DOPO LA CONFERMA VERRANNO SOSTITUITI CON QUELLI NUOVI"])?>
					</div>

				<?php ActiveForm::end(); ?>
				

			</div>
		</div>
	</div>
</div>
<?php
endif;
?>

<!-- START SECTIONS -->
<?php
/** @var PreferenceCampainSection $content */
foreach ($newsletter_content as $content) : ?>

	<div class="row mt-3">
		<div class="col-md-12">

			<div class="altre-notizie neutral-1-bg-a1 p-4" style="border-color: <?= $content['section']->color ?>;">

				<!-- START header row -->
				<div class="row pl-2 pr-3 mb-3 align-items-center">
					<div class="col-4 col-xs-8 col-md-9 pl-0 section-title text-uppercase font-weight-bold" style="color: <?= $content['section']->color == '#ffffff00' ? 'black' : $content['section']->color  ?>;">
						<?= $content['section']->title ?>
					</div>

					<div class="col-xs-2 col-lg-1 col-4 ml-auto d-flex justify-content-center justify-content-md-around">
						<div class="arrow d-flex">
							<?php
							// NO ACTION FOR APP CONTENT
							?>
						</div>
					</div>

					<div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
						<?php
						// NO ACTION FOR APP CONTENT 
						?>
					</div>
				</div>
				<!-- STOP header row -->


				<!-- START Section -->
				<?php foreach ($content['container'] as $container) : ?>

					<?php if (
						$container->preference_campain_container_type_id ==
						CampainsUtility::preferenceCampainContainerTypeByName("Contenuto")->id
					) : ?>

						<div class="news-contenuto-container mt-4">
							<div class="news contenuto">
								<div class="row px-3 py-2 mb-2 align-items-center">
									<div class="text-left text-md-center col-3 col-xs-2 col-lg-1">
										<?php
										if (!empty($container->contentImage)) :
										?>
											<img src="<?= $container->contentImage->getWebUrl() ?>" alt="immagine contenuto" class="img-fluid">
										<?php
										endif
										?>
									</div>
									<div class="col-xs-2 col-lg-1 col-9 text-right text-sm-left text-black text-uppercase">Contenuto</div>
									<div class="col-xs-3 col-md-4 col-lg-6 my-3 my-md-0 pl-0 pl-sm-3 pl-xl-0 text-center text-sm-left text-black text-uppercase font-weight-bold">
										<?= $container->content_title ?>
									</div>

									<div class="col-xs-1 col-4 ml-auto d-flex justify-content-start justify-content-md-center">

										<!-- <svg class="icon star-icon active">
												<use xlink:href="< ?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star"></use>
											</svg> -->


									</div>
									<div class="col-xs-2 col-lg-1 col-4 d-flex justify-content-center justify-content-md-around">
										<?php
										if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
										?>

											<div class="arrow d-flex">
												<a class="btn btn-xs btn-icon mr-auto p-1 btn-container-order-up" href="#" title="" data-section_id="<?= $content['section']->id ?>" data-container_id="<?= $container->id ?>" data-toggle="tooltip" data-original-title="Sposta contenuto notizia secondaria in su">
													<svg class="icon arrow-icon icon-black">
														<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
													</svg>
												</a>
												<a class="btn btn-xs btn-icon ml-auto p-1 btn-container-order-down" href="#" title="" data-section_id="<?= $content['section']->id ?>" data-container_id="<?= $container->id ?>" data-toggle=" tooltip" data-original-title="Sposta contenuto notizia secondaria in giù">
													<svg class="icon arrow-icon icon-black">
														<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
													</svg>
												</a>
											</div>

										<?php
										endif;
										?>
									</div>
									<div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
										<a class="btn btn-xs btn-icon p-1" data-toggle="modal" data-target="#modal-container-<?= $container->id ?>" data-section_id="<?= $content['section']->id ?>" data-container_id="<?= $container->id ?>" href="#" title="Modifica contenuto">
											<span class="rounded-icon rounded-secondary p-1" data-toggle="tooltip" data-original-title="Modifica contenuto">
												<svg class="icon icon-white">
													<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use>
												</svg>
											</span>
											<span class="sr-only">Modifica contenuto</span>
										</a>
										<?php
										if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
										?>
											<!-- <a class="btn btn-xs btn-icon p-1" href="/campains/wizard/delete-preference-campain-container?id=< ?= $model->id ?>&preference_campain_container_id=< ?= $container->id ?>" title="" data-toggle="tooltip" data-original-title="Elimina contenuto"> -->
											<a class="btn btn-xs btn-icon p-1 btn-delete-preference-campain-container" data-container_id="<?= $container->id ?>" href="#" title="" data-toggle="tooltip" data-original-title="Elimina contenuto">
												<span class="rounded-icon rounded-danger p-1">
													<svg class="icon icon-white">
														<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use>
													</svg>
												</span>
												<span class="sr-only">Elimina contenuto</span>
											</a>
										<?php
										endif;
										?>
									</div>

								</div>
							</div>
						</div>

					<?php endif; ?>

					<?php if (
						$container->preference_campain_container_type_id ==
						CampainsUtility::preferenceCampainContainerTypeByName("Contenuto Esterno")->id
					) : ?>

						<div class="news-contenuto-esterno-container">
							<div class="news contenuto-esterno">
								<div class="row px-3 py-2 align-items-center mt-2">
									<div class="text-left text-md-center col-3 col-xs-2 col-lg-1">
										<svg class="icon icon-black">
											<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_rss_feed"></use>
										</svg>
									</div>
									<div class="col-xs-2 col-lg-1 col-9 text-right text-sm-left text-black text-uppercase">CONTENUTO ESTERNO</div>
									<div class="col-xs-3 col-md-4 col-lg-6 my-3 my-md-0 pl-0 pl-sm-3 pl-xl-0 text-center text-sm-left text-black text-uppercase font-weight-bold">
										<?= $container->content_title ?>
									</div>
									<div class="col-xs-1 col-4 ml-auto d-flex justify-content-start justify-content-md-center">

										<!-- <svg class="icon star-icon disabled">
												<use xlink:href="< ?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star"></use>
											</svg> -->


									</div>
									<div class="col-xs-2 col-lg-1 col-4 d-flex justify-content-center justify-content-md-around">
										<div class="arrow d-flex">
											<?php
											if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
											?>
												<a class="btn btn-xs btn-icon mr-auto p-1 btn-container-order-up" data-section_id="<?= $content['section']->id ?>" data-container_id="<?= $container->id ?>" href="#" title="" data-toggle="tooltip" data-original-title="Sposta smart working: una scelta per il futuro in su">
													<svg class="icon arrow-icon icon-black">
														<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_up"></use>
													</svg>
												</a>
												<a class="btn btn-xs btn-icon ml-auto p-1 btn-container-order-down" data-section_id="<?= $content['section']->id ?>" data-container_id="<?= $container->id ?>" href="#" title="" data-toggle="tooltip" data-original-title="Sposta smart working: una scelta per il futuro in giù">
													<svg class="icon arrow-icon icon-black">
														<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_arrow_drop_down"></use>
													</svg>
												</a>
											<?php
											endif;
											?>
										</div>
									</div>
									<div class="col-xs-2 col-md-1 col-4 d-flex justify-content-center justify-content-md-around">
										<a class="btn btn-xs btn-icon p-1" href="#" title="Modifica contenuto esterno" data-toggle="modal" data-target="#modal-container-<?= $container->id ?>">
											<span class="rounded-icon rounded-secondary p-1" data-toggle="tooltip" data-original-title="Modifica contenuto esterno">
												<svg class="icon icon-white">
													<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use>
												</svg>
											</span>
											<span class="sr-only">Modifica contenuto esterno</span>
										</a>
										<?php
										if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
										?>
											<a class="btn btn-xs btn-icon p-1 btn-delete-preference-campain-container" data-container_id="<?= $container->id ?>" href="#" title="" data-toggle="tooltip" data-original-title="Elimina contenuto esterno">
												<span class="rounded-icon rounded-danger p-1">
													<svg class="icon icon-white">
														<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use>
													</svg>
												</span>
												<span class="sr-only">Elimina contenuto esterno</span>
											</a>
										<?php
										endif;
										?>
									</div>
								</div>
							</div>
						</div>

					<?php endif; ?>

				<?php endforeach; ?>
				<!-- END Section -->

				<?php
				if (Yii::$app->user->can('PREFERENCECAMPAIN_UPDATE', ['model' => $model])) :
				?>
					<!-- START Button sezione -->
					<div class="py-2 d-flex flex-wrap flex-column flex-md-row align-items-start mt-2">

						<!-- BTN NUOVO CONTENUTO -->
						<?= Html::button(
							'<svg class="icon icon-white">
							<use xlink:href="' . $spriteAsset->baseUrl . '/material-sprite.svg#ic_add_box"></use>
						</svg>
						<span class="text-uppercase text-white">Inserisci nuovo contenuto</span>',
							[
								'class' => 'btn btn-xs btn-news-yellow btn-icon mr-md-4 mb-3 mb-md-0 btn-new-container-content',
								'data-toggle' => "modal",
								'data-target' => "#modalinseriscinuovocontenuto",
								'value' => $content['section']->id,
							]
						)
						?>

						<!-- BTN NUOVO CONTENUTO ESTERNO -->
						<?= Html::button(
							'<svg class="icon icon-white">
							<use xlink:href="' . $spriteAsset->baseUrl . '/material-sprite.svg#ic_rss_feed"></use>
						</svg>
						<span class="text-uppercase text-white">Inserisci contenuto esterno</span>',
							[
								'class' => 'btn btn-xs btn-news-pink btn-icon mr-md-4 mb-3 mb-md-0 btn-new-external-container-content',
								'data-toggle' => "modal",
								'data-target' => "#modalinseriscinuovocontenutoesterno",
								'value' => $content['section']->id,
							]
						)
						?>

						<!-- BTN NUOVO SEPARATORE -->
						<?php
						// echo Html::button(
						// 	'<svg class="icon icon-white">
						// 		<use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_space_bar"></use>
						// 	</svg>
						// 	<span class="text-uppercase text-white">Inserisci separatore</span>',
						// 	[
						// 		'class' => 'btn btn-xs btn-news-gray btn-icon btn-new-container-separator',
						// 		'data-toggle' => "modal",
						// 		'data-target' => "#modalinseriscinuovoseparatore",
						// 		'value' => $content['section']->id,
						// 	])
						?>

					</div>
					<!-- END Butto sezione -->
				<?php
				endif;
				?>

			</div>

		</div>
	</div>


<?php endforeach; ?>
<!-- END SECTIONS -->


<?php

/** PAGE SCRIPTS */
$script = <<< JS

	// BUTTON change container order up
	$('.btn-container-order-up').click(function(){
		var preference_campain_section_id = $(this).data('section_id');
		var preference_campain_container_id = $(this).data('container_id');
		var type_order = "up";
	
		var url = '/campains/wizard/set-preference-campain-container-order';
        var data = "preference_campain_section_id="+preference_campain_section_id+"&preference_campain_container_id="+preference_campain_container_id+"&type_order="+type_order;

		ajaxCall(url, data, reloadWindow);
	});

	// BUTTON change container order down
	$('.btn-container-order-down').click(function(){
		var preference_campain_section_id = $(this).data('section_id');
		var preference_campain_container_id = $(this).data('container_id');
		var type_order = "down";

		var url = '/campains/wizard/set-preference-campain-container-order';
        var data = "preference_campain_section_id="+preference_campain_section_id+"&preference_campain_container_id="+preference_campain_container_id+"&type_order="+type_order;

		ajaxCall(url, data, reloadWindow);
	});


	// BUTTON change section order up
	$('.btn-section-order-up').click(function(){

		var preference_campain_section_id = $(this).data('section_id');
		var preference_campain_channel_mm_id = $(this).data('preference_campain_channel_mm_id')
		var type_order = "up";

		var url = '/campains/wizard/set-preference-campain-section-order';
        var data = "preference_campain_section_id="+preference_campain_section_id+"&preference_campain_channel_mm_id="+preference_campain_channel_mm_id+"&type_order="+type_order;

		ajaxCall(url, data, reloadWindow);
	});

	// BUTTON change section order down
	$('.btn-section-order-down').click(function(){

		var preference_campain_section_id = $(this).data('section_id');
		var preference_campain_channel_mm_id = $(this).data('preference_campain_channel_mm_id')
		var type_order = "down";

		var url = '/campains/wizard/set-preference-campain-section-order';
		var data = "preference_campain_section_id="+preference_campain_section_id+"&preference_campain_channel_mm_id="+preference_campain_channel_mm_id+"&type_order="+type_order;

		ajaxCall(url, data, reloadWindow);
	});


	// BUTTON delete container
	$('.btn-delete-preference-campain-container').click(function(){
		var preference_campain_container_id = $(this).data('container_id');
		var url = '/campains/wizard/delete-preference-campain-container';
        var data = "preference_campain_container_id="+preference_campain_container_id;

		ajaxCall(url, data, reloadWindow);
	});



	function ajaxCall(url, data, callback){

		$.ajax({
			type: "POST",
			url: url,
			data: data,
			dataType: "html",
			success: function(data){
				// alert(data);
				callback(data);
			},
            error: function(xhr, status, error) {
                alert(error);
                alert(xhr.responseText);
            }
		});

		return true;
	}

	function reloadWindow(data){
		location.reload();
	}


	// BUTTON CONTAINER INSERISCI SEPARATORE 
	$('.btn-new-container-separator').click(function(){
		var section_id = $(this).val();

		// set hidden input preference_campain_section_id 
		$('.preference_campain_section').val(section_id);
	});
	
	// BUTTON CONTAINER INSERISCI CONTENUTO 
	$('.btn-new-container-content').click(function(){
		var section_id = $(this).val();

		// set hidden input preference_campain_section_id 
		$('.preference_campain_section').val(section_id);
	});

	// BUTTON CONTAINER INSERISCI CONTENUTO ESTERNO
	$('.btn-new-external-container-content').click(function(){
		var section_id = $(this).val();

		// set hidden input preference_campain_section_id 
		$('.preference_campain_section').val(section_id);
	});

	
	$('#modal-content-copy-id').on('hidden.bs.modal', function (e) {
		$('#id-btn-modal-copia').removeClass('disabled')
		$('.progress-spinner').attr('hidden', true);
	});

	$("#id-form-copy").submit(function( event ) {
		$('#id-btn-modal-copia').addClass('disabled')
		$('.progress-spinner').removeAttr('hidden');
	});


JS;

$this->registerJs($script);
?>