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
use open20\amos\community\models\Community;
use open20\amos\community\widgets\CommunityCardWidget;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\assets\DashboardFullsizeAsset;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;
use open20\amos\community\assets\AmosCommunityAsset;

\open20\amos\dashboard\assets\DashboardFullsizeAsset::register($this);
AmosCommunityAsset::register($this);

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var ActiveDataProvider $communitiesList
 * @var $titleSection string
 */
$moduleCommunity = \Yii::$app->getModule(AmosCommunity::getModuleName());
$communitiesModels = $communitiesList->getModels();

$linkCta = '';
$titleCreate = '';
$labelCreate = '';

?>

<div class="widget-graphic-cms-bi-less card-<?= 'my-communities' ?> container">
    <div class="page-header">
        <?=
        $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'titleSection' => $titleSection,
                'titleCreate' => $titleCreate,
                'linkCta' => $linkCta,
                'labelCreate' => $labelCreate,
                'hideCreate' => true,
            ]
        );
        ?>
    </div>
    <section>
        <div class="list-items clearfixplus">
            <?php
            /** @var Community $community */
            foreach ($communitiesModels as $community) { ?>
                <?php echo $this->render('@vendor/open20/amos-community/src/views/community/_icon',['model'=> $community])?>
            <?php } ?>
        </div>
    </section>
</div>
