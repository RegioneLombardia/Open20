<?php

use app\modules\uikit\Uikit;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\utility\DesignIcon;

/**
 * @var $this \yii\web\View
 * @var $data
 */

$currentAsset = BootstrapItaliaDesignAsset::register($this);


$linkClass = is_array($data['class']) ? implode(' ',$data['class']) : $data['class'];
if($data['link_type']=='button'){
    $fillingClass= '';
   
    if($data['button_style']){
        if($data['button_filling']){
            $fillingClass=$data['button_filling'] . '-';
        }
        $fillingClass=$fillingClass.$data['button_style'];
    }
   
   
    $linkClass=$linkClass . ' btn'. ' btn-'. $fillingClass . ' btn-' . $data['button_size'];
    if(!empty($data['icon_name'])){
        $linkClass=$linkClass . ' btn-icon';
    }
}

$linkTarget = $data['link_target'];
$linkClass = $data['is_disabled'] ? $linkClass . ' disabled' : $linkClass;

$iconClass='icon';

$css = <<<CSS
    svg{
        fill:currentColor !important;
    }
CSS;
$this->registerCss($css, [],'svg-curr-color-id');

?>
<?php if($data['HTML_type']=='button'): ?>
    <button class="<?= $linkClass ?> open20-cms-button" 
    role="button" 
    style="<?= $this->extraValue('style') ?>"
    >
<?php else: ?>
    <a class="<?= $linkClass ?> open20-cms-button" 
    href="<?= $data['link'] ?>" 
    target="<?= $linkTarget ?>" 
    title="<?= strip_tags($data['text_content']) ?>" 
    <?=$data['is_disabled'] ? 'aria-disabled="true"' : '';?>
    style="<?= $this->extraValue('style') ?>"
    >
<?php endif; ?>
    <?php if($data['icon_name']): ?>
        <?=  DesignIcon::show($data['icon_name'], DesignIcon::ICON_MD, 'icon', $currentAsset); ?>
    <?php endif; ?>
    <?php if($data['image']): ?>
        <img src="<?= $data['image'] ?>" class="img-fluid <?= $adatta . $imgClass ?> <?= $data['rounded_image'] ?>" alt="<?= $data['image_alt'] ?>">
    <?php endif; ?>
    <?php if($data['text_content']): ?>
        <span><?= $data['text_content'] ?></span>
    <?php endif; ?>
<?php if($data['HTML_type']=='button'): ?>
    </button>
<?php else: ?>
    </a>
<?php endif; ?>
