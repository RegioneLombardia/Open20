<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */
$this->title = AmosSondaggi::t('amossondaggi', 'Sondaggio di gradimento');
?>
<div class="container">
    <nav role="navigation" aria-label="breadcrumbs" aria-labelledby="bc-title" id="bc">
        <h5 id="bc-title" class="vis-off"><?= AmosSondaggi::tHtml('amossondaggi', 'Sei qui') ?>:</h5>
        <ol class="breadcrumb">
            <li><a href="/site/index"><?= AmosSondaggi::tHtml('amossondaggi', 'Home') ?></a></li>
            <?php if (isset($id) && isset($attivita)): ?>
                <li>
                    <a href="/<?= $this->context->module->id ?>/pubblicazione/sondaggio-pubblico-attivita"><?= AmosSondaggi::tHtml('amossondaggi', 'Sondaggio di gradimento') ?></a>
                </li>
                <li class="active"><?= frontend\modules\attivitaformative\models\PeiAttivitaFormative::findOne(['codice_attivita' => $attivita])->titolo; ?></li>
            <?php else: ?>
                <li><?= AmosSondaggi::tHtml('amossondaggi', 'Sondaggio di gradimento') ?></li>
            <?php endif; ?>
        </ol>
    </nav>

</div>
<main role="main" id="mainContent">
    <div class="container">
        <div class="page" role="contentinfo">
            <h1><?= AmosSondaggi::tHtml('amossondaggi', 'Sondaggio di gradimento') ?></h1>
            <?php if (isset($id) && isset($attivita)): ?>
                <img src="/img/loading-sondaggio.gif" alt="Loading"/>
                <meta http-equiv="refresh"
                      content="0;URL=/<?= $this->context->module->id ?>/pubblicazione/sondaggio-pubblico?id=<?= $id ?>&attivita=<?= $attivita ?>">
            <?php else: ?>
            <div class="marginTB">
                <h3 class="green">
                    <strong><?= AmosSondaggi::tHtml('amossondaggi', "Inserisci il codice dell'attività che hai trovato sul calendario che ti è stato fornito") ?></strong>
                </h3>
                <section>
                    <div class="sondaggi-index">
                        <?php
                        $form = ActiveForm::begin();
                        ?>
                        <div id="dati-anagrafici">
                            <p><?=
                                $form->field($model, 'attivita')->textInput(['maxLength' => 255])->label('Codice attività');
                                ?>
                            </p>
                        </div>

                        <div id="form-actions" class="bk-btnFormContainer">

                            <?php
                            echo Html::submitButton(AmosSondaggi::tHtml('amossondaggi', 'Compila il sondaggio'), [
                                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                                'model' => $model
                            ]);
                            ?>

                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                    <div class="clearfix"></div>
                </section>

                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
