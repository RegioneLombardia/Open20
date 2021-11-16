<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities $widget
 * @var string $toRefreshSectionId
 */

?>

<div class="box-widget">
    <div class="box-widget-toolbar row nom">
        <h2 class="box-widget-title col-xs-10 nop"><?= AmosCommunity::t('amoscommunity', 'My communities') ?></h2>
        <?= WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosCommunity::className(),
            'actionRoute' => ['/community/community-wizard/introduction'],
            'toRefreshSectionId' => $toRefreshSectionId,
            'permissionCreate' => 'COMMUNITY_CREATE'
        ]); ?>
    </div>
    <section>
        <!--<h2 class="sr-only"><?php //= AmosCommunity::t('amoscommunity', 'My communities') ?></h2>-->
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <div role="listbox">
            <?php
            if (count($communitiesList->getModels()) == 0):
                echo '<div class="list-items"><h2 class="box-widget-subtitle"></h2>' . AmosCommunity::t('amoscommunity', 'My communities') . '</div>';
            else:
                ?>
                <div class="list-items">
                    <?php
                    foreach ($communitiesList->getModels() as $community):
                        /** @var Community $community */
                        ?>
                        <div class="widget-listbox-option row" role="option">
                            <article class="col-xs-12 nop">
                                <div class="container-img">
                                    <?= \open20\amos\community\widgets\CommunityCardWidget::widget(['model' => $community, 'imgStyleDisableHorizontalFix' => true]) ?>
                                </div>
                                <div class="container-text">
                                    <div class="col-xs-12 nopr">
                                        <!--                                        <p>< ?= Yii::$app->getFormatter()->asDatetime($community->created_at); ?></p>-->
                                        <h2 class="box-widget-subtitle">
                                            <?php
                                            if (strlen($community->name) > 60) {
                                                $stringCut = substr($community->name, 0, 60);
                                                echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                            } else {
                                                echo $community->name;
                                            }
                                            ?>
                                        </h2>
                                    </div>
                                    <div class="col-xs-12 nopr">
                                        <div class="box-widget-text">
                                            <?php
                                            if (strlen($community->description) > 60) {
                                                $stringCut = substr($community->description, 0, 60);
                                                echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                            } else {
                                                echo $community->description;
                                            }
                                            ?>
                                        </div>
                                        <h3 class="members"><?= count($community->communityUsers) . " " . AmosCommunity::t('amoscommunity', 'MEMBERS') ?></h3>
                                    </div>
                                </div>
                                <div class="col-xs-12 nop">
                                <span class="pull-right">
                                    <?= Html::a(AmosCommunity::t('amoscommunity', 'Sign in'),
                                        ['/community/join', 'id' => $community->id],
                                        ['class' => 'btn btn-navigation-primary']); ?>
                                </span>
                                </div>
                            </article>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <?= Html::a(
                AmosCommunity::t('amoscommunity', 'View Community List'),
                ['/community/community/my-communities'],
                ['class' => 'read-all']); ?>
                <?php
            endif;
            ?>
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>