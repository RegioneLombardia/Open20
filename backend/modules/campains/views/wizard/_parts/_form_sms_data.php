<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use backend\modules\campains\Module;
use yii\widgets\ActiveField;
use yii\bootstrap\Button;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>

<div class="preferences-center">

	<div class="row">
		<div class="col-md-12">
			<div class="py-4 d-inline-flex w-100 title-substeps affix-top">
				<!-- TODO GRAFICA: cambiare icona con quella mostrata nella pagina 86 fig 46 delle specifiche -->
				<svg class="icon mr-2">
					<use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_mail"></use>
				</svg>

				<h5 class="font-weight-bold ">
					<?= Module::t('amosevents', "Gestione dei contenuti SMS") ?>
				</h5>
			</div>

			<div class="row variable-gutters">
				<div class="col-md-3 text-muted">
					<p>
						Componi il testo da inviare via SMS nella campagna di comunicazione
					</p>
				</div>
				<div class="col-md-8 ml-auto">
					<?php
						if (empty($model->sms_text) && \Yii::$app->getSession()->has('preference-sms-text')){
							$model->sms_text = \Yii::$app->getSession()['preference-sms-text'];
						}	
						
						\Yii::$app->getSession()->remove('preference-sms-text');
					?>

					<?= $form->field($model, 'sms_text')->textArea([
						'rows' => '5',
						'maxlength' => '160',
						'id' => 'sms-text-id',
						'placeholder' => 'SMS (max 160 caratteri)'
					])->label(false) ?>
					<small id="length_text_textarea" class="form-text text-muted text-right">160</small>




					<div class="row">
						<div class="col-8">
							

							<!-- TODO GRAFICA: mask per i numeri telefonici -->
							<?= $form->field($model, 'phone_number')->textInput([
								'maxlength' => '11',
								'placeholder' => Module::t('amosevents', 'Numero di telefono'),
								'id' => 'phone-number-id'

							])->label('Invia Test a:') ?>
						</div>
						<div class="col-4 d-flex">
							<!-- TODO invio in seguito all'arrivo delle api operatore telefonico -->
							

								<?= 
									Html::a(
										'<svg class="icon icon-white">
											<use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_send"></use>
										</svg> <span>Invia</span>',
										['#'], ['class' => 'btn btn-secondary btn-xs btn-icon mt-auto mb-4', 'id' => 'send-sms-id']
									) 
								?>
						</div>
					</div>

				</div>
			</div>
		</div>


	</div>

</div>




<?php

/** CONTENT PAGE SCRIPTS */

$script = <<< JS

	// phone number input only numeric 
	$("#phone-number-id").keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});

	// send email test
	$('#send-sms-id').click(function(){
		event.preventDefault();

		var url = '/campains/preference-campain/send-test-sms';
		var phone = $('#phone-number-id').val();
		var text = $('#sms-text-id').val();

        var data = {
			'text': '' + text,
			'phone': '' + phone ,
		}

		$.ajax({
			type: "POST",
			url: url,
			data: data,
			dataType: "html",
			success: function(data){
				if (data == 'true') {
					// possibile gestione diversa per l'invio corretto...
					location.reload();
				} else {
					location.reload();
				}
			},
            error: function(xhr, status, error) {
                alert(error);
                alert(xhr.responseText);
            }

		});
	});


	// counts the number of characters in the textarea
	$('#sms-text-id').keyup(function(){
		// TODO da aggiungere filtro client per i caratteri inseriti nella text area (fornito da operatore SMS )
		$('#length_text_textarea').text(160 - $('#sms-text-id').val().length);
	});

	// replace the character, just the number
	$('#preferencecampainchannelmm-phone_number').keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
JS;

$this->registerJs($script);
?>