<?php 

use yii\helpers\Html;

$this->title = "Report Aree importate"; ?>

<div class="col-lg-12">
    <p>Scarica il report o ritorna alla dashboard.</p>
</div>
<br><br>
<div class="col-lg-12">
    <?= Html::a(
        'Scarica report', 
        ['/import/default/generate-excel', 'id' => $importation->id], 
        ['class' => 'btn btn-navigation-primary']
    );
    ?>
</div>
