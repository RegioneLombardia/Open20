<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */

use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */
$this->title = AmosSondaggi::t('amossondaggi', 'Sondaggi pubblici');
?>
<div class="container">
    <nav role="navigation" aria-label="breadcrumbs" aria-labelledby="bc-title" id="bc">
        <h5 id="bc-title" class="vis-off"><?= AmosSondaggi::tHtml('amossondaggi', 'Sei qui') ?>:</h5>
        <ol class="breadcrumb">
            <li><a href="/site/index"><?= AmosSondaggi::tHtml('amossondaggi', 'Home') ?></a></li>
            <li class="active"><?= AmosSondaggi::tHtml('amossondaggi', 'Sondaggi pubblici') ?></li>
        </ol>
    </nav>
</div>
<main role="main" id="mainContent">
    <div class="container">
        <div class="page" role="contentinfo">
            <h1><?= AmosSondaggi::t('amossondaggi', 'Sondaggi pubblici compilabili') ?></h1>
            <?php if ($model->count() == 0): ?>
                <div class="marginTB">
                    <h3 class="green"><strong><?= AmosSondaggi::tHtml('amossondaggi', 'Non sono presenti sondaggi pubblici compilabili') ?></strong></h3>
                    <section>
                        <div class="clearfix"></div>
                    </section>
                </div>
            <?php
            else:
                foreach ($model->all() as $Sondaggio) {
                    ?>
                    <div class="marginTB">
                        <h3 class="green">
                            <strong>
                                <a href="/<?= $this->context->module->id ?>/pubblicazione/sondaggio-pubblico?id=<?= $Sondaggio['id'] ?>&libero=TRUE"><?= $Sondaggio['titolo'] ?></a>
                            </strong>
                        </h3>
                        <section>
                            <div class="sondaggi-index">
                                <?= $Sondaggio['descrizione'] ?>
                            </div>
                            <div class="clearfix"></div>
                        </section>
                    </div>
                    <?php
                }
            endif;
            ?>
        </div>
    </div>
</main>
