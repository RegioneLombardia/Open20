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
use open20\amos\news\AmosNews;
use open20\amos\community\models\Community;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;
use open20\amos\community\assets\AmosCommunityAsset;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\models\News;

\open20\amos\dashboard\assets\DashboardFullsizeAsset::register($this);

AmosCommunityAsset::register($this);
/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities $widget
 * @var string $toRefreshSectionId
 */

$moduleDocumenti = \Yii::$app->getModule(AmosCommunity::getModuleName());

?>
<div class="box-widget-header">
    <?php if(isset($moduleCommunity) && !$moduleCommunity->hideWidgetGraphicsActions) { ?>
        <?= WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosCommunity::className(),
            'actionRoute' => ['/community/community-wizard/introduction'],
            'toRefreshSectionId' => $toRefreshSectionId,
            'permissionCreate' => 'COMMUNITY_CREATE'
        ]); ?>
    <?php } ?>
    
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('community-consigliate', ['class' => 'am-2'], AmosIcons::IC)?>
            <?= AmosCommunity::tHtml('amoscommunity', 'Communities Recommended') ?>
        </h2>
    </div>

    <?php
    if (count($communitiesList->getModels()) == 0) {
        $textReadAll = AmosCommunity::t('amoscommunity', '#addCommunity');
        $linkReadAll = ['/community/community-wizard/introduction'];

    } else {
        if ($linkToSubcommunities) {
            $textReadAll = AmosCommunity::t('amoscommunity', '#showAll');
            $linkReadAll = ['/community/subcommunities/index'];
        } else {
            $textReadAll = AmosCommunity::t('amoscommunity', '#showAll') . AmosIcons::show('chevron-right');
            $linkReadAll = ['/community/community/index'];
        }
    }?>
    <div class="read-all"><?= Html::a($textReadAll,$linkReadAll,['class' => '']); ?></div>
</div>
<div class="box-widget box-widget-column my-community">
    <section>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>

        <?php if (count($communitiesList->getModels()) == 0): ?>
            <div class="list-items list-empty"><h3><?= AmosCommunity::t('amoscommunity', '#noCommunity') ?></h3></div>
        <?php endif; ?>
            <div class="list-items">
                <?php
                foreach ($communitiesList->getModels() as $community):
                    /** @var Community $community */
                    ?>
                    <div class="widget-listbox-option" role="option">
                        <article class="wrap-item-box">
                            <div>
                                <div class="container-img">
                                    <?= \open20\amos\community\widgets\CommunityCardWidget::widget([
                                        'model' => $community,
                                        'imgStyleDisableHorizontalFix' => true,
                                        'avatarCropSize' => 'large'
                                    ]) ?>
                                </div>
                            </div>
                            <div class="container-text">
<!--                                <div class="box-widget-info-top">-->
<!--                                    <p>< ?= Yii::$app->getFormatter()->asDatetime($community->created_at); ?></p>-->
<!--                                </div>-->
                                <h2 class="box-widget-subtitle">
                                    <?php
                                    $decode_name = strip_tags($community->name);
                                    $decoded_name = '';
                                    if (strlen($decode_name) > 150) {
                                        $stringCut = substr($decode_name, 0, 150);
                                        $decoded_name = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                    } else {
                                        $decoded_name =  $decode_name;
                                    }
                                    ?>
                                    <?= Html::a($decoded_name, ['/community/community/view', 'id' => $community->id]) ?>
                                </h2>
                                <p class="box-widget-text">
                                    <?php
                                    $decode_description = strip_tags($community->description);
                                    if (strlen($decode_description) > 300) {
                                        $stringCut = substr($decode_description, 0, 300);
                                        echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                    } else {
                                        echo $decode_description;
                                    }
                                    ?>
                                </p>
                                <div class="box-widget-info-bottom">
                                    <span><?= $community->getCommunityUsers()->count() ?></span>
                                    <span><?= AmosCommunity::t('amoscommunity', 'Participants') ?></span>
                                </div>

                                    <div class="container-text-list">
                                            <?php
                                            $listaNews = [];
                                            $listaNews = \open20\amos\community\utilities\CommunityUtil::communityNews($community->id);

                                            if (!empty($listaNews)):
                                                ?>
                                                <h2 class="community-title-list"><?= AmosCommunity::t('amoscommunity', 'NewsCommuniy') ?></h2>
                                            <?php else : ?>
                                                <h2 class="community-title-list"><?= AmosCommunity::t('amoscommunity', 'NoNewsCommuniy') . $community->name ?> </h2>
                                            <?php
                                            endif;

                                            foreach ($listaNews as $news):
                                                /** @var News $news */
                                                ?>

                                            <div class="community-list-news">
                                                <div class="container-img">
                                                    <?php
                                                    $url = '/img/img_default.jpg';
                                                    if (!is_null($news->newsImage)) {
                                                        $url = $news->newsImage->getWebUrl('square_medium', false, true);
                                                    }
                                                    ?>
                                                    <?= Html::img($url, ['class' => 'img-responsive', 'alt' => AmosNews::t('amosnews', 'Immagine della notizia')]); ?>
                                                </div>
                                                <h2 class="community-title-news">
                                                    <?php
                                                    if (strlen($news->titolo) > 55) {
                                                        $stringCut = substr($news->titolo, 0, 55);
                                                        //echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                                         echo Html::a(substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ', ['/community/community/view', 'id' => $community->id]);
                                                    } else {
                                                       // echo $news->titolo;
                                                        echo Html::a($news->titolo, ['/community/community/view', 'id' => $community->id]);
                                                    }
                                                    ?>
                                                </h2>
                                            </div>

                                            <?php
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                        </article>
                    </div>
                <?php
                endforeach;
                ?>
            </div>
        <?php Pjax::end(); ?>
    </section>
</div>