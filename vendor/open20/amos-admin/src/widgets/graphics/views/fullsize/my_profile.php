<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;


/**
 * @var \yii\web\View $this
 * @var WidgetGraphicMyProfile $widget
 * @var \open20\amos\admin\models\UserProfile $userProfile
 */

?>

<div class="box-widget-header">
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title col-xs-10 nop">
            <?= AmosIcons::show('user', ['class' => 'am-2'], AmosIcons::IC); ?>
            <?= AmosAdmin::tHtml('amosadmin', 'Il mio profilo') ?>
        </h2>
    </div>
    <div class="read-all"><?= Html::a(AmosAdmin::t('amosadmin', '#go_to_your_profile'), ['/'.AmosAdmin::getModuleName().'/user-profile/update', 'id' => $userProfile->id], ['class' => '']); ?></div>
</div>

<div class="box-widget myprofile">
    <section>
        <div class="list-items">
        <h2 class="sr-only"><?= AmosAdmin::t('amosadmin', 'Il mio profilo') ?></h2>
            <div class="widget-listbox-option" role="option">
                <article class="wrap-item-box">
                    <div class="icon-admin-wgt">
                        <span class="pull-left">
                            <?= Html::a(
                                $widget->getUserProfileRoundImage(),
                                ['/'.AmosAdmin::getModuleName().'/user-profile/update', 'id' => $userProfile->id],
                                ['title' => AmosAdmin::t('amosadmin', '#go_to_your_profile'), 'class' => 'container-square-img-sm']
                            ) ?>
                        </span>
                    </div>
                    <div class="text-admin-wgt">
                        <h3 class="box-widget-subtitle"><?= $userProfile->nomeCognome ?></h3>
                        <p class="box-widget-text"><?= $widget->getBoxWidgetText() ?></p>
                    </div>
                </article>
            </div>
        </div>
    </section>
</div>
