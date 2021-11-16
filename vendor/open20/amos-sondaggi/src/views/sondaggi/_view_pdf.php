<?php
/** @var  $sondaggio \open20\amos\sondaggi\models\Sondaggi */

$header = $data[0];
$risposte = $data[1];
$i = 1;
?>
<h2><?= $sondaggio->titolo ?></h2>
<br>

<?php foreach ($risposte as $key => $risposta){
    if ($key == $divider) echo "<hr>";
?>
    <?php
    if ( !empty($header[$key]) || !empty($risposta) ):
        ?>
        <label><strong><?= $header[$key]?></strong></label>
        <p><?= $risposta?></p>
        <?php
    endif;
    ?>
    <?php if ($key == 2) {
        echo "<label><strong>Iniziato il</strong>";
        echo "<p>". (!empty($rispostaModel->begin_date) ? \Yii::$app->formatter->asDate($rispostaModel->begin_date) : '')."</p>";
        echo "<label><strong>Completato il</strong>";
        echo "<p>". (!empty($rispostaModel->end_date) ? \Yii::$app->formatter->asDate($rispostaModel->end_date) : '')."</p>";
    } ?>
<?php } ?>
