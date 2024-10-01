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
use open20\amos\core\icons\AmosIcons;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */
$this->title = AmosSondaggi::t('amossondaggi', 'Sondaggio terminato');
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
?>
<div class="sondaggi-index text-center sondaggi-warning">
    <?php
    if (!empty($pubblicazioni->one()->text_not_compilable) && strlen(trim($pubblicazioni->one()->text_not_compilable))) {
        if ($pubblicazioni->one()->text_not_compilable_html == 1) {
            ?>
            <?= $pubblicazioni->one()->text_not_compilable ?>
            <?php
        } else {
            ?>
            <h4><?= $pubblicazioni->one()->text_not_compilable ?></h4>
            <?php
        }
    } else {
        ?>
             <?= AmosIcons::show('alert-triangle', [
            'class' => 'am-4 warning m-t-15'
        ]) ?>
         <h2 class="p-t-5 nom-b"><?= AmosSondaggi::t('amossondaggi', 'Attenzione') ?></h2>
         <h3><?= AmosSondaggi::t('amossondaggi', 'Sondaggio già compilato o non compilabile') ?></h3>
          <?= Html::a(AmosSondaggi::t('amossondaggi', 'Chiudi'), Url::previous(), [
        'class' => 'btn btn-secondary undo-edit'
    ]); ?>
    <?php } ?>
</div>
