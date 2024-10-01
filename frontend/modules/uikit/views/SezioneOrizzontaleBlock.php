<?php

/**
 * @var $this object
 * @var $data array
 */

use trk\uikit\Uikit;
use app\modules\uikit\BaseUikitBlock;

$id = $data['id'];
$class = $data['class'][0];
$style = [];

$canSeeBlock = true;
$visibility = $data['visibility'];


switch ($visibility) {
    case 'guest':
        $canSeeBlock = Yii::$app->user->isGuest;
        break;
    case 'logged':

        $canSeeBlock = !Yii::$app->user->isGuest;
        $n_class = $this->varValue('addclass');
        if ($canSeeBlock && !empty($n_class)) {
            $canSeeBlock = BaseUikitBlock::visivility($n_class);
        }
        break;
}


$permissions = $data['rbac_permissions'];
if (!empty($permissions)) {
    $canSeeBlock = false;
    foreach ($permissions as $permission) {
        if (Yii::$app->user->can($permission['rbac_permission'])) {
            $canSeeBlock = true;
            break;
        }
    }
}

$activateGradient = false;
if (!empty($data['gradient_align']) || !empty($data['color_gradient_1']) || !empty($data['color_gradient_2']) || !empty($data['gradient_percent'])) {
    $activateGradient = true;
}
$activateBackground = false;
if (!empty($data['opacity']) || !empty($data['brandbook_background'])) {
    $activateBackground = true;
}
if ($activateGradient || $activateBackground) :

    $gradientAlign = $data['gradient_align'] ? $data['gradient_align'] : '90deg';
    $gradientPercent = $data['gradient_percent'] ? $data['gradient_percent'] . '%' : '';
    $gradientFirstColor = $data['color_gradient_1'] ? $data['color_gradient_1'] : '#000';
    $gradientSecondColor = $data['color_gradient_2'] ? $data['color_gradient_2'] : 'transparent';


    /** @var \yii\web\View $this */
    $css = <<<CSS
        .gradient-section,
        .bg-opacity-section {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            z-index: 0;
        }
        .gradient-section ~ *, .bg-opacity-section ~ * {
                z-index: 1;
            }
CSS;
    $this->registerCss($css);

?>
<?php endif; ?>
<?php


?>
<?php if ($canSeeBlock) : ?>
    <div class="uk-section <?= ($activateGradient || $activateBackground) ? 'position-relative' : ''; ?> <?= $class ? $class : ''; ?> " <?php if (!empty($data['image'])) : ?> style="

            <?= $data['image'] ? 'background-image:url(' . $data['image'] . ');'  : ''; ?>
            <?= $data['image_size'] ? 'background-size:' . $data['image_size'] . ';'  : ''; ?>
            <?= $data['image_position'] ? 'background-position:' . $data['image_position'] . ';'  : ''; ?>
            <?= $data['image_repeat'] ? 'background-repeat: repeat;'  : 'background-repeat: no-repeat;'; ?>
            " <?php endif; ?>>
        
        <?php if ($activateBackground) : ?>
            <div class="bg-opacity-section <?= ($data['brandbook_background']) ?  'bg-'.$data['brandbook_background'] : ''; ?> " style="<?= ($data['opacity']) ? 'opacity:' . $data['opacity'] . '%;' : 'opacity:100%'; ?>"></div>
        <?php endif; ?>
        <?php if ($activateGradient) : ?>
            <div class="gradient-section <?= $data['opacity'] ? 'opacity-' . $data['opacity'] : ''; ?>" style="background:linear-gradient(<?= $gradientAlign  ?>, <?= $gradientFirstColor ?> <?= $gradientPercent ?>, <?= $gradientSecondColor ?>);"></div>
        <?php endif; ?>

        <?php if (!$data['not_embed_container']) : ?>
            <div class="uk-container <?= $activateBackground ? 'position-relative' : ''; ?>">
            <?php endif; ?>
            <?= $data['content'] ?>
            <?php if (!$data['not_embed_container']) : ?>
            </div>
        <?php endif; ?>





    </div>
<?php endif;
