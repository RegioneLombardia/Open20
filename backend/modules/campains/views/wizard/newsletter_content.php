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
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampainSection;
use backend\modules\campains\models\PreferenceCampainContainer;
use backend\modules\campains\models\PreferenceCampainContainerType;
use backend\modules\campains\utility\CampainsChannelUtility;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\VarDumper;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);


$this->title = Yii::t('amoscore', 'STEP 3', [
    'modelClass' => 'Preference Campain',
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/campains']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Preference Campain'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => strip_tags($model), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('amoscore', 'Campagna');


?>


<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
	            <h3 class="mb-0 pr-2 text-muted text-uppercase"><?=Yii::t('amoscore', 'Nuova campagna: mail (struttura)')?></h3>
        </div>
        <div>
            <div class="step">3</div>
        </div>
    </div>
</div>

<div class="newsletter-content">

    <?php $form = ActiveForm::begin([
            'id' => 'newsletter',
            'options' => [
                'class' => 'needs-validation'
            ]
        ]); 
    ?>  

        <?= $form->errorSummary($model) ?>
        <?php ActiveForm::end(); ?>

        <!-- FORM CONTENT -->
        <?= 
            $this->render('_parts/_form_newsletter_content', [
                'model' => $model,
                'form' => $form,
                'newsletter_content' => $newsletter_content,
                'colorChoiches' => $colorChoiches,
            ])
        ?>
        
        <!-- Wizard Button -->
        <div class="d-flex justify-content-between mt-5">
     
            <?= Html::a('Precedente', ['/campains/wizard/newsletter-data/?id='.$model->id], ['class'=>'btn btn-primary']) ?>
        
            <?php
                if (CampainsUtility::campainHaveSmsChannel($model)):
            ?>
                <?= Html::a('Prosegui', ['/campains/wizard/sms-data/?id='.$model->id], ['class'=>'btn btn-primary']) ?>
            <?php
                elseif (CampainsUtility::campainHaveAppChannel($model)):
            ?>
                <?= Html::a('Prosegui', ['/campains/wizard/app-data/?id='.$model->id], ['class'=>'btn btn-primary']) ?>
            <?php
                else:
            ?>
                <?= Html::a('Prosegui', ['/campains/wizard/summary/?id='.$model->id], ['class'=>'btn btn-primary']) ?>
            <?php
                endif;
            ?>
    
        </div>

 

</div>



<!-- START Modal Nuova Sezione -->
<div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="modalnuovasezione">
	<div class="modal-dialog modal-dialog-right" role="document">
		<div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title">Nuova Sezione
                </h5>

				<?= Html::button(
					'<svg class="icon">
						<use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_close"></use>
					</svg>',
					[
						'class' => 'close',
						'data-dismiss' => "modal",
						'aria-label' => "Close"
					])
				?>
			</div>

            <!-- FORM SECTION -->
            <?php 
                $form = ActiveForm::begin([
                    'options' => ['class' => 'needs-validation'],
                    'action' =>['/campains/wizard/create-newsletter-section?id='.$model->id]
                ]); 
            ?>  
				<div class="modal-body">
                    <!-- CONTENT FORM -->
                    <?= 
                        $this->render('_parts/_form_newsletter_section', [
                            'form' => $form,
                            'model' => new PreferenceCampainSection,
                            'model_preference_campain' => $model,
                            'model_preference_campain_channel_mm' => $model_preference_campain_channel_mm,
                            'newsletter_section_color' => $newsletter_section_color,
                            'newsletter_section_title' => $newsletter_section_title,
                        ])
                    ?>
				</div>
				<div class="modal-footer">
					<div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Salva', ['class' => 'btn btn-primary'])?>
                        </div>
                    </div>
				</div>
            <?php ActiveForm::end(); ?>
            <!-- END FORM SECTION  -->

		</div>
	</div>
</div>
<!-- END Modal Nuova Sezione -->


<!-- START Modal Inserisci Nuovo Contenuto -->
<div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="modalinseriscinuovocontenuto">
    <div class="modal-dialog modal-dialog-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inserisci contenuto</h5>

                <?= Html::button(
                    '<svg class="icon">
                        <use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_close"></use>
                    </svg>',
                    [ 'class' => 'close', 'data-dismiss' => "modal", 'aria-label' => "Close"])
                ?>
            </div>

            <div class="modal-body">

                <!-- START FORM -->
                <?php 
                    $form = ActiveForm::begin([
                        'options' => ['class' => 'needs-validation', 'enctype' => 'multipart/form-data'],
                        'action' =>['/campains/wizard/create-newsletter-container?id='.$model->id]
                    ]); 
                ?>  

                    <!-- CONTENT FORM -->
                    <?php 
                        // set type of PreferenceCampainContainer
                        $preference_campain_container->preference_campain_container_type_id = 
                            CampainsUtility::preferenceCampainContainerTypeByName('Contenuto')->id;
                    ?>
                    <?= 
                        $this->render('_parts/_form_newsletter_container_content', [
                            'form' => $form,
                            'model' => $preference_campain_container,
                            'model_preference_campain' => $model,
                            'preference_campain_section' => $preference_campain_section,
                            'type_content_align' => $type_content_align,
                            'aspectRatioChoices' => $aspectRatioChoices,
                            'aspectRatio' => $aspectRatio,
                        ])
                    ?>
                    
                    <!-- 
                        TODO GRAFICA : 
                            * capire del perche àl'apertura del form prima del nmodal-body e la sua chiusura dopo il footer
                         crea problemi grafici di visualizzazione  
                            * modal-footer è stato spostato all'inteno del modal-body sistemare secondo le convenzioni del bootstrap
                    -->
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::submitButton('Salva', ['class' => 'btn btn-primary'])?>
                            </div>
                        </div>
                    </div>
                                
                <?php ActiveForm::end(); ?>
                <!-- END FORM -->

            </div>
            
        </div>
    </div>
</div>
<!-- END Modal inserisci nuovo contenuto -->


<!-- START Modal Inserisci Nuovo Contenuto Esterno -->
<div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="modalinseriscinuovocontenutoesterno">
    <div class="modal-dialog modal-dialog-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inserisci contenuto esterno</h5>
                    <!-- 
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <svg class="icon">
                                <use xlink:href="/bootstrap-italia/dist/svg/sprite.svg#ic_close"></use>
                            </svg>
                        </button> 
                    -->
                    <?= Html::button(
                        '<svg class="icon">
                            <use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_close"></use>
                        </svg>',
                        [ 'class' => 'close', 'data-dismiss' => "modal", 'aria-label' => "Close"])
                    ?>
            </div>
            <div class="modal-body">

                <!-- START FORM -->
                <?php 
                    $form = ActiveForm::begin([
                        'options' => ['class' => 'needs-validation'],
                        'action' =>['/campains/wizard/create-newsletter-container?id='.$model->id]
                    ]); 
                ?>  

                    <!-- FORM CONTENT -->
                    <?php 
                        // set type of PreferenceCampainContainer
                        $preference_campain_container->preference_campain_container_type_id = 
                            CampainsUtility::preferenceCampainContainerTypeByName('Contenuto Esterno')->id;
                    ?>
                    <?= 
                        $this->render('_parts/_form_newsletter_external_content', [
                            'form' => $form,
                            'model' => $preference_campain_container,
                            'model_preference_campain' => $model,
                            'external_source' => $external_source,
                            'type_content_align' => $type_content_align,
                            'aspectRatioChoices' => $aspectRatioChoices,
                            'aspectRatio' => $aspectRatio,
                        ])
                    ?>

                    <div class="modal-footer">
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <?= Html::submitButton('Inserisci', ['class' => 'btn btn-primary'])?>
                            </div>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>
                <!-- END FORM -->
            </div>
        </div>
    </div>
</div>
<!-- END Modal Inserisci Nuovo Contenuto Esterno -->


<!-- START Modal Inserisci Separatore -->
<div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="modalinseriscinuovoseparatore">
    <div class="modal-dialog modal-dialog-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Nuovo Separatore
                </h5>


                <?= Html::button(
					'<svg class="icon">
						<use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_close"></use>
					</svg>',
					[
						'class' => 'close',
						'data-dismiss' => "modal",
						'aria-label' => "Close"
					])
				?>
            </div>

            <div class="modal-body">

                <!-- START FORM -->
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'newsletter-separator',
                        'options' => ['class' => 'needs-validation'],
                        'action' =>['/campains/wizard/create-newsletter-container?id='.$model->id]
                    ]); 
                ?>  

                    <!-- FORM CONTENT -->
                    <?php 
                        // set type of PreferenceCampainContainer
                        $preference_campain_container->preference_campain_container_type_id = 
                            CampainsUtility::preferenceCampainContainerTypeByName('Separatore')->id;
                    ?>
                    <?= 
                        $this->render('_parts/_form_newsletter_container_separator', [
                            'form' => $form,
                            'model' => $preference_campain_container,
                            'preference_campain' => $model,
                            'newsletter_section_color' => $newsletter_section_color
                        ])
                    ?>

                    <div class="modal-footer">
                        <!-- <button class="btn btn-primary btn-sm" type="button">Ok</button> -->
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <?= Html::submitButton('Salva', ['class' => 'btn btn-primary'])?>
                            </div>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>
                <!-- END FORM -->

            </div>
        </div>
    </div>
</div>
<!-- END Modal Inserisci Separatore -->





<!-- START MODALS UPDATE SECTION -->
<?php foreach ($newsletter_content as $content) : ?>

    <!-- START modal UPDATE SECTION -->
    <div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="modal-section-<?= $content['section']->id ?>">
        <div class="modal-dialog modal-dialog-right" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuova Sezione
                    </h5>

                    <?= Html::button(
                        '<svg class="icon">
                            <use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_close"></use>
                        </svg>',
                        [
                            'class' => 'close',
                            'data-dismiss' => "modal",
                            'aria-label' => "Close"
                        ])
                    ?>
                </div>

                <!-- FORM SECTION -->
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'newsletter-section',
                        'options' => ['class' => 'needs-validation'],
                        'action' =>['/campains/wizard/create-newsletter-section?id='.$model->id]
                    ]); 
                ?>  
                    <div class="modal-body">
                        <!-- CONTENT FORM -->
                        <?= 
                            $this->render('_parts/_form_newsletter_section', [
                                'form' => $form,
                                'model' => $content['section'],
                                'model_preference_campain' => $model,
                                'model_preference_campain_channel_mm' => $model_preference_campain_channel_mm,
                                'newsletter_section_color' => $newsletter_section_color,
                                'newsletter_section_title' => $newsletter_section_title,
                            ])
                        ?>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::submitButton('Salva', ['class' => 'btn btn-primary'])?>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                <!-- END FORM SECTION  -->

            </div>
        </div>
    </div>
    <!-- END modal UPDATE SECTION -->

    <!-- START modal UPDATE CONTAINER-->
    <?php foreach ($content['container'] as $container) : ?>

        <!-- START Modal Container -->
        <div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="modal-container-<?= $container->id ?>">
            <div class="modal-dialog modal-dialog-right" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?php if( $container->preference_campain_container_type_id == 
                                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto')->id ) : ?>

                                <?= "Inserisci contenuto" ?>

                            <?php elseif( $container->preference_campain_container_type_id == 
                                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto Esterno')->id ) : ?>

                                <?= "Inserisci contenuto esterno" ?>

                            <?php elseif( $container->preference_campain_container_type_id ==  
                                        CampainsUtility::preferenceCampainContainerTypeByName('Separatore')->id ) : ?>

                                <?= "Inserisci contenuto esterno"; ?>
                            <?php endif; ?>
                        </h5>

                        <?= Html::button(
                            '<svg class="icon">
                                <use xlink:href="'.$spriteAsset->baseUrl.'/material-sprite.svg#ic_close"></use>
                            </svg>',
                            [ 'class' => 'close', 'data-dismiss' => "modal", 'aria-label' => "Close"])
                        ?>
                    </div>

                    <div class="modal-body">

                        <!-- START FORM -->
                        <?php 
                            $form = ActiveForm::begin([
                                'options' => ['class' => 'needs-validation', 'enctype' => 'multipart/form-data'],
                                'action' =>['/campains/wizard/create-newsletter-container?id='.$model->id]
                            ]); 
                        ?>  

                            <?php 
                                /** START FORM CONTENT */

                                // CONTAINER TYPE Contenuto
                                if( $container->preference_campain_container_type_id == 
                                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto')->id ){

                                    echo $this->render('_parts/_form_newsletter_container_content', [
                                        'form' => $form,
                                        'model' => $container,
                                        'model_preference_campain' => $model,
                                        'type_content_align' => $type_content_align,
                                        'aspectRatioChoices' => $aspectRatioChoices,
                                        'aspectRatio' => $aspectRatio,
                                    ]);
                                }

                                // CONTAINER TYPE Contenuto Esterno
                                if( $container->preference_campain_container_type_id == 
                                    CampainsUtility::preferenceCampainContainerTypeByName('Contenuto Esterno')->id ){

                                    echo $this->render('_parts/_form_newsletter_external_content', [
                                        'form' => $form,
                                        'model' => $container,
                                        'model_preference_campain' => $model,
                                        'external_source' => $external_source,
                                        'type_content_align' => $type_content_align,
                                        'aspectRatioChoices' => $aspectRatioChoices,
                                        'aspectRatio' => $aspectRatio,
                                    ]);
                                }
                                
                                // CONTAINER TYPE Separatore
                                if( $container->preference_campain_container_type_id ==  
                                        CampainsUtility::preferenceCampainContainerTypeByName('Separatore')->id ){
                                                                        
                                    echo $this->render('_parts/_form_newsletter_container_separator', [
                                        'form' => $form,
                                        'model' => $container,
                                        'model_preference_campain' => $model,
                                        'newsletter_section_color' => $newsletter_section_color
                                    ]);
                                }

                                /** END FORM CONTENT */
                            ?>
                            
                            <div class="modal-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= Html::submitButton('Salva', ['class' => 'btn btn-primary'])?>
                                    </div>
                                </div>
                            </div>
                                        
                        <?php ActiveForm::end(); ?>
                        <!-- END FORM -->

                    </div>
                </div>
            </div>
        </div>
        <!-- END Modal Container -->
   
    <?php endforeach; ?>
    <!-- END modal UPDATE CONTAINER -->


<?php endforeach; ?>    
<!-- END MODALS UPDATE SECTION -->





