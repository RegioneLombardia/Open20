<?php

use yii\web\View;
use app\modules\uikit\Uikit;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;


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
 * @var $this
 * @var $data
 */
$id = $data['id'];
//$class = $data['class'];
$class = (isset($data['class'][0]) ? $data['class'][0] : '');
//$tabID = 'tab-' . substr(uniqid(), -3);
$oldTabId=rand();

// Tabs
//$attrs['uk-subnav uk-subnav-pill'] = Uikit::json(Uikit::pick($data, []));
//console.log($(this).data('tabcustom') +' this');

if ($data['tab_all_close']) {
    $class = $class . ' tab-all-close';

    $js = <<< JS
        var current_tab = null;
        var previous_tab = null;

        $('.tab-all-close [data-toggle=tab]').click(function(event){
          if ($(this).data('tabcustom') == 'active'){
                $($(this).attr("href")).removeClass('active');
                    $($(this).parent()).removeClass('active');
                    $(this).data('tabcustom', '');
                    if(current_tab == this){
                        event.preventDefault();
                        return false;
                    }

          } else {
             $(this).data('tabcustom', 'active');
         }
        });

        $('.nav-tabs a').on('shown.bs.tab', function (e) {
             current_tab = e.target;
             previous_tab = e.relatedTarget;
         });
JS;

    $this->registerJs($js, View::POS_READY);
}
?>
<?php if ($canSeeBlock): ?>
    <div class="tab-container <?=(!empty($data['is_vertical']))? 'd-flex' :''?>">
        <ul class="nav nav-tabs <?= $class ?> <?=(!empty($data['is_vertical']))? 'nav-tabs-vertical' :''?>" role="tablist">
            <?php foreach ($data['items'] as $key => $item) : 
                $tabId=$oldTabId+$key;

            ?>
                <li class="nav-item">
                    <?php if ($item['tab_image']) {

                        $attrs_image['class'][] = 'el-image';

                        if (Uikit::isImage($item['tab_image']) == 'gif') {
                            $attrs_image['uk-gif'] = true;
                        }
                        $item['tab_image'] = Uikit::image($item['tab_image'], $attrs_image);
                    }?>
                        <a class="nav-link <?= $item['tab_image_class'] ?> <?=($key==0)? 'active' :''?>" href="#tab<?= $tabId ?>" data-toggle="tab">
                            <p class="mb-0"><?= $item['title'] ?></p>
                        </a>
                    
                </li>


            <?php endforeach ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($data['items'] as $key => $item) : ?>  
                <?php $tabId=$oldTabId+$key;?>
                <div class="tab-pane p-4 fade  <?=($key==0)? 'show active' :''?>" id="tab<?= $tabId ?>">
                    <p>
                        <?= $item['content'] ?>
                    </p>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif; ?>
