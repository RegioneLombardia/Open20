<?php

use trk\uikit\Uikit;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;
use yii\helpers\Url;


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

$id    = $data['id'];
$containerClass = $data['class'][0];
$attrs = $data['attrs'];
$attrs_grid = [];

$lang =  substr(Yii::$app->language, 0, 2);

if($lang != ''){
  $lang = '/'.$lang;
}

$current_page_url = \Yii::$app->params['platform']['frontendUrl'] . $lang . Url::current();
$sharing_link = '';



?>
<?php if ($canSeeBlock): ?>
    <div class="social-share d-inline-flex align-items-center <?= $containerClass ?>">
        <span
            class="mr-1 <?= $data['share_label_class']? $data['share_label_class'] : '' ?>"
        >
            <?=  $data['share_label'] ?>
        </span>
        <?php foreach ($data['items'] as $item) : ?>

            <?php if ($item['social']): ?>
                <?php
                    switch($item['social'])
                    {
                        case 'facebook':
                            $sharing_link="https://www.facebook.com/sharer/sharer.php?u=" . $current_page_url;
                            break;

                        case 'twitter':
                            $sharing_link="https://twitter.com/intent/tweet?text=" . $current_page_url;
                            break;

                        case 'linkedin':
                            $sharing_link="https://www.linkedin.com/sharing/share-offsite/?url=" . $current_page_url;
                            break;

                        case 'whatsapp':
                            $sharing_link="https://api.whatsapp.com/send?text=" . $current_page_url;
                            break;

                        case 'telegram':
                            $sharing_link="https://t.me/share/url?url=" . $current_page_url;
                            break;
                    }
                ?>

                <a href="<?=$sharing_link?>"
                target="_blank"
                title="<?= $item['content']  ?>"
                class="d-inline-flex text-decoration-none"
                >
            <?php endif; ?>

                <?php switch($item['icon_type'])
                    {
                        case 1:
                            echo DesignIcon::show($item['icon_name'], DesignIcon::ICON_BI, 'icon icon-sm '.$item['icon_class'], $currentAsset);
                        break;
                        case 2:
                            echo DesignIcon::show($item['icon_name'], DesignIcon::ICON_MD, 'icon icon-sm '.$item['icon_class'], $currentAsset);
                        break;
                    }
                ?>
            <?php if ($item['social']): ?>
                </a>
            <?php endif; ?>

        <?php endforeach ?>
    </div>
<?php endif; ?>
