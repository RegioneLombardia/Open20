<?php

use trk\uikit\Uikit;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

 \open20\socialwall\assets\ModuleSocialWallAsset::register($this); 
$currentAsset = BootstrapItaliaDesignAsset::register($this);
use app\modules\uikit\BaseUikitBlock;

$canSeeBlock = true;
$visibility = $this->varValue('visibility');

switch($visibility){
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;          
    break;
    case 'logged':
        $canSeeBlock = !Yii::$app->user->isGuest; 
		$n_class = $this->varValue('addclass');
		if($canSeeBlock && !empty($n_class)){
			$canSeeBlock = BaseUikitBlock::visivility($n_class);
		}
    break;
}




/**
 * @var $this object
 * @var $data array
 */

$lang =  substr(Yii::$app->language, 0, 2);



$posts = $data['posts'];

if(is_null(\Yii::$app->getModule('socialwall'))) {
    $canSeeBlock = false;
}


?>
<?php if ($canSeeBlock): ?>
    
    <?php 
    
        /*echo \open20\socialwall\widgets\SocialwallFromModelWidget::widget([
            'model' => $model,
        ]);*/
    ?>

    <div class="col-xs-12 section-form social-wall">
        <?php
        $displayWidget = false;
        $blockId = $this->block->getEnvOption('id');
        $blockItem = \luya\cms\models\NavItemPageBlockItem::findOne(['id' => $blockId]);
        $page = null;
        $navItem = null;
        $navId = null;
        if(!empty($blockItem)) {
            $page = \luya\cms\models\NavItemPage::findOne(['id' => $blockItem->nav_item_page_id]);
            if(!empty($page)) {
                $navItem = \app\modules\cms\models\NavItem::findOne(['id' => $page->nav_item_id]);
            }
            if(!empty($navItem)) {
                $navId = $navItem->nav_id;
            }
        }
        if(!empty($navId)) {
            $displayWidget = true;
        }
        ?>
        <?php if($displayWidget): ?>
            <?php \open20\socialwall\assets\ModuleSocialWallAsset::register($this); ?>

            <div class="row">
                <div class="col-md-12">
                    <?=
                    \open20\socialwall\widgets\SocialwallFromModelWidget::widget([
                        'explicitModuleId' => 'cms',
                        'explicitModuleRecordId' => $navId,
                    ]);
                    ?>
                </div>
            </div>
        <?php endif; ?>
<!--        <h2 class="subtitle-form">--><?php //= Yii::t('backendobjects', 'Social Wall') ?><!--</h2>-->
<!--        <div class="row">-->
<!---->
<!--            <div class="col-xs-12">-->
<!--                <div class="social-container">-->
<!--                    --><?php //foreach($posts as $post): ?>
<!---->
<!--                        --><?php ////$this->render('socialwall/post', compact('item', 'post')) ?>
<!---->
<!--                    --><?php //endforeach; ?>
<!--                </div>-->
<!--            </div>-->
<!---->
<!--        </div>-->

        
    </div>
<?php endif; ?>