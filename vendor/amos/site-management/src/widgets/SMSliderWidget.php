<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\widgets
 * @category   CategoryName
 */

namespace amos\sitemanagement\widgets;

use amos\sitemanagement\exceptions\SiteManagementException;
use amos\sitemanagement\models\PageContent;
use amos\sitemanagement\models\SiteManagementSection;
use amos\sitemanagement\models\SiteManagementSlider;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Url;

/**
 * Class SMPageContentWidget
 * @package amos\sitemanagement\widgets
 */
class SMSliderWidget extends Widget
{
    /**
     * @var string $layout
     */
    public $layout = '{pageContent}';

    public $optionsImg = [
        'class' => '',
    ];

    public $optionsVideo = [
        'class' => '',
        'fullscreen' => true,
        'autoplay' => true,

    ];

    /**
     * @var integer
     */
    public $sliderId;

    /**
     * @var string $tag
     */
    private $section;

    /**
     * @var PageContent $model
     */
    private $model;

    /**
     * @var
     */
    private $viewPath;

    public $crop = 'social';

    /**
     * @throws SiteManagementException
     */
    public function init()
    {
        parent::init();

        // to find the slider you can use the slider ID or the section id
        if(!empty($this->sliderId)){
            $slider = SiteManagementSlider::findOne($this->sliderId);
            $this->model = $slider;
        } else {
			
			$this->sliderId = 'carouselHeader-' . substr(uniqid(), -3);
		
            if (is_null($this->section)) {
                throw new SiteManagementException('SMPageContentWidget: missing tag');
            }

            if (!is_string($this->section)) {
                throw new SiteManagementException('SMPageContentWidget: tag is not a string');
            }

            $section = SiteManagementSection::find()->andWhere(['name' => $this->section])->one();
            if (!empty($section)) {
                $slider = $section->siteManagementSlider;
                $this->model = $slider;
            }
        }
        $this->configureViewPath();
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $tag
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * @param string $tag
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     *
     */
    public function configureViewPath(){
        $module = \Yii::$app->getModule('sitemanagement');
        if($module){
            if(empty($this->viewPath)){
                $this->viewPath = $module->defaultSliderView;
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function run()
    {
        $slider = $this->model;
        /** @var $slider SiteManagementSlider */
        if($slider){
            if($slider->getSliderElems()->count() > 0){
                $this->registerJsVideo();
                return $this->renderSlider($slider);
            }
        }
        return '';
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSlider($slider) {
        return $this->render($this->viewPath, ['slider' => $slider, 'optionsVideo' => $this->optionsVideo, 'optionsImg' => $this->optionsImg, 'key' => $this->sliderId, 'crop' => $this->crop]);
    }

    /**
     * Register js for the auto play on youtube
     */
    public function registerJsVideo(){
        $autoplay = (!empty($this->optionsVideo['autoplay'] && $this->optionsVideo['autoplay'] === true)) ? 'true' : 'false';
		$domain = Url::to('/','https');
        $js = <<<JS
        var player = [];
        var idsVideo = [];

        $('#{$this->sliderId}').on('slid.bs.carousel', function(event){
            var videoDiv = event.relatedTarget;
            var videoUploaded = $(videoDiv).find('video');
            var video = $(videoDiv).find('iframe');
            if($autoplay === 'true'){
                // ---------- for uploaded video
                  if(videoUploaded.length > 0){
                    $(this).carousel('pause');
                    $(videoUploaded).get(0).play();
                }
                //pause all videos if you click forward or backward in the carousel
                else {
                    $('video').each(function(){
                        $(this).get(0).pause();
                    });
                }

                // ------------- for youtube
                if(video.length > 0){
                    $(this).carousel('pause');
                    player[$(video).attr('id')].playVideo();
                }
               //pause all videos if you click forward or backward in the carousel
                else {
                    idsVideo.forEach(function(element){
                        player[element].pauseVideo();
                    });
                }
            }
        });

        function loadScript() {
            if (typeof(YT) == 'undefined' || typeof(YT.Player) == 'undefined') {
                var tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                loadPlayer();
            }
        }
        
        //for youtube
        function onPlayerStateChange(event){
            if(event.data == YT.PlayerState.PLAYING){
                 $('#{$this->sliderId}').carousel('pause');
            }
            if(event.data == YT.PlayerState.PAUSED || event.data == YT.PlayerState.ENDED){
                $('#{$this->sliderId}').carousel('cycle');
            }
        }
        
        //event play/pause for uploaded video
        $('.uploaded-video').on('play', function(){
             $('#{$this->sliderId}').carousel('pause');
        });
        
          $('.uploaded-video').on('pause', function(){
            $('#{$this->sliderId}').carousel('cycle');
        });

        function loadPlayer() {
                window.onYouTubePlayerAPIReady = function() {
                    $('.youtube-video','#{$this->sliderId}').each(function(){
						
                         playerYt = new YT.Player($(this).attr('id'),{
                                    events: {
                                        'onStateChange': onPlayerStateChange
                                    },
                                    host: '{$domain}',
                                    playerVars: { 'origin':'{$domain}' },
				});
                         player[$(this).attr('id')] = playerYt;
                         idsVideo.push($(this).attr('id'));
                    });
                };
        }
       loadScript();
	 
JS;

    $this->getView()->registerJs($js,View::POS_READY);
    }


}
