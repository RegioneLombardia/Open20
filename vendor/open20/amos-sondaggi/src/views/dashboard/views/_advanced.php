<?php

use open20\amos\core\helpers\Html;
use open20\amos\sondaggi\AmosSondaggi;
?>
<div class="row">
    <div class="col-md-12">
        <?php
        echo Html::tag(
            'div', $form->field($model, 'send_pdf_to_compiler')->checkbox(), ['class' => 'col-sm-12']
        );

        echo Html::tag(
            'div', $form->field($model, 'send_pdf_via_email')->checkbox(), ['class' => 'col-sm-12']
        );

        echo Html::tag(
            'div',
            $form->field($model, 'additional_emails')->textarea(['placeholder' => 'email1@example.it; email2@example.it; email3@example.it'])
                ->label(AmosSondaggi::t('amossondaggi', '#compiled_poll_emails_option')), ['class' => 'col-sm-12']
        );

        echo Html::tag(
            'div', $form->field($model, 'send_pdf_via_email_closed')->checkbox(), ['class' => 'col-sm-12']
        );
        echo '<hr/>';
        echo $form->field($model, 'url_chiudi_sondaggio')->textInput();


        if (empty($model->compilazioni_disponibili)) $model->compilazioni_disponibili = 1;

        echo Html::tag(
            'div', $form->field($model, 'compilazioni_disponibili')->textInput(['disabled' => true]),
            ['class' => 'container-not-live']
        );
        ?>
    </div>
    <?php
    if (!$sondaggiModule->forceOnlyFrontend) { // Se non è settata l'intenzione di forzare il campo frontend forzatamente a 1
        if ($sondaggiModule->enableFrontendCompilation) {
            ?>
            <div class="col-md-12"><hr/>
                <?=
                $form->field($model, 'frontend')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'), 1 => AmosSondaggi::t('amossondaggi',
                        'Si')]);
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>
<div class="row" id="compilabile_in_frontend">
    <div class="col-md-6">
        <?= $form->field($model, 'thank_you_page')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'url_sondaggio_non_compilabile')->textInput() ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'thank_you_page_sondaggio_chiuso')->textInput() ?>
    </div>
    <div class="col-md-12">
        <?=
        $form->field($model, 'abilita_registrazione')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
            1 => AmosSondaggi::t('amossondaggi', 'Si')])
        ?>
    </div>
    <div class="col-md-12">
        <?php
        $moduleTranslation = \Yii::$app->getModule('translation');
        //$languages         = ['it-IT' => 'IT', 'en-GB' => 'EN'];

        if (!empty($moduleTranslation)) {
            $arrLanguage = (new \yii\db\Query())->from('language')->andWhere(['=', 'status', 1])->select(['language_id',
                    'name'])->all();
            foreach ($arrLanguage as $k => $v) {
                $languages[$v['language_id']] = $v['name'];
            }
        }
        ?>
        <?=
        $form->field($model, 'forza_lingua')->dropDownlist($languages,
            ['prompt' => AmosSondaggi::t('amossondaggi', 'Non forzare. Usa quella dell\'utente')]);
        ?>
    </div>
     <?php if ($sondaggiModule->enableForceLanguageByGet) { ?>
        <div class="col-md-12">
        <?=
            $form->field($model, 'use_get_language')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
                1 => AmosSondaggi::t('amossondaggi', 'Si')])
            ?>
        </div>
        <?php } ?>
        <?php if ($sondaggiModule->enableExtraFieldByGet) { ?>
        <div class="col-md-12">
            <?=
            $form->field($model, 'field_extra')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
                1 => AmosSondaggi::t('amossondaggi', 'Si')])
            ?>
        </div>
        <?php } ?>
</div>

<?php if ($sondaggiModule->enableCriteriValutazione) { ?>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'abilita_criteri_valutazione')->dropDownlist([0 => AmosSondaggi::t('amossondaggi', 'No'),
                1 => AmosSondaggi::t('amossondaggi', 'Si')], ['id' => 'abilita_criteri_valutazione-id'])
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'n_max_valutatori')->textInput(['id' => 'n_max_valutatori-id']) ?>
        </div>
    </div>
<?php } ?>