<?php

use yii\base\Security;
use yii\helpers\VarDumper;
use yii\web\View;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */


$modalId = Yii::$app->security->generateRandomString(10);
$okButtonId = Yii::$app->security->generateRandomString(10);

$this->registerJs(
  <<<JS

  $("#{$modalId}").modal('show');

  $("#{$okButtonId}").click(function(event) {
    $("#{$modalId}").modal('hide');
  });

JS
,  View::POS_READY);

?>


<div class="modal fade modale-validazione modal-custom" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modalId ?>-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h3 class="modal-title" id="<?= $modalId ?>-label"><?= isset($arrayContent['title']) ? $arrayContent['title'] : '' ?></h3>
        <button aria-label="chiudi la modale" type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-4 text-center text-md-left">
        <div class="row pb-4">
          <div class="col-md-5">
            <img class="img-fluid" src="<?= isset($arrayContent['icon']) ? $arrayContent['icon'] : '' ?>" alt="<?= isset($arrayContent['icon-alt']) ? $arrayContent['icon-alt'] : '' ?>">
          </div>
          <div class="col-md-7">
            <p class="p-2"><?= isset($arrayContent['description']) ? $arrayContent['description'] : '' ?></p>
          </div>
        </div>
        <!-- TODO cosa far fare al bottone quando si clicca ok -->
        <button type="button" id="<?= $okButtonId ?>" class="btn btn-primary w-75 d-flex justify-content-center m-auto"><?= isset($arrayContent['ok-button-label']) ? $arrayContent['ok-button-label'] : '' ?></button>
      </div>
    </div>
  </div>
</div>