<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\sondaggi\models\SondaggiMap;
use open20\amos\core\forms\TextEditorWidget;

ModuleSondaggiAsset::register($this);

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomande $model
 * @var yii\widgets\ActiveForm $form
 */
$registrazione = true;
if ((empty($model->getSondaggi()->one()['frontend']) && empty($model->getSondaggi()->one()['abilita_registrazione'])) || !in_array($model->sondaggi_domande_tipologie_id,
        [5, 6])) {
    $registrazione = false;
}

$sondaggiModule = AmosSondaggi::instance();

$sondaggioLive = ($model->getSondaggi()->one()->sondaggio_type == \open20\amos\sondaggi\models\base\SondaggiTypes::getLiveType());

$js = <<< JS
       /* setTimeout(
        function()
        {           */
        $("#ordina_dopo").prop("disabled", true);
       /* }, 1200);                        */
JS;
$this->registerJs($js, yii\web\View::POS_LOAD);
//sondaggidomande-sondaggi_domande_tipologie_id

$js2 = <<<JS
    var front = $('#sondaggidomande-sondaggi_domande_tipologie_id').val();
    if(front == 5 || front == 6 | front == 13){
        $('#sondaggidomande-sondaggi_map_id').prop('disabled', false);
        $('#anagrafica-abilitata').show();
        $('#sondaggi_validazione-id').prop('disabled', false);
    } else {
        $('#sondaggidomande-sondaggi_map_id').prop('disabled', true);
        $('#anagrafica-abilitata').hide();
        $('#sondaggi_validazione-id').prop('disabled', true);
    }

    $('#sondaggidomande-sondaggi_domande_tipologie_id').on("change", function() {
        var front = $('#sondaggidomande-sondaggi_domande_tipologie_id').val();
         if(front == 5 || front == 6 || front == 13){
        $('#sondaggidomande-sondaggi_map_id').prop('disabled', false);
        $('#anagrafica-abilitata').show();
    $('#sondaggi_validazione-id').prop('disabled', false);
    } else {
        $('#sondaggidomande-sondaggi_map_id').prop('disabled', true);
        $('#anagrafica-abilitata').hide();
    $('#sondaggi_validazione-id').prop('disabled', true);
    }
   });
JS;



$this->registerJs($js2, yii\web\View::POS_READY);
?>

<div class="sondaggi-domande-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row sondaggio-row">
        <div class="col-sm-12">
            <?=
            $form->field($model, 'introduzione')->widget(TextEditorWidget::className(),
                [
                'clientOptions' => [
                    'placeholder' => AmosSondaggi::t('amossondaggi',
                        'Qui si può inserire una descrizione introduttiva alla domanda, essa sarà sempre visibile'),
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ])
            ?>
        </div>
        <div class="col-sm-12">
            <?=
            $form->field($model, 'introduzione_condizionata')->widget(TextEditorWidget::className(),
                [
                'clientOptions' => [
                    'placeholder' => AmosSondaggi::t('amossondaggi',
                        'Qui si può inserire una descrizione introduttiva alla domanda, sarà vincolata alla presenza della stessa'),
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ])
            ?>
        </div>
        <div class="col-sm-12">
            <?= $form->field($model, 'domanda', ['options' => ['class' => 'nom']])->textarea(['rows' => 4]) ?>
        </div>
        <div class="col-sm-12">
            <?= $form->field($model, 'tooltip', [])->textarea(['rows' => 3]) ?>
        </div>

        <?php if (!$sondaggioLive) { ?>
            <div class="col-sm-6">
                <!--                <label>Opzioni</label>-->
                <?= $form->field($model, 'obbligatoria')->checkbox() ?>
            </div>
            <?php if ($sondaggiModule->enableCriteriValutazione) { ?>
                <div class="col-sm-6">
                    <?=
                    $form->field($model, 'domanda_per_criteri')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
                        1 => AmosSondaggi::t('amossondaggi', 'Si')])
                    ?>
                </div>
            <?php } ?>
        <?php } ?>
        <?php if ($registrazione) { ?>
            <div class="col-lg-12 col-sm-12" id="anagrafica-abilitata">
                <?=
                $form->field($model, 'sondaggi_map_id')->dropDownList(ArrayHelper::map(SondaggiMap::find()->all(), 'id',
                        'descrizione'), ['prompt' => AmosSondaggi::t('amossondaggi', 'Selezionare ...')]);
                ?>
            </div>
        <?php } ?>

    </div>

    <?php if (!$sondaggioLive) { ?>

        <div class="row sondaggio-row">
            <?php if ($model->sondaggi_id) : ?>
                <div class="col-xs-12">
                    <?=
                    $form->field($model, 'ordine', ['options' => ['class' => 'nom']])->inline()->radioList(['inizio' => AmosSondaggi::t('amossondaggi',
                            "All'inizio"), 'fine' => AmosSondaggi::t('amossondaggi', 'Alla fine'), 'dopo' => AmosSondaggi::t('amossondaggi',
                            'Dopo la seguente domanda')], ['id' => 'ordinamento-radio'])->label(AmosSondaggi::t('amossondaggi',
                            'Posiziona la domanda').':')
                    ?>
                </div>
                <!--?= $form->field($model, 'ordina_dopo')->dropDownList(ArrayHelper::map($model->getTutteDomandeSondaggio()->all(), 'id', 'domanda'), ['id' => 'ordina-dopo'])->label(''); ?-->
                <div class="col-xs-12">
                    <?=
                    $form->field($model, 'ordina_dopo')->widget(DepDrop::classname(),
                        [
                        //'type' => DepDrop::TYPE_SELECT2,
                        'data' => $model->getDomandaPrecedente() ? [$model->getDomandaPrecedente() => open20\amos\sondaggi\models\SondaggiDomande::findOne($model->getDomandaPrecedente())->domanda]
                                : [],
                        'options' => ['id' => 'ordina_dopo'],
                        //'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                        'pluginOptions' => [
                            'depends' => ['sondaggi_domande_pagine_id-id'],
                            'placeholder' => ['Seleziona ...'],
                            'url' => Url::to(['/'.$this->context->module->id.'/ajax/domande-by-pagine']),
                            'initialize' => true,
                            'params' => ['ordina_dopo'],
                        ],
                    ])->label(false);
                    ?>
                </div>
            <?php else: ?>
                <div class="col-xs-12 nom">
                    <?=
                    $form->field($model, 'ordine')->inline()->radioList(['inizio' => 'All\'inizio', 'fine' => 'Alla fine'])->label(AmosSondaggi::t('amossondaggi',
                            'Posiziona la domanda').':')
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php } ?>
    <div class="row sondaggio-row">
        <div class="col-lg-6 col-sm-6">
            <?php if ($sondaggioLive) { ?>
                <?=
                // generated by schmunk42\giiant\crud\providers\RelationProvider::activeField
                $form->field($model, 'sondaggi_domande_tipologie_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\open20\amos\sondaggi\models\SondaggiDomandeTipologie::find()->andWhere([
                            'attivo' => 1])->andWhere(['in', 'id', [1, 2, 3, 4]])->all(), 'id', 'tipologia'),
                    ['prompt' => AmosSondaggi::t('amossondaggi', 'Seleziona il tipo di risposta ...')]
                );
                ?>
            <?php } else { ?>
                <?=
                // generated by schmunk42\giiant\crud\providers\RelationProvider::activeField
                $form->field($model, 'sondaggi_domande_tipologie_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\open20\amos\sondaggi\models\SondaggiDomandeTipologie::find()->andWhere([
                            'attivo' => 1])->all(), 'id', 'tipologia'),
                    ['prompt' => AmosSondaggi::t('amossondaggi', 'Seleziona il tipo di risposta ...')]
                );
                ?>
            <?php } ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?=
            $form->field($model, 'inline')->dropDownList([0 => AmosSondaggi::t('amossondaggi',
                    'In colonna (una sotto l\'altra)'), 1 => AmosSondaggi::t('amossondaggi',
                    'In linea (sulla stessa linea)')]);
            ?>
        </div>
    </div>
    <?php if (!$sondaggioLive) { ?>
        <div class="row" id="abilita-ordinamento-risposte">
            <div class="col-lg-6">
                <?=
                $form->field($model, 'abilita_ordinamento_risposte')->dropDownList([
                    0 => AmosSondaggi::t('amossondaggi', 'Nessun ordinamento'),
                    1 => AmosSondaggi::t('amossondaggi',
                        'Sì, risposte ordinabili con la funzione "Gestisci Risposte"')
                ]);
                ?>
            </div>
            <div class="col-lg-6">
                <?=
                $form->field($model, 'validazione')->widget(Select2::classname(),
                    [
                    'data' => ArrayHelper::map(\open20\amos\sondaggi\models\SondaggiDomandeRule::find()->orderBy('nome')->asArray()->all(),
                        'id', 'nome'),
                    'options' => [
                        'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona ...'),
                        'id' => 'sondaggi_validazione-id',
                        'disabled' => true,
                        'multiple' => true,
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="row" id="selezione-classe-validatrice">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'nome_classe_validazione')->textInput(); ?>
            </div>
        </div>
    <?php } ?>

    <div class="row" id="selezione-modello">
        <div class="col-lg-12 col-sm-12">
            <?=
            $form->field($model, 'modello_risposte_id')->widget(Select2::classname(),
                [
                'data' => ArrayHelper::map(\open20\amos\sondaggi\models\SondaggiModelliPredefiniti::find()->orderBy('classname')->asArray()->all(),
                    'id', 'classname'),
                'options' => [
                    'placeholder' => AmosSondaggi::t('amossondaggi', 'Seleziona ...'),
                    'id' => 'modello-id',
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row" id="selezioni-minime-massime-label">
        <div class="col-lg-12 col-sm-12">
            <?php
            $model->min_int_multipla = ($model->min_int_multipla) ? $model->min_int_multipla : 0;
            $model->max_int_multipla = ($model->max_int_multipla) ? $model->max_int_multipla : 0;
            ?>
            <p><i><?=
                    AmosSondaggi::t('amossondaggi',
                        'Selezioni minime e/o massime se impostate a "0" (vuol dire senza limiti) non avranno nessuno effetto.')
                    ?></i></p>
        </div>
    </div>
    <div class="row" id="selezioni-minime-massime">
        <div class="col-lg-6 col-sm-6">
            <?=
            $form->field($model, 'min_int_multipla')->textInput();
            ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?=
            $form->field($model, 'max_int_multipla')->textInput();
            ?>
        </div>
    </div>

    <?php
    $hide                    = '';
    if ($sondaggioLive) {
        $hide = 'display:none;';
    }
    ?>

    <div class="row" style="<?= $hide ?>">
        <div class="col-lg-6 col-sm-6">
            <?php
            if ($model->sondaggi_id) :
                $model->sondaggi_id = $model->sondaggi_id;
                ?>
                <?=
                $form->field($model, 'sondaggi_id')->widget(Select2::classname(),
                    [
                    'data' => ArrayHelper::map(\open20\amos\sondaggi\models\Sondaggi::find()->asArray()->all(),
                        'id', 'titolo'),
                    'options' => ['placeholder' => AmosSondaggi::t('amossondaggi', 'Digita il nome del sondaggio ...'), 'id' => 'sondaggi_id-id',
                        'disabled' => TRUE],
                ]);
                ?>
                <?= $form->field($model, 'sondaggi_id')->hiddenInput()->label(FALSE); ?>
            <?php else : ?>
                <?=
                $form->field($model, 'sondaggi_id')->widget(Select2::classname(),
                    [
                    'data' => ArrayHelper::map(\open20\amos\sondaggi\models\Sondaggi::find()->asArray()->all(),
                        'id', 'titolo'),
                    'options' => ['placeholder' => AmosSondaggi::t('amossondaggi', 'Digita il nome del sondaggio ...'), 'id' => 'sondaggi_id-id'],
                ]);
                ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?=
            $form->field($model, 'sondaggi_domande_pagine_id')->widget(DepDrop::classname(),
                [
                //'type' => DepDrop::TYPE_SELECT2,
                'data' => $model->sondaggi_domande_pagine_id ? [$model->getSondaggiDomandePagine()->one()->id => $model->getSondaggiDomandePagine()->one()->titolo]
                        : [],
                'options' => ['id' => 'sondaggi_domande_pagine_id-id'],
                //'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['sondaggi_id-id'],
                    'placeholder' => ['Seleziona ...'],
                    'url' => Url::to(['/'.$this->context->module->id.'/ajax/pagine-by-sondaggio']),
                    'initialize' => true,
                    'params' => ['sondaggi_domande_pagine_id-id'],
                ],
            ]);
            ?>
        </div>
    </div>

    <?php if (!$sondaggioLive) { ?>
        <div class="row">

            <div class="col-lg-12 col-sm-12">
                <label></label>
                <?= $form->field($model, 'domanda_condizionata')->checkbox(['id' => 'domcond']) ?>
            </div>
            <div class="col-lg-12 col-sm-12">
                <?php
                if ($model->domanda_condizionata) :
                    $model->condizione_necessaria = $model->getSondaggiRispostePredefinitesCondizionate()->all();
                endif;
                ?>
                <?=
                $form->field($model, 'condizione_necessaria')->dropDownList(
                    yii\helpers\ArrayHelper::map($model->getTutteDomandeDellePagine()->all(), 'id', 'risposta',
                        'domanda'),
                    ['prompt' => AmosSondaggi::t('amossondaggi',
                        'Seleziona la risposta o le risposte a cui condizionare la domanda'), 'id' => 'condizione_necessaria-id',
                    'multiple' => true, 'size' => $model->getTutteDomandeDellePagine()->count() + 5]
                )
                ?>
                <?php /*
                  $form->field($model, 'condizione_necessaria')->widget(DepDrop::classname(), [
                  'type' => DepDrop::TYPE_SELECT2,
                  'data' => $model->domanda_condizionata ? [$model->getSondaggiRispostePredefinitesCondizionate()->one()['id'] => $model->getSondaggiRispostePredefinitesCondizionate()->one()['risposta']] : [],
                  'options' => ['id' => 'condizione_necessaria-id', 'enabled' => TRUE],
                  'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                  'pluginOptions' => [
                  'depends' => ['sondaggi_id-id'],
                  'placeholder' => ['Seleziona ...'],
                  'url' => Url::to(['/' . $this->context->module->id . '/ajax/condizione-by-risposta']),
                  'initialize' => true,
                  'params' => ['sondaggi_id-id'],
                  ],
                  ]);
                 */ ?>
            </div>

        </div>
        <div class="row">

            <div class="col-lg-12">
                <?=
                $form->field($model, 'domanda_condizionata_testo_libero')->dropDownList(
                    yii\helpers\ArrayHelper::map($model->getTutteDomandeLibere()->orderBy('ordinamento')->all(), 'id',
                        'domanda'),
                    ['prompt' => AmosSondaggi::t('amossondaggi', 'Selezionare, questa selezione scarterà la precedente'),
                    'id' => 'condizione_necessaria-libera-id']
                )
                ?>
            </div>
        </div>
    <?php } ?>

    <div class="col-lg-6 col-sm-6">

    </div>
    <div class="clearfix"></div>

    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <div id="form-actions" class="bk-btnFormContainer">
        <?php if (isset($model->sondaggi_id) && isset($model->sondaggi_domande_pagine_id) && !$url) : ?>
            <?=
            Html::submitButton(AmosSondaggi::tHtml('amossondaggi', 'Inserisci e vai a nuova pagina'),
                ['class' => 'btn btn-success', 'id' => 'submit-pagina', 'name' => 'pagina']);
            ?>
            <?=
            CloseSaveButtonWidget::widget([
                'model' => $model,
                'buttonNewSaveLabel' => $model->isNewRecord ? AmosSondaggi::t('amossondaggi', 'Inserisci') : AmosSondaggi::t('amossondaggi',
                        'Salva'),
                'closeButtonLabel' => AmosSondaggi::t('amossondaggi', 'Chiudi'),
            ]);
            ?>
        <?php else : ?>
            <?=
            CloseSaveButtonWidget::widget([
                'model' => $model,
                'buttonNewSaveLabel' => $model->isNewRecord ? AmosSondaggi::t('amossondaggi', 'Inserisci') : AmosSondaggi::t('amossondaggi',
                        'Salva'),
                'closeButtonLabel' => AmosSondaggi::t('amossondaggi', 'Chiudi'),
            ]);
            ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
