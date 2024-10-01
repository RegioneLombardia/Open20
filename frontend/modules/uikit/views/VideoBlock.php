<?php

/**
 * @var $this \luya\cms\base\PhpBlockView
 */
use app\modules\uikit\BaseUikitBlock;

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch ($visibility) {
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;
        break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest;
        $n_class = $this->varValue('addclass');
        if ($canSeeBlock && !empty($n_class)) {
            $canSeeBlock = BaseUikitBlock::visivility($n_class);
        }
        break;
}
?>
<?php if ($canSeeBlock): ?>
    <div class="cookieconsent-optout-marketing">
        <?= \Yii::t('amosapp', "E' necessario 
    <a href=\"javascript:Cookiebot.renew()\">accettare i cookie di marketing</a>
    per visualizzare questo video.") ?>
    </div>
    <?php if ($this->extraValue('url')): ?>
        <?php if ($this->cfgValue('width')): ?>
            <div style="width:<?= $this->cfgValue('width'); ?>px">
            <?php endif; ?>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" data-cookieblock-src="<?= $this->extraValue('url'); ?>" frameborder="0" data-cookieconsent="marketing" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
            <?php if ($this->cfgValue('width')): ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php
 endif;