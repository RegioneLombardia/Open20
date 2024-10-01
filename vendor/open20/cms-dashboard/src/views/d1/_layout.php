<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\cms\dashboard\Module;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use open20\amos\core\helpers\Html;
use open20\amos\attachments\components\CropInput;

$this->title = Module::txt('Crea un nuovo modello di pagina');
$this->params['forceBreadcrumbs'][] = ['label' => Module::txt('Modelli'), 'url' => ['/' . Module::getModuleName() . '/d1/modelli']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumb'][] = ['label' => $this->title];
?>
<div class="dashboards-page-create">
    <p class="lead"><?= Module::t('amosdashboards', 'I modelli di pagina sono layout preconfigurati: ti aiutano a comporre più rapidamente le pagine utilizzando i componenti a disposizione all’interno di schemi ricorrenti.') ?></p>
    <div class="dashboards-page-form">

        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        ?>

        <div class="row">

            <div class="col-xs-12">
                <h2 class="subtitle-form m-t-30"><?= Module::txt('Informazioni di base') ?></h2>
            </div>

            <div class="col-xs-12">
                <?= $form->field($model, 'title')->textInput()->hint(Module::txt('Il nome con cui verrà salvato il modello di pagina')); ?>
            </div>
            <?php if ($model->isNewRecord) { ?>
                <div class="col-xs-12">
                    <?=
                    $form->field($model, 'clone')->widget(Select2::class, [
                        'data' => ArrayHelper::map($menuQuery->all(), 'nav0', 'title'),
                        'options' => ['placeholder' => Module::txt('Modello di pagina vuoto'), 'id' => 'select-copy'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'templateResult' => new \yii\web\JsExpression("function formatState (state) {                                
                                tree = state.text;    
                                    newTree = tree.replace(/\ \ /g, '&nbsp;&nbsp;&nbsp;');
                                    if(newTree == tree){
                                        return tree;
                                    } else {
                                    newTree = '<span class=\" d-flex \">' + tree.replace(/\ \ /g, '&nbsp;&nbsp;&nbsp;@') + '</span>';     
                                    var lastIndex = newTree.lastIndexOf('@');
                                    newTree = newTree.slice(0, lastIndex) + '<span class=\"mdi mdi-subdirectory-arrow-right m-r-10 \"></span>' + newTree.slice(lastIndex + 1);                           
                                    newTree = newTree.replace(/@/g, '&nbsp;&nbsp;&nbsp;')
                                    newTree2 = $(newTree);
                                    return newTree2;
                                }
                              }
                            "),
                        ],
                    ])->label(Module::txt('Modalità di creazione'))->hint(Module::txt('Seleziona una pagina esistente da cui verrà copiato il contenuto per creare il tuo modello, oppure lascia "Modello di pagina vuoto"'));
                    ?>
                </div>
            <?php } ?>
            <div class="col-xs-12">                              

                <?= $form->field($modelimage, 'seo_image')->widget(CropInput::class, ['hidePreviewDeleteButton' => $hidePreviewDeleteButton, 'jcropOptions' => ['aspectRatio' => '']])->label(Module::txt('Anteprima modello'))->hint(Module::t('amosdashboards', 'Immagine visualizzata nelle anteprime del modello')) ?>

            </div>

        </div>

        <div class="clearfix"></div>
        <div class="col-xs-12 note_asterisk nop">
            <p><?= Module::txt('I campi <span class="red">*</span> sono obbligatori.') ?></p>
        </div>

        <?=
        CloseSaveButtonWidget::widget([
            'model' => $model,
            'buttonNewSaveLabel' => Module::txt('Personalizza layout'),
            'closeButtonLabel' => Module::txt('Indietro'),
            'urlClose' => ['/' . \open20\cms\dashboard\Module::getModuleName() . '/d1/modelli'],
        ]);
        ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="clearfix"></div>
</div>