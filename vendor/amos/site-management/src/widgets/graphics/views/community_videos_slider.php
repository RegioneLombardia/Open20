<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\widgets\graphics\views
 * @category   CategoryName
 */

use amos\sitemanagement\models\SiteManagementCommunitySliderMm;
use amos\sitemanagement\Module;
use amos\sitemanagement\widgets\SMSliderWidget;
use amos\sitemanagement\widgets\SMSliderWidgetBS4;
use open20\amos\community\AmosCommunity;
use yii\web\View;
use amos\sitemanagement\assets\ModuleSiteManagementInterfacciaAsset;
use open20\amos\core\utilities\CurrentUser;
use yii\helpers\Html;

ModuleSiteManagementInterfacciaAsset::register($this);

/**
 * @var View $this
 * @var \amos\sitemanagement\widgets\graphics\WidgetCommunitySliderImage $widget
 * @var integer $communityId
 */

$userModule = CurrentUser::getUserProfile();

if (!is_null($communityId)) {
    $communityImageSlider = SiteManagementCommunitySliderMm::find()
        ->andWhere(['community_id' => $communityId])
        ->andWhere(['type' => SiteManagementCommunitySliderMm::TYPE_VIDEOS])
        ->one();

    $slider = $communityImageSlider->siteManagementSlider;
}
?>

<style>
    .widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(1):checked ~ .tab-panels > .tab-panel:nth-child(1) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(3):checked ~ .tab-panels > .tab-panel:nth-child(2) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(5):checked ~ .tab-panels > .tab-panel:nth-child(3) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(7):checked ~ .tab-panels > .tab-panel:nth-child(4) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(9):checked ~ .tab-panels > .tab-panel:nth-child(5) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(11):checked ~ .tab-panels > .tab-panel:nth-child(6) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(13):checked ~ .tab-panels > .tab-panel:nth-child(7) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(15):checked ~ .tab-panels > .tab-panel:nth-child(8) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(17):checked ~ .tab-panels > .tab-panel:nth-child(9) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(19):checked ~ .tab-panels > .tab-panel:nth-child(10) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(21):checked ~ .tab-panels > .tab-panel:nth-child(11) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(23):checked ~ .tab-panels > .tab-panel:nth-child(12) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(25):checked ~ .tab-panels > .tab-panel:nth-child(13) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(27):checked ~ .tab-panels > .tab-panel:nth-child(14) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(29):checked ~ .tab-panels > .tab-panel:nth-child(15) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(31):checked ~ .tab-panels > .tab-panel:nth-child(16) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(33):checked ~ .tab-panels > .tab-panel:nth-child(17) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(35):checked ~ .tab-panels > .tab-panel:nth-child(18) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(37):checked ~ .tab-panels > .tab-panel:nth-child(19) {
  display: block;
}
.widget-graphic-cms-bi-less .tabs-video-language .tabset > input:nth-child(39):checked ~ .tab-panels > .tab-panel:nth-child(20) {
  display: block;
}
</style>

<?php if (!empty($communityImageSlider) && (!empty($slider)) && ($slider->getSliderElems()->count() > 0)) { ?>
    <div class="widget-graphic-cms-bi-less container">
        <div class="page-header">
            <div class="flexbox">
                <div>
                    <p class="h2 text-uppercase mb-0 no-margin"><?= Module::t('amossitemanagement', 'Video') ?></p>
                </div>
            </div>
        </div>



        <?php if (($slider->getSliderElems()->count() == 1)) : ?>
            <?php $slider = $slider->getSliderElems()->one() ?>
            <?php if ($slider->url_video == '#') : ?>
                <div class="placeholder-wrapper">
                    <img class="img-responsive img-placeholder-streaming" src="/img/placeholder-video-streaming.jpg" alt="placeholder streaming" />
                    <p class="text-placeholder-streaming"><?= Module::t('amoscore', 'in diretta streaming da {luogo} all\'orario indicato', ['luogo' => $slider->title]) ?></p>
                </div>

            <?php elseif ((strip_tags(strtolower($slider->description))) == 'youtube') : ?>
                <div class="placeholder-wrapper m-t-30">
                    <a href="<?= $slider->url_video ?>" title="Guarda la live su Youtube" target="_blank">
                        <img class="img-responsive img-placeholder-streaming" src="/img/placeholder-video-streaming-active.jpg" alt="placeholder streaming" />
                    </a>
                </div>
            <?php else : ?>
                <div class="video-wrapper">
                    <ul class="m-t-25">
                        <?php
                        $match = [];
                        $idVideo = '';
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $slider->urlEmbeddedVideo, $match);
                        $idVideo = $match[1];
                        echo ' <li class="m-b-25"><iframe src="' . $slider->urlEmbeddedVideo . '?rel=0" frameborder="0" width="100%" height="350" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>';
                        ?>
                    </ul>
                </div>
            <?php endif ?>
        <?php else : ?>
            <div class="video-wrapper">
                <div class="tabs-video-language m-t-25">
                    <div class="tabset">
                        <?php
                        $i = 0;
                        foreach ($slider->getSliderElems()->orderBy('order')->all() as $sliderCiao) {
                            $match = [];
                            $idVideo = '';
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $sliderCiao->urlEmbeddedVideo, $match);
                            $idVideo = $match[1];
                            $isFirst = ($i == 0) ? 'checked' : '';
                            echo '<input type="radio" name="tabset" id="' . $sliderCiao->title . '" aria-controls="' . $sliderCiao->title . '"' . ' ' . $isFirst . '><label for="' . $sliderCiao->title . '">' . $sliderCiao->title . '</label>';
                            $i++;
                        }
                        ?>
                        <div class="tab-panels">
                            <?php
                            foreach ($slider->getSliderElems()->orderBy('order')->all() as $sliderPippo) {
                                $match = [];
                                $idVideo = '';
                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $sliderPippo->urlEmbeddedVideo, $match);
                                $idVideo = $match[1];
                                if (strstr(strip_tags(strtolower($sliderPippo->description)), 'youtube')) {
                                    echo '<section id="' . $sliderPippo->title . '" class="tab-panel"><div class="placeholder-wrapper m-t-30">
                                    <a href="' . $sliderPippo->url_video . '" title="Guarda la live su Youtube" target="_blank">
                                        <img class="img-responsive img-placeholder-streaming" src="/img/placeholder-video-streaming-active.jpg" alt="placeholder streaming" />
                                    </a>
                                </div></section>';
                                } else {
                                    echo '<section id="' . $sliderPippo->title . '" class="tab-panel"><iframe src="' . $sliderPippo->urlEmbeddedVideo . '?rel=0" frameborder="0" width="100%" height="550" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></section>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php }

/*
else{?>
    <div class="no-result-message mx-auto container">
        <div class="page-header">
            <div class="flexbox">
                <div>
                    <p class="h2 text-uppercase mb-0 no-margin"><?= Module::t('amossitemanagement', 'Video') ?></p>
                </div>
            </div>
        </div>
                
        <div class="flexbox flexbox-column">
                <p class="h4">Non ci sono contenuti che corrispondono ai tuoi interessi. </p>
                <div>
                    <?php if (CurrentUser::isPlatformGuest()){ ?><!--guest va all'accedi e secondo non si vede -->
                        <a class="btn btn-primary" href="/site/login">sii il primo a scrivere un contenuto</a>
                    <?php }else{ ?><!--loggato: vede entrambe: crea/update-->
                        
                        <a href="/it/sitemanagement/site-management-community-slider-elements/videos" class="btn btn-primary">sii il primo a scrivere un contenuto</a>
                        <a href="/amosadmin/user-profile/update?id=<?=$userModule->id ?>" class="btn btn-secondary"> aggiorna i tuoi interessi </a>
                        
                    <?php } ?>
                </div>
        </div>
    </div>
<?php }

*/
?>