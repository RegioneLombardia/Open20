<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/licenses/gpl-3.0.html GNU General Public Proscription version 3
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
<?php if ($socialAuthModule && $socialAuthModule->enableAgidLogin) : ?>
  <?php if (!$hideIdpcAgidButtonInfo) : ?>
    <div class="border-bottom border-light pb-3 mb-4">
      <?php if (!$hideIdpcAgidButtonTitle) : ?>
        <p class="h5"><?= Module::t('Accedi con idPC AGID') ?></p>
      <?php endif ?>
      <?php if (!$hideIdpcAgidButtonSubtitle) : ?>
        <p class="mb-0"><?= Module::t('Sistema di Identity Provider di AGID') ?></p>
      <?php endif ?>
    </div>
  <?php endif ?>
  <?=
  Html::a(
    Html::tag('span', Module::t('Accedi con la tua identità digitale')),
    Url::to('/socialauth/agid-login/connect', 'https'),
    [
      'class' => 'btn btn-icon rounded-0 btn-spid text-uppercase',
      'title' => Module::t('Accedi con la tua identità digitale AGID'),
    ]
  )
  ?>


<?php endif ?>