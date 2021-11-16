<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core
 * @category   CategoryName
 */

use yii\helpers\Html;
?>

<?php if (isset(Yii::$app->params['logoConfigurations']) && is_array(Yii::$app->params['logoConfigurations']) && !empty(Yii::$app->params['logoConfigurations'])) : ?>
    <!-- new logo configurations -->
    <?php
    $logoConfigurations = Yii::$app->params['logoConfigurations'];
    ?>
    <?php foreach ($logoConfigurations as $i => $logoConf) : ?>

        <?php if ($i == 0 && !$logoConf['positionTop']) : ?>
            <?php
            //DEFAULT URL
            $logoUrl = Yii::$app->homeUrl;

            //CHECK URL
            if (isset($logoConf['logoUrl'])) {
                if ($logoConf['logoUrl'] == 'frontendUrl') {
                    $logoUrl = Yii::$app->params['platform']['frontendUrl'];
                } elseif ($logoConf['logoUrl'] == 'backendUrl') {
                    $logoUrl = Yii::$app->params['platform']['backendUrl'];
                } else {
                    $logoUrl = $logoConf['logoUrl'];
                }
            }

            //DEFAULT OPTIONS LOGO
            $logoOptions = [];
            $logoAlt = Yii::$app->name;
            $title = '';

            //CHECK TITLE LINK
            $logoOptions['title'] = $title;
            if (isset($logoConf['logoTitle'])) {
                $logoOptions['title'] = (isset($logoConf['logoTranslateCategory']) ? Yii::t($logoConf['logoTranslateCategory'], $logoConf['logoTitle']) : $logoConf['logoTitle']);
            }

            //CHECK TARGET LINK
            if (isset($logoConf['logoUrlTarget'])) {
                $logoOptions['target'] = $logoConf['logoUrlTarget'];
            }

            //CHECK ADDITIONAL CLASS
            if (isset($logoConf['logoClass'])) {
                if (isset($logoOptions['class'])) {
                    $logoOptions['class'] .= ' ' . $logoConf['logoClass'];
                } else {
                    $logoOptions['class'] = $logoConf['logoClass'];
                }
            }

            //CHECK ALT IMAGE
            if (isset($logoConf['logoAlt'])) {
                if (isset($logoConf['logoTranslateCategory'])) {
                    $logoAlt = Yii::t($logoConf['logoTranslateCategory'], $logoConf['logoAlt']);
                } else {
                    $logoAlt = $logoConf['logoAlt'];
                }
            }
            ?>

            <!-- IF IS SET LOGO IMAGE -->
            <?php if (isset($logoConf['logoImg'])) : ?>
                <?php

                $logoImg = Html::img($logoConf['logoImg'], ['alt' => $logoAlt]);

                if (isset($logoConf['logoUrl'])) {
                    $logo = Html::a(Html::tag('div', $logoImg, ['class' => 'it-brand-text d-block']), $logoUrl, $logoOptions);
                } else {
                    $logo = Html::tag('div', $logoImg, ['class' => 'it-brand-text d-block']);
                }
                ?>
                <?= $logo; ?>
            <?php endif; ?>

            <!-- IF IS SET LOGO TEXT -->
            <?php if (isset($logoConf['logoText'])) : ?>
                <?php
                $logoTextClass = 'it-brand-text' . (isset($logoConf['logoClass']) ? ' ' . $logoConf['logoClass'] : '');
                ?>
                <div class="<?= $logoTextClass ?>">
                    <strong class="text-secondary">
                        <?= (isset($logoConf['logoUrl'])) ? Html::a($logoConf['logoText'],$logoUrl, $logoOptions) : $logoConf['logoText'] ?>
                    </strong>
                </div>
            <?php endif; ?>

            <!-- CHECK NO LOGO FOUND -->
            <?php if (!isset($logoConf['logoImg']) && !isset($logoConf['logoText'])) : ?>
                <div class="it-brand-text">
                    <?= (isset($logoConf['logoUrl'])) ? Html::a(Yii::$app->name, $logoUrl, $logoOptions) : Html::tag('p', Yii::$app->name); ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
        <?php $i++; ?>
    <?php endforeach; ?>
<?php endif; ?>