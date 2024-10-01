<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics\views
 * @category   CategoryName
 */
use open20\amos\community\AmosCommunity; 
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;
use open20\amos\comments\AmosComments;
use open20\amos\comments\widgets\CommentsWidget;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\utilities\CurrentUser;

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities $widget
 * @var string $toRefreshSectionId
 * @var bool $linkToSubcommunities
 */
//$moduleCommunity   = \Yii::$app->getModule(AmosCommunity::getModuleName());
//$communitiesModels = $communitiesList->getModels();
$toRefreshSectionId = 'container_comments';
$model = open20\amos\community\models\Community::findOne($widget->community);
?>
<?php if (!CurrentUser::isPlatformGuest()){ ?>
    <div class="comments-widget box-widget-column my-community container">
        <section>
            <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
            <?php if (false): ?>
                <div class="list-items list-empty"><h3><?= AmosCommunity::t('amoscommunity', '#noCommunity') ?></h3></div>
            <?php endif; ?>
            <div class="list-items">        
                <div class="widget-listbox-option" role="option">
                    <article class="wrap-item-box">
                        <?php
                        $commentsModule = \Yii::$app->getModule(AmosComments::getModuleName());
                        if (!is_null($commentsModule)) {
                            echo CommentsWidget::widget([
                                'model' => $model,
                                'useDesign' => true,
                                'noAttach' => true,                           
                                'pageSize' => $widget->numberToView,
                                'moderator' => CommunityUtil::isManagerUser($model, 'id'),
                            ]);
                        }
                        ?>
                    </article>
                </div>
            </div>
            <?php Pjax::end(); ?>
        </section>
    </div>
<?php } ?>