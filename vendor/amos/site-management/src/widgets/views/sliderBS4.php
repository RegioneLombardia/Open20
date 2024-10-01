<?php
/**
 * @var $sliderElements \amos\sitemanagement\models\SiteManagementSliderElem
 * @var $slider \amos\sitemanagement\models\SiteManagementSlider
 */
use amos\sitemanagement\Module;
use \amos\sitemanagement\models\SiteManagementSliderElem;

$sliderElements = $slider->getSliderElems()->orderBy('order ASC')->all();

?>
<?php if(count($sliderElements)>1): ?>
  <div class="it-carousel-wrapper it-carousel-landscape-abstract-three-cols">
    <div class="it-carousel-all owl-carousel it-card-bg">
      <?php
                      $i = 0;
                      foreach ($sliderElements as $element){
                          $active = false;
                          if($i < 3){
                              $active = true;
                          }
                          if($element->type == SiteManagementSliderElem::TYPE_IMG){
                              echo $this->render('_item_imageBS4', ['element' => $element, 'active' => $active]);
                          } else {
                              echo $this->render('_item_videoBS4', ['element' => $element, 'active' => $active, 'optionsVideo' => $optionsVideo]);
                          }
                          $i++;
                      }
        ?>



    </div>
  </div>
<?php else: ?>
  <div class="single-item">
    <?php
                        $i = 0;
                        foreach ($sliderElements as $element){
                            $active = false;
                            if($i < 3){
                                $active = true;
                            }
                            if($element->type == SiteManagementSliderElem::TYPE_IMG){
                                echo $this->render('_item_imageBS4', ['element' => $element, 'active' => $active]);
                            } else {
                                echo $this->render('_item_videoBS4', ['element' => $element, 'active' => $active, 'optionsVideo' => $optionsVideo]);
                            }
                            $i++;
                        }
    ?>
  </div>
<?php endif;

