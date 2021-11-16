<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;
use \open20\amos\community\models\Community;
use \open20\amos\community\utilities\CommunityUtil;
use \open20\design\components\SecondActionWidget;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$btnClass = (isset($btnClass)) ? $btnClass : 'btn btn-icon btn-xs';

$isGuest = \Yii::$app->user->isGuest;

$canCreate       = (isset($canCreate)) ? $canCreate : !$isGuest;
$canSecondAction = (isset($canSecondAction)) ? $canSecondAction : !$isGuest;
$canManage       = (isset($canManage)) ? $canManage : !$isGuest;

$hideCreate       = (isset($hideCreate)) ? $hideCreate : false;
$hideManage       = (isset($hideManage)) ? $hideManage : false;
$hideSecondAction = (isset($hideSecondAction)) ? $hideSecondAction : true;
$hideViewAll      = (isset($hideViewAll) && $hideViewAll) ? $hideViewAll : false;

$bulletCountNumber = (isset($bulletCountNumber)) ? $bulletCountNumber : 0;

$iconSecondAction = (isset($iconSecondAction)) ? $iconSecondAction : 'plus-circle-outline';

$titlePreventCreate       = (isset($titlePreventCreate)) ? $titlePreventCreate : Module::t(
    'amosdesign',
    'Accedi o registrati alla piattaforma {platformName} per creare un contenuto',
    ['platformName' => \Yii::$app->name]
);
$titleCanNotCreate = (!empty($titleCanNotCreate)) ? $titleCanNotCreate : Module::t(
    'amoslayout',
    'Non hai il permesso per creare un contenuto della piattaforma {platformName}',
    ['platformName' => \Yii::$app->name]
);

$titlePreventSecondAction = (isset($titlePreventSecondAction)) ? $titlePreventSecondAction : Module::t(
    'amosdesign',
    'Accedi o registrati alla piattaforma {platformName} per eseguire questa operazione',
    ['platformName' => \Yii::$app->name]
);

$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope      = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
    if (isset($scope['community'])) {
        $community = Community::findOne($scope['community']);
        if (!empty($community)) {
            $canCreate                = CommunityUtil::userIsCommunityMemberActive($community->id);
            $canSecondAction          = CommunityUtil::userIsCommunityMemberActive($community->id);
            $communityName            = $community->name;
            $titleScopePreventCreate  = (isset($titleScopePreventCreate)) ? $titleScopePreventCreate : Module::t(
                'amosdesign',
                'Per creare un contenuto iscriviti alla community {communityName}',
                ['communityName' => $communityName]
            );
            $titlePreventSecondAction = (isset($titlePreventCreate)) ? $titlePreventCreate : Module::t(
                'amosdesign',
                'Accedi o registrati alla piattaforma {platformName} per eseguire questa operazione',
                ['platformName' => \Yii::$app->name]
            );
        }
    }
}
?>


<div class="bi-plugin-header d-flex flex-wrap flex-sm-nowrap mb-3">
    <div class="d-flex flex-wrap">
        <div class="d-flex flex-wrap align-items-center">
            <h2 class="h2 d-flex align-items-center title-<?= $modelLabel ?> text-uppercase mr-2 mb-0"><?= $titleSection ?>
                <?php if ($bulletCountNumber > 0) : ?>
                    <span class="badge badge-pill badge-danger bullet-count-testata-plugin small ml-3">
                        <?= $bulletCountNumber ?>
                        <span class="sr-only"><?= Module::t('amosdesign', 'Nuove') ?></span>
                    </span>
                <?php endif; ?>
            </h2>

            <div>
                <?php if (!empty($urlLinkAll)) : ?>
                    <a href="<?= $urlLinkAll ?>" class="link-all-<?= $modelLabel ?> btn btn-xs btn-link btn-icon" title="<?= $titleLinkAll ?>">
                        <span><?= $labelLinkAll ?></span>
                        <svg class="icon ml-1" role="img">
                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#arrow-right-circle-outline"></use>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="d-flex ml-auto">
        <?php if ((!$hideCreate || !$hideSecondAction) || !$hideManage) : ?>
            <?php if (!$isGuest) : ?>
                <?php // ------------------ PER COMMUNITY ----------------
                ?>
                <?php if ($isSetScope) : ?>
                    <div class="btn-wrapper">
                        <div class="d-flex">
                            <?php if (!$hideCreate) : ?>
                                <?php if ($canCreate && !empty($urlCreate)) : ?>
                                    <a href="<?= $urlCreate ?>" class="cta link-create-<?= $modelLabel ?> <?= $btnClass ?> btn-primary" title="<?= $titleCreate ?>">
                                        <svg class="icon" role="img">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#plus-circle-outline"></use>
                                        </svg> <span><?= $labelCreate ?></span>
                                    </a>
                                <?php else : ?>
                                    <button class="cta link-create-<?= $modelLabel ?> <?= $btnClass ?> btn-primary disabled disabled-with-pointer-events" data-toggle="tooltip" title="<?= $titleScopePreventCreate ?>" aria-disabled="true">
                                        <svg class="icon" role="img">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#plus-circle-outline"></use>
                                        </svg> <span><?= $labelCreate ?></span>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?=
                                SecondActionWidget::widget([
                                    'type' => SecondActionWidget::TYPE_DESIGN,
                                    'modelLabel' => $modelLabel,
                                    'urlSecondAction' => $urlSecondAction,
                                    'labelSecondAction' => $labelSecondAction,
                                    'titleSecondAction' => $titleSecondAction,
                                    'titleScopePreventSecondAction' => $titleScopePreventSecondAction,
                                    'iconSecondAction' => $iconSecondAction,
                                    'btnClass' => $btnClass,
                                    'isScope' => true,
                                    'hideSecondAction' => $hideSecondAction,
                                    'canSecondAction' => $canSecondAction
                                ])
                            ?>
                            <?php if (!$hideManage) : ?>
                                <?php if ($canManage && !empty($manageLinks)) : ?>
                                    <div class="dropdown">
                                        <button class="cta link-manage-<?= $modelLabel ?> <?= $btnClass ?> btn-outline-tertiary ml-2 dropdown-toggle" type="button" data-toggle="dropdown">
                                            <svg class="icon" role="img">
                                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#cog"></use>
                                            </svg>
                                            <span><?= $labelManage ?></span>
                                        </button>
                                        <div class="dropdown-menu-right dropdown-menu">
                                            <div class="link-list-wrapper">
                                                <ul class="link-list">
                                                    <?php foreach ($manageLinks as $singleManage) : ?>
                                                        <li>
                                                            <a class="list-item" href="<?= $singleManage['url'] ?>" title="<?= $singleManage['title'] ?>">
                                                                <span><?= $singleManage['label'] ?></span>
                                                            </a>
                                                        </li>
                                                    <?php endforeach ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <?php // --------------------- FUORI DALLA COMMUNITY ---------------- 
                    ?>
                    <div class="btn-wrapper">
                        <div class="d-flex">
                            <?php if (!$hideCreate) : ?>
                                <?php if ($canCreate && !empty($urlCreate)) { ?>
                                    <a href="<?= $urlCreate ?>" class="cta link-create-<?= $modelLabel ?> <?= $btnClass ?> btn-primary" title="<?= $titleCreate ?>">
                                        <svg class="icon" role="img">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#plus-circle-outline"></use>
                                        </svg>
                                        <span><?= $labelCreate ?></span>
                                    </a>
                                <?php } else { ?>
                                    <button class="cta link-create-<?= $modelLabel ?> flexbox align-items-center btn btn-xs btn-primary disabled disabled-with-pointer-events" data-toggle="tooltip" title="<?= ($isGuest ? $titlePreventCreate : $titleCanNotCreate) ?>" aria-disabled="true">
                                        <span class="am am-plus-circle-o"></span>
                                        <span><?= $labelCreate ?></span>
                                    </button>
                                <?php } ?>
                            <?php endif; ?>
                            <?=
                                SecondActionWidget::widget([
                                    'type' => SecondActionWidget::TYPE_DESIGN,
                                    'modelLabel' => $modelLabel,
                                    'urlSecondAction' => $urlSecondAction,
                                    'labelSecondAction' => $labelSecondAction,
                                    'titleSecondAction' => $titleSecondAction,
                                    'iconSecondAction' => $iconSecondAction,
                                    'btnClass' => $btnClass,
                                    'hideSecondAction' => $hideSecondAction,
                                    'canSecondAction' => $canSecondAction
                                ])
                            ?>
                            <?php if (!$hideManage) : ?>
                                <?php if ($canManage && !empty($manageLinks)) : ?>
                                    <div class="dropdown">
                                        <button class="cta link-manage-<?= $modelLabel ?> <?= $btnClass ?> btn-outline-tertiary ml-2 dropdown-toggle" type="button" data-toggle="dropdown">
                                            <svg class="icon" role="img">
                                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#cog"></use>
                                            </svg>
                                            <span><?= $labelManage ?></span>
                                        </button>
                                        <div class="dropdown-menu-right dropdown-menu">
                                            <div class="link-list-wrapper">
                                                <ul class="link-list">
                                                    <?php foreach ($manageLinks as $singleManage) : ?>
                                                        <li>
                                                            <a class="list-item" href="<?= $singleManage['url'] ?>" title="<?= $singleManage['title'] ?>">
                                                                <span><?= $singleManage['label'] ?></span>
                                                            </a>
                                                        </li>
                                                    <?php endforeach ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="btn-wrapper">
                    <?php if (!$hideCreate) : ?>
                        <a href="<?= (isset($urlCreate)) ? $urlCreate : \Yii::$app->params['linkConfigurations']['loginLinkCommon'] ?>" class="cta link-create-<?= $modelLabel ?> <?= $btnClass ?> btn-primary disabled disabled-with-pointer-events" data-toggle="tooltip" title="<?= $titlePreventCreate ?>" aria-disabled="true">
                            <svg class="icon" role="img">
                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#plus-circle-outline"></use>
                            </svg> <span><?= $labelCreate ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!$hideSecondAction) : ?>
                        <a href="<?= (isset($urlSecondAction)) ? $urlSecondAction : \Yii::$app->params['linkConfigurations']['loginLinkCommon'] ?>" class="cta link-create-<?= $modelLabel ?> <?= $btnClass ?> btn-primary-outline disabled disabled-with-pointer-events" data-toggle="tooltip" title="<?= $titlePreventSecondAction ?>" aria-disabled="true">
                            <svg class="icon" role="img">
                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $iconSecondAction ?>"></use>
                            </svg> <span><?= $labelSecondAction ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>

</div>
<?php if (isset($subTitleSection)) : ?>
    <div class="subtitle-<?= $modelLabel ?>"><?= $subTitleSection ?></div>
<?php



endif;
