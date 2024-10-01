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
use open20\amos\community\assets\AmosCommunityAsset;
use open20\amos\community\models\Community;
use open20\amos\community\widgets\CommunityCardWidget;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\utilities\CurrentUser;


use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

AmosCommunityAsset::register($this);

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities $widget
 * @var string $toRefreshSectionId
 * @var bool $linkToSubcommunities
 */

//$moduleCommunity = \Yii::$app->getModule(AmosCommunity::getModuleName());
//$communitiesModels = $communitiesList->getModels();
$toRefreshSectionId = 'container_comments';

?>
<?php if (!CurrentUser::isPlatformGuest()){ ?>
<div class="grid-item grid-item--height2">
    <div class="box-widget my-community">
        <div class="box-widget-toolbar row nom">
            <h2 class="box-widget-title col-xs-10 nop"><?= AmosCommunity::t('amoscommunity', 'Comments') ?></h2>
          
        </div>
        <section>
            <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
            <div role="listbox">
                test
            </div>
            
            <?php Pjax::end(); ?>
        </section>
        <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
    </div>
</div>
<?php } ?>
