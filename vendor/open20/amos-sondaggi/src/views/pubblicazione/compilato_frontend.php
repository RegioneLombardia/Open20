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
$this->title                   = AmosSondaggi::t('amossondaggi', 'Sondaggio terminato');
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
if (!empty($forzato)) {
    \Yii::$app->language = $forzato;
}
?>
<div class="sondaggi-index text-center sondaggi-success m-t-30 m-b-20">
    <?php
    if (!empty($sondaggio->titolo_fine_sondaggio_front) && strlen(trim($sondaggio->titolo_fine_sondaggio_front))) {
        ?>
        <?=
        AmosIcons::show('check-circle', [
            'class' => 'am-4 success m-t-30'
        ])
        ?>
        <h2 class="p-t-5 nom-b"><?= $sondaggio->titolo_fine_sondaggio_front ?></h2>
        <?php
    } else {
        ?>
        <?=
        AmosIcons::show('check-circle', [
            'class' => 'am-4 success m-t-30'
        ])
        ?>
        <h2 class="p-t-5 nom-b"><?= AmosSondaggi::tHtml('amossondaggi', 'Grazie per aver compilato il sondaggio.')
        ?></h2>
        <?php } ?>

    <?php
    if (!empty($sondaggio->testo_fine_sondaggio_front) && strlen(trim($sondaggio->testo_fine_sondaggio_front))) {
        if ($pubblicazioni->one()->text_end_html == 1) {
            ?>
            <?= $sondaggio->testo_fine_sondaggio_front ?>
            <?php
        } else {
            ?>
            <h3><?= \Yii::$app->formatter->asNtext($sondaggio->testo_fine_sondaggio_front) ?></h3>
            <?php
        }
    } else {
        if ($sondaggio->frontend != 1):
        ?>
        <h3 class="m-b-30"><?=
            AmosSondaggi::tHtml('amossondaggi', 'Ti abbiamo inviato un’email di riepilogo con tutte le informazioni.')
            ?></h3>
        <?php
        endif;
    }

    $closeUrl = trim($sondaggio->url_chiudi_sondaggio);
    ?>
    <?=
    Html::a(AmosSondaggi::t('amossondaggi', 'Chiudi'), (!empty($closeUrl) && AmosSondaggi::instance()->enableRedirectionUrl) ? $closeUrl: Url::previous(),
        [
        'class' => 'btn btn-secondary undo-edit m-t-30'
    ]);
    ?>
</div>
