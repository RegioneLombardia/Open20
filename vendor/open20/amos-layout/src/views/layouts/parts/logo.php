<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use open20\amos\core\module\BaseAmosModule;
use yii\helpers\Html;
use open20\amos\core\widget\WidgetAbstract;

/** @var bool|false $disablePlatformLinks - if true all the links to dashboard, settings, etc are disabled */
$disablePlatformLinks = isset(Yii::$app->params['disablePlatformLinks']) ? Yii::$app->params['disablePlatformLinks'] : false;

?>

<div class="container-logo">

    <?php if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) : ?>
        <div class="<?= isset($class) ? $class : 'container-custom' ?>">
        <?php else : ?>
            <div class="<?= isset($class) ? $class : 'container' ?>">
            <?php endif; ?>

            <?php if (isset(Yii::$app->params['logoConfigurations']) && is_array(Yii::$app->params['logoConfigurations']) && !empty(Yii::$app->params['logoConfigurations'])) : ?>

                <!-- new logo configurations -->
                <?php
                $logoConfigurations = Yii::$app->params['logoConfigurations'];
                ?>
                <?php foreach ($logoConfigurations as $logoConf) : ?>
                    <?php
                    $logoUrl = (!empty(Yii::$app->params['disablePlatformLinks']) ? null : Yii::$app->homeUrl);
                    if (isset($logoConf['logoUrl'])) {
                        if ($logoConf['logoUrl'] == 'frontendUrl') {
                            $logoUrl = Yii::$app->params['platform']['frontendUrl'];
                        } elseif ($logoConf['logoUrl'] == 'backendUrl') {
                            $logoUrl = Yii::$app->params['platform']['backendUrl'];
                        } else {
                            $logoUrl = $logoConf['logoUrl'];
                        }
                    }
                    $logoOptions = [];
                    $title = BaseAmosModule::t('amoscore', 'vai alla home page');
                    $logoOptions['title'] = $title;
                    if (isset($logoConf['logoTitle'])) {
                        $logoOptions['title'] = (isset($logoConf['logoTranslateCategory']) ? Yii::t($logoConf['logoTranslateCategory'], $logoConf['logoTitle']) : $logoConf['logoTitle']);
                    }
                    if (isset($logoConf['logoUrlTarget'])) {
                        $logoOptions['target'] = $logoConf['logoUrlTarget'];
                    }
                    $logoAlt = Yii::$app->name;
                    if (isset($logoConf['logoAlt'])) {
                        if (isset($logoConf['logoTranslateCategory'])) {
                            $logoAlt = Yii::t($logoConf['logoTranslateCategory'], $logoConf['logoAlt']);
                        } else {
                            $logoAlt = $logoConf['logoAlt'];
                        }
                    }
                    ?>

                    <?php if (!isset($logoConf['logoImg']) && !isset($logoConf['logoText']) && !isset($logoConf['logoSignature'])) : ?>
                        <?php
                        if (isset($logoConf['logoPosition'])) {
                            $logoOptions['class'] = $logoConf['logoPosition'];
                        }
                        ?>
                        <div class="logo-text text-center">
                            <?= (!$disablePlatformLinks) ? Html::a(Yii::$app->name, $logoUrl, $logoOptions) : Html::tag('p', Yii::$app->name); ?>
                        </div>
                    <?php endif; ?>

                    <!-- image logo -->
                    <?php if (isset($logoConf['logoImg'])) : ?>
                        <?php
                        if (!$disablePlatformLinks) {
                            $logoImg = Html::img($logoConf['logoImg'], [
                                'class' => 'img-responsive logo-amos',
                                'alt' => $logoAlt
                            ]);
                            if (isset($logoConf['logoPosition'])) {
                                if (isset($logoOptions['class'])) {
                                    $logoOptions['class'] .= ' ' . $logoConf['logoPosition'];
                                } else {
                                    $logoOptions['class'] = $logoConf['logoPosition'];
                                }
                            }
                            $logo = Html::a($logoImg, $logoUrl, $logoOptions);
                        } else {
                            $logo = Html::img($logoConf['logoImg'], [
                                'class' => 'img-responsive logo-amos' . (isset($logoConf['logoPosition']) ? ' ' . $logoConf['logoPosition'] : ''),
                                'alt' => $logoAlt
                            ]);
                        }
                        ?>
                        <?= $logo; ?>
                    <?php endif; ?>

                    <!-- text logo -->
                    <?php if (isset($logoConf['logoText'])) : ?>
                        <?php
                        $logoTextClass = 'title-text' . (isset($logoConf['logoPosition']) ? ' ' . $logoConf['logoPosition'] : '');
                        ?>
                        <p class="<?= $logoTextClass ?>"><?= $logoConf['logoText'] ?></p>
                    <?php endif; ?>

                    <!-- signature logo -->
                    <?php if (isset($logoConf['logoSignature'])) : ?>
                        <?php
                        if (!$disablePlatformLinks) {
                            $logoSignature = Html::img($logoConf['logoSignature'], [
                                'class' => 'img-responsive logo-signature',
                                'alt' => $logoAlt
                            ]);
                            if (isset($logoConf['logoPosition'])) {
                                if (isset($logoOptions['class'])) {
                                    $logoOptions['class'] .= ' ' . $logoConf['logoPosition'];
                                } else {
                                    $logoOptions['class'] = $logoConf['logoPosition'];
                                }
                            }
                            $signature = Html::a($logoSignature, $logoUrl, $logoOptions);
                        } else {
                            $signature = Html::img($logoConf['logoSignature'], [
                                'class' => 'img-responsive logo-signature' . (isset($logoConf['logoPosition']) ? ' ' . $logoConf['logoPosition'] : ''),
                                'alt' => $logoAlt
                            ]);
                        }
                        ?>
                        <?= $signature ?>
                    <?php endif; ?>

                <?php endforeach; ?>

            <?php else : ?>

                <!-- old logo configurations -->
                <?php

                /** @var string $logo */
                $logo = isset(Yii::$app->params['logo']) ?
                    Html::img(Yii::$app->params['logo'], [
                        'class' => 'img-responsive logo-amos',
                        'alt' => 'logo ' . Yii::$app->name
                    ])
                    : '';

                /** @var string $signature */
                $signature = isset(Yii::$app->params['logo-signature']) ?
                    Html::img(Yii::$app->params['logo-signature'], [
                        'class' => 'img-responsive logo-signature',
                        'alt' => 'logo ' . Yii::$app->name
                    ])
                    : '';

                $logoUrl = !empty(Yii::$app->params['disablePlatformLinks']) ? null : Yii::$app->homeUrl;
                $logoOptions = [];
                $title = BaseAmosModule::t('amoscore', 'vai alla home page');
                $logoOptions['title'] = $title;
                $signatureOptions = $logoOptions;

                if (!$disablePlatformLinks) {
                    $logoOptions['class'] = 'container-logo-amos';
                    $signatureOptions['class'] = 'container-logo-signature';
                    $logo = Html::a($logo, $logoUrl, $logoOptions);
                    $signature = Html::a($signature, $logoUrl, $signatureOptions);
                }

                ?>
                <?php if (!isset(Yii::$app->params['logo']) && !isset(Yii::$app->params['logo-text'])) : ?>
                    <div class="logo-text text-center">
                        <?= (!$disablePlatformLinks) ? Html::a(Yii::$app->name, $logoUrl, $logoOptions) : Html::tag('p', Yii::$app->name); ?>
                    </div>
                <?php endif; ?>

                <!-- params logo -->
                <?php if (isset(Yii::$app->params['logo'])) : ?>
                    <?= $logo; ?>
                <?php endif; ?>

                <!-- params logo-text -->
                <?php if (isset(Yii::$app->params['logo-text'])) : ?>
                    <p class="title-text"><?= Yii::$app->params['logo-text'] ?></p>
                <?php endif; ?>

                <!-- params signature logo -->
                <?php if (isset(Yii::$app->params['logo-signature'])) : ?>
                    <?= $signature ?>
                <?php endif; ?>

            <?php endif; ?>

            </div>
        </div>