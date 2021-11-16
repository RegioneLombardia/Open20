<?php

use open20\amos\utility\Module;

$this->title = Module::t('amosutility', 'Run Migrations');
$this->params['breadcrumbs'][] = $this->title;
?>
<h3>Migrations to be done:</h3>

<?php
if (!count($migrations)) {
    echo "None";
}
foreach ($migrations as $k => $migration) {
    echo "<p> - {$migration}</p>";
}
?>

<?php if ($force) : ?>
    <h3>Result:</h3>

    <p><?= $result; ?></p>
    <p><?= \yii\helpers\Html::a('Back', ['migrate'], ['class' => 'btn btn-default']) ?></p>
<?php else: ?>
    <br><br>
    <hr>
    <h4>Click here to run migrations, bon sorte!</h4>
    <p><?= \yii\helpers\Html::a('Migrate Now', ['migrate', 'force' => true], ['class' => 'btn btn-default']) ?></p>
<?php endif; ?>
