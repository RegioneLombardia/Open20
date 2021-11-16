<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\widgets\views\widgets
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\module\BaseAmosModule;

/**
 * @var string $contentCreatorAvatar Avatar of the content creator.
 * @var string $contentCreatorNameSurname Name and surname of the content creator.
 * @var bool $hideInteractionMenu If true set the class to hide the interaction menu.
 * @var array $interactionMenuButtons Sets the interaction menu buttons.
 * @var array $interactionMenuButtonsHide Sets the interaction menu buttons to hide.
 * @var string $publicatonDate Publication date of the content.
 * @var string $customContent Custom content.
 * @var UserProfile $contentCreator Content creator.
 * @var \open20\amos\core\forms\ItemAndCardHeaderWidget $widget
 */

if (isset(\Yii::$app->params['customContentCreatorAvatarUrl']) && \Yii::$app->params['customContentCreatorAvatarUrl']) {
    $avatarUrl = Yii::$app->urlManager->createAbsoluteUrl(\Yii::$app->params['customContentCreatorAvatarUrl']);
} else {
    $avatarUrl = $contentCreator->getAvatarWebUrl();
}

?>

<?php if (!isset(\Yii::$app->params['hideEmailContentCreatorAvatar']) || (isset(\Yii::$app->params['hideEmailContentCreatorAvatar']) && (\Yii::$app->params['hideEmailContentCreatorAvatar'] === false))): ?>
    <td width="25" border="0" align="top" style="padding-right:5px;">
        <img src="<?= $avatarUrl; ?>" width="25" height="25" border="0" align="center" style="border-radius:50%;" alt="<?= $contentCreatorNameSurname; ?>">
    </td>
<?php endif; ?>
<td style="font-size:11px; font-family: sans-serif; color:#000;">
    <p style="margin:0px;"><strong><?= $widget->getCreator($contentCreatorNameSurname) ?></strong></p>
    <?php if (isset($contentPrevalentPartnership) && $contentPrevalentPartnership) : ?>
        <p style="margin:0px;">(<?= $contentPrevalentPartnership ?>)</p>
    <?php endif; ?>
    <?php if (isset($contentCreatorTargets) && $contentCreatorTargets) : ?>
        <?= $contentCreatorTargets ?>
    <?php endif; ?>
    <?php if (isset($customContent) && $customContent) : ?>
        <?= $customContent; ?>
    <?php endif; ?>
    <?php if ($publicatonDate): ?>
        <?= BaseAmosModule::t('amoscore', 'Pubblicato il') ?> <?= $publicatonDate ?>
    <?php endif; ?>
</td>
