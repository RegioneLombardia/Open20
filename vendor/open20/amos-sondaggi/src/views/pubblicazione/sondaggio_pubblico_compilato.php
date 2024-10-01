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
$this->title = AmosSondaggi::t('amossondaggi', 'Sondaggio completato');
?>
<div class="container">
    <nav role="navigation" aria-label="breadcrumbs" aria-labelledby="bc-title" id="bc">
        <h5 id="bc-title" class="vis-off"><?= AmosSondaggi::tHtml('amossondaggi', 'Sei qui') ?>:</h5>
        <ol class="breadcrumb">
            <li><a href="/site/index"><?= AmosSondaggi::tHtml('amossondaggi', 'Home') ?></a></li>
            <li>
                <a href="/<?= $this->context->module->id ?>/pubblicazione/sondaggio-pubblico-attivita"><?= AmosSondaggi::tHtml('amossondaggi', 'Sondaggio di gradimento') ?></a>
            </li>
            <li class="active"><?= AmosSondaggi::tHtml('amossondaggi', 'Sondaggio completato') ?></li>
        </ol>
    </nav>
</div>

<main role="main" id="mainContent">
    <div class="container">
        <div class="page" role="contentinfo">
            <?php
            if (!empty($pubblicazioni->one()->text_end_title) && strlen(trim($pubblicazioni->one()->text_end_title))) {
                ?>
                <h1><?= $pubblicazioni->one()->text_end_title ?></h1>
                <?php
            } else {
                ?>
                <h1><?= AmosSondaggi::tHtml('amossondaggi', 'Sondaggio di gradimento') ?></h1>
            <?php } ?>
            <div class="sondaggi-compilazione marginTB">
                <div class="sondaggi-index">
                    <?php
                    if (!empty($pubblicazioni->one()->text_end) && strlen(trim($pubblicazioni->one()->text_end))) {
                        if ($pubblicazioni->one()->text_end_html == 1) {
                            ?>
                            <?= $pubblicazioni->one()->text_end ?>
                            <?php
                        } else {
                            ?>
                            <h4 class="green"><strong><?= $pubblicazioni->one()->text_end ?></strong></h4>
                            <?php
                        }
                    } else {
                        ?>
                        <h4 class="green"><strong><?= AmosSondaggi::tHtml('amossondaggi', 'Complimenti! Sondaggio completato') ?></strong></h4>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</main>
