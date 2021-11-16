<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\layout\Module;

$moduleCwh       = Yii::$app->getModule('cwh');
$scope       = null;
$communityId = null;
if (!empty($moduleCwh)) {
    /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
    $scope = $moduleCwh->getCwhScope();
}
if (!empty($scope)) {
    if (isset($scope['community'])) {
        $communityId = $scope['community'];
        //$community   = \open20\amos\community\models\Community::findOne($communityId);
    }
}
?>
<?php
if (empty($communityId)) {
    if ($this->context->action->id !== \Yii::$app->homeUrl) :
?>
        <a class="back-to-dashboard" href="/dashboard" title="<?=
                                                                    Module::t('amoslayout', 'Ritorna alla Dashboard di Piattaforma')
                                                                ?>">
            <?= AmosIcons::show('chevron-left') ?>
            <span><?= Module::t('amoslayout', 'Torna alla Dashboard') ?></span>
        </a>
    <?php endif; ?>

<?php
}
