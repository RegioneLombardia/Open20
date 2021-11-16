<?php

use open20\amos\layout\Module;

$isGuest = \Yii::$app->user->isGuest;

$canCreateController = false;
if (\Yii::$app->controller instanceof \open20\amos\core\controllers\CrudController) {
    $canCreateController = \Yii::$app->controller->can('CREATE');
}

$createNewButtonParams = Yii::$app->view->params['createNewBtnParams'];
if (!empty($createNewButtonParams)) {
    if (!empty($createNewButtonParams['urlCreateNew'])) {
        $urlCreate = $createNewButtonParams['urlCreateNew'];
    }

    if (!empty($createNewButtonParams['createNewBtnLabel'])) {
        $labelCreate = $createNewButtonParams['createNewBtnLabel'];
    }
}

$canCreate = (isset($canCreate)) ? $canCreate : !$isGuest && $canCreateController;
$canManage = (isset($canManage)) ? $canManage : !$isGuest;

$hideCreate = (isset($hideCreate)) ? $hideCreate : false;
$hideManage = (isset($hideManage)) ? $hideManage : false;

$hideSecondAction = (isset($hideSecondAction)) ? $hideSecondAction : true;
$iconSecondAction = (!empty($iconSecondAction)) ? $iconSecondAction : 'plus-circle-o';

$titlePreventCreate = (!empty($titlePreventCreate)) ? $titlePreventCreate : Module::t(
    'amoslayout',
    'Accedi o registrati alla piattaforma {platformName} per creare un contenuto',
    ['platformName' => \Yii::$app->name]
);
$titleCanNotCreate = (!empty($titleCanNotCreate)) ? $titleCanNotCreate : Module::t(
    'amoslayout',
    'Non hai il permesso per creare un contenuto della piattaforma {platformName}',
    ['platformName' => \Yii::$app->name]
);

$titlePreventSecondAction = (isset($titlePreventSecondAction)) ? $titlePreventSecondAction : Module::t(
    'amoslayout',
    'Accedi o registrati alla piattaforma {platformName} per eseguire questa operazione',
    ['platformName' => \Yii::$app->name]
);

$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
    if (isset($scope['community'])) {
        $community = \open20\amos\community\models\Community::findOne($scope['community']);
        if (!empty($community)) {
            $canCreateMemberActive = \open20\amos\community\utilities\CommunityUtil::userIsCommunityMemberActive($community->id, \Yii::$app->user->id);

            $canSecondAction = $canCreate && $canCreateMemberActive;
            $canCreate = $canCreate && $canCreateMemberActive;
            $communityName = $community->name;
            $titleScopePreventSecondAction = (isset($titleScopePreventSecondAction)) ? $titleScopePreventSecondAction : Module::t(
                'amoslayout',
                'Per {labelSecondAction} iscriviti alla community {communityName}',
                [
                    'communityName' => $communityName,
                    'labelSecondAction' => $labelSecondAction,

                ]
            );
            $titleScopePreventCreate = (isset($titleScopePreventCreate)) ? $titleScopePreventCreate : Module::t(
                'amoslayout',
                'Per creare un contenuto iscriviti alla community {communityName}',
                ['communityName' => $communityName]
            );
        }
    }
}

$manageLinks = [];
if (method_exists(\Yii::$app->controller, 'getManageLinks')) {
    $controller = \Yii::$app->controller;
    $manageLinks = $controller::getManageLinks();
    $currentUrl = str_replace('/it/', '/', strtok(\yii\helpers\Url::current(), '?'));

    foreach ($manageLinks as $k => $v) {
        if (
            (!empty($v['url']) && ($currentUrl == $v['url'])) ||
            (!empty($v['url']) && $v['url'] == $urlLinkAll)
        ) {
            unset($manageLinks[$k]);
        }
    }
}
?>

<div class="bi-plugin-header">

    <div class="flexbox title-heading-plugin">
        <div class="m-r-10">
            <div class="h2 text-uppercase "><?= $titleSection ?></div>
        </div>
        <?php if (!empty($urlLinkAll)) : ?>
            <a href="<?= $urlLinkAll ?>"
               class="link-all-<?= $modelLabel ?> text-uppercase align-items-center small"
               title="<?= $titleLinkAll ?>">
                <span><?= $labelLinkAll ?></span>
                <span class="icon mdi mdi-arrow-right-circle-outline"></span>
            </a>
        <?php endif ?>
    </div>

    <div class="cta-wrapper">
        <?php if ((!$hideCreate || !$hideSecondAction) || !$hideManage) { ?>
            <?php if (!$isGuest) { ?>
                <?php // ------------------ PER COMMUNITY ----------------
                ?>
                <?php if ($isSetScope) { ?>
                    <div class="flexbox manage-cta-container">
                        <?php if (!$hideCreate) { ?>
                            <?php if ($canCreate && !empty($urlCreate)) { ?>
                                <?= \yii\helpers\Html::a(" <span class=\"am am-plus-circle-o\"></span><span>$labelCreate</span>", $urlCreate, [
                                    'class' => "cta link-create-$modelLabel flexbox align-items-center btn btn-xs btn-primary",
                                    'title' => $titleCreate
                                ]); ?>
                            <?php } else { ?>
                                <button class="cta link-create-<?= $modelLabel ?> flexbox align-items-center btn btn-xs btn-primary disabled disabled-with-pointer-events"
                                        data-toggle="tooltip" title="<?= $titleScopePreventCreate ?>">
                                    <span class="am am-plus-circle-o"></span>
                                    <span><?= $labelCreate ?></span>
                                </button>
                            <?php } ?>
                        <?php } ?>
                        <?= \open20\design\components\SecondActionWidget::widget([
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

                        ]) ?>
                        <?php if (!$hideManage) { ?>
                            <?php if ($canManage && !empty($manageLinks)) { ?>
                                <div class="dropdown">
                                    <button class="cta link-manage-<?= $modelLabel ?> flexbox align-items-center btn btn-outline-tertiary dropdown-toggle"
                                            type="button" data-toggle="dropdown">
                                        <span class="am am-settings"></span>
                                        <span><?= $labelManage ?></span>
                                        <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <?php foreach ($manageLinks as $singleManage) : ?>
                                            <li>
                                                <a href="<?= $singleManage['url'] ?>"
                                                   title="<?= $singleManage['title'] ?>"><?= $singleManage['label'] ?></a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <?php // --------------------- FUORI DALLA COMMUNITY ----------------
                    ?>
                    <div class="flexbox manage-cta-container">
                        <?php if (!$hideCreate) { ?>
                            <?php if ($canCreate && !empty($urlCreate)) {
                                $parameters['class'] = "cta link-create-$modelLabel flexbox align-items-center btn btn-xs btn-primary";
                                $parameters['title'] = $titleCreate;
                                if (isset($dataConfirmCreate) && !empty($dataConfirmCreate)) {
                                    $parameters['data-confirm'] = $dataConfirmCreate;
                                }
                                ?>
                                <?= \yii\helpers\Html::a(" <span class=\"am am-plus-circle-o\"></span><span>$labelCreate</span>", $urlCreate, $parameters); ?>
                            <?php } else { ?>
                                <button class="cta link-create-<?= $modelLabel ?> flexbox align-items-center btn btn-xs btn-primary disabled disabled-with-pointer-events"
                                        data-toggle="tooltip" title="<?= ($isGuest ? $titlePreventCreate : $titleCanNotCreate) ?>">
                                    <span class="am am-plus-circle-o"></span>
                                    <span><?= $labelCreate ?></span>
                                </button>
                            <?php } ?>
                        <?php } ?>
                        <?= \open20\design\components\SecondActionWidget::widget([
                            'modelLabel' => $modelLabel,
                            'urlSecondAction' => $urlSecondAction,
                            'labelSecondAction' => $labelSecondAction,
                            'titleSecondAction' => $titleSecondAction,
                            'iconSecondAction' => $iconSecondAction,
                            'btnClass' => $btnClass,
                            'hideSecondAction' => $hideSecondAction,
                            'canSecondAction' => $canSecondAction
                        ]) ?>
                        <?php if (!$hideManage) { ?>
                            <?php if ($canManage && !empty($manageLinks)) { ?>
                                <div class="dropdown">
                                    <button class="cta link-manage-<?= $modelLabel ?> flexbox align-items-center btn btn-outline-tertiary dropdown-toggle"
                                            type="button" data-toggle="dropdown">
                                        <span class="am am-settings"></span>
                                        <span><?= $labelManage ?></span>
                                        <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <?php foreach ($manageLinks as $singleManage) : ?>
                                            <li>
                                                <a href="<?= $singleManage['url'] ?>"
                                                   title="<?= $singleManage['title'] ?>"><?= $singleManage['label'] ?></a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="flexbox manage-cta-container">
                    <?php if (!$hideCreate) : ?>
                        <a href="<?= \Yii::$app->params['linkConfigurations']['loginLinkCommon'] ?>"
                           class="cta link-create-<?= $modelLabel ?> flexbox align-items-center btn btn-xs btn-primary disabled  disabled-with-pointer-events"
                           data-toggle="tooltip" title="<?= $titlePreventCreate ?>">
                            <span class="am am-plus-circle-o"></span>
                            <span><?= $labelCreate ?></span>
                        </a>
                    <?php endif ?>
                    <?php if (!$hideSecondAction) : ?>
                        <div class="flexbox manage-cta-container">
                            <a href="<?= \Yii::$app->params['linkConfigurations']['loginLinkCommon'] ?>"
                               class="cta link-my-<?= $modelLabel ?> flexbox align-items-center btn btn-xs btn-primary-outline disabled  disabled-with-pointer-events"
                               data-toggle="tooltip" title="<?= $titlePreventSecondAction ?>">
                                <span class="am am-<?= $iconSecondAction ?>"></span>
                                <span><?= $labelSecondAction ?></span>
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<?php if (isset($subTitleSection)) : ?>
    <div class="subtitle-<?= $modelLabel ?> <?= $subTitleAdditionalClass?>"><?= $subTitleSection ?></div>
<?php endif ?>
