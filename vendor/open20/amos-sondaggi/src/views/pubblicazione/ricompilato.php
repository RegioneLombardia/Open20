<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 * @var \yii\db\ActiveQuery $pubblicazioni
 */
$this->title                   = AmosSondaggi::t('amossondaggi', '#sondaggioterminato');
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-index text-center sondaggi-success m-t-30 m-b-20">
    <?php
    if (!empty($pubblicazioni->one()->text_end_title) && strlen(trim($pubblicazioni->one()->text_end_title))) {
        ?>
        <?=
        AmosIcons::show('check-circle', [
            'class' => 'am-4 success m-t-30'
        ])
        ?>
        <h2 class="p-t-5 nom-b"><?= $pubblicazioni->one()->text_end_title ?></h2>
        <?php
    } else {
        ?>
        <?=
        AmosIcons::show('check-circle', [
            'class' => 'am-4 success m-t-30'
        ])
        ?>
    <?php } ?>

    <?php
    if (!empty($pubblicazioni->one()->text_end) && strlen(trim($pubblicazioni->one()->text_end))) {
        if ($pubblicazioni->one()->text_end_html == 1) {
            ?>
            <?= $pubblicazioni->one()->text_end ?>
            <?php
        } else {
            ?>
            <h3><?= $pubblicazioni->one()->text_end ?></h3>
            <?php
        }
    } else {
        ?>
        <h3 class="m-b-30"><?=
            AmosSondaggi::tHtml('amossondaggi', '#thankyoumessage').((!AmosSondaggi::instance()->enableCompilationWorkflow && $pubblicazioni->one()->sondaggi->send_pdf_via_email)
            == true ? 'Ti abbiamo inviato un’email di riepilogo con tutte le informazioni.' : '')
            ?></h3>
    <?php } ?>
    <?=
    Html::a(AmosSondaggi::t('amossondaggi', 'Chiudi'), !empty($url)?$url:'/sondaggi/pubblicazione/index',
        [
        'class' => 'btn btn-secondary undo-edit m-t-30'
    ]);
    ?>
</div>
