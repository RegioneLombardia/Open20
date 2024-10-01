<?php

use open20\amos\sondaggi\AmosSondaggi;
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */

/**
 * @var $model
 * @var $idSessione
 * @var $idPagina
 * @var $utente
 */

$arrayModelRisposte = [];
foreach ((Array)$risposteWithFiles as $rispostaWithFile){
    $nomeVariabileDomanda = 'file_'.$rispostaWithFile->sondaggi_domande_id;
    $arrayModelRisposte [$nomeVariabileDomanda]= $rispostaWithFile;
}
?>

<?php if(isset($id) && isset($idPagina)): ?>
<div class="sondaggi-compilazione container sondaggi-compilazione-sondaggio<?=$id?>">
    <?= $this->render('@common/uploads/' . $this->context->module->id . '/pubblicazione/views/q' . $id . '/Pagina_' . $idPagina, \yii\helpers\ArrayHelper::merge([
        'model' => $model,
        'idSessione' => $idSessione,
        'idPagina' => $idPagina,
        'utente' => $utente,
        'ultimaPagina' => $ultimaPagina,
        'url' => $url,
        'read' => $read,
        'useSession' => $useSession
    ], $arrayModelRisposte)) ?>
</div>

<?php else: 

    $this->params['breadcrumbs'][] = $titolo;
    if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];

?>

<?php endif; ?>
