<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/proscriptions/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-admin
 * @category CategoryName
 */

use amos\userauth\frontend\Module;;

use open20\amos\core\helpers\Html;
use yii\helpers\Url;

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

?>
<?php if ($socialAuthModule && $socialAuthModule->enableSpid) : ?>
  <?php if (!$hideIdpcButtonInfo) : ?>
    <div class="border-bottom border-light pb-3 mb-4">
      <?php if (!$hideIdpcButtonTitle) : ?>
        <h3 class="h5"><?= Module::t('Accedi con idPC') ?></h3>
      <?php endif ?>
      <?php if (!$hideIdpcButtonSubtitle) : ?>
        <p class="mb-0"><?= Module::t('Sistema di Identity Provider di Regione Lombardia') ?></p>
      <?php endif ?>
    </div>
  <?php endif ?>
  <?=
  Html::a(
    Html::tag('span', Module::t('Accedi con la tua identità digitale')),
    Url::to('/socialauth/shibboleth/endpoint', 'https'),
    [
      'class' => 'btn btn-icon rounded-0 btn-spid text-uppercase',
      'title' => Module::t('Accedi con la tua identità digitale'),
    ]
  )
  ?>


<?php endif ?>