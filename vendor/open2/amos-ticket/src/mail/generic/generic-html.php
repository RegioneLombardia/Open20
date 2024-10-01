<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\mail\generic
 * @category   CategoryName
 */

use open2\amos\ticket\AmosTicket;

/**
 * @var $this yii\web\View
 */

$appLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);
$appName = Yii::$app->name;

$this->title = AmosTicket::t('amosticket', 'Registrazione {appName}', ['appName' => $appName]);
$this->registerCssFile('http://fonts.googleapis.com/css?family=Roboto');
?>


<table width=" 600" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>
      <div class="corpo" style="border:1px solid #cccccc;padding:10px;margin-bottom:10px;background-color:#ffffff;margin-top:20px">
        <div class="sezione titolo" style="overflow:hidden;color:#000000;">
          <h2 style="padding:5px 0;	margin:0;"><?= AmosTicket::t('amosticket', 'Servizio assistenza'); ?></h2>
        </div>
        <div class="sezione" style="overflow:hidden;color:#000000;">
          <div class="testo">
          <?php
            foreach ($body as $paragraph) {
              echo '<p>' . $paragraph . '<br /></p>';
            }
          ?>
          </div>
        </div>
      </div>
    </td>
  </tr>
</table>

<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>
      <p style="text-align:center"><?= AmosTicket::t('amosticket', '*** Questa è una e-mail generata automaticamente, si prega di non rispondere***'); ?></p>
    </td>
  </tr>
</table>