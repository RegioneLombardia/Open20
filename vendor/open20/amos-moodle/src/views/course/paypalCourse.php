<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\icons\AmosIcons;
use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\Topic;

$this->title = $course->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="moodle-not-enrolled">
<?php if (!$courseEnrolled) { ?>
    <div class="text-center">
        <h2 class="text-danger"><?= AmosIcons::show('alert-circle', ['class' => 'am-4']) ?>
            <br /><?= AmosMoodle::_t('#warning') ?>
        </h2>
        <?= AmosIcons::show('chevron-down', ['class' => 'am-4 text-danger']) ?>
        <h3><strong><?= AmosMoodle::_t('#gentle_user') ?></strong>,
            <br /><?=  AmosMoodle::_t('#paypal_course', [
                'pp_cost' => $pp_cost,
                'pp_currency' => $pp_currency
            ]) ?>
            <br />
            <br />
            <?= AmosMoodle::_t('#need_to_pay') ?>
        </h3>

        <?= Html::a(AmosMoodle::_t('#paypal_payment_btn', [
                'modelClass' => 'Moodle Topic',
            ]), 
            $paypalUrl,
            ['class' => 'btn btn-amministration-primary'])
        ?>
        <br /> <br />
        <p><?php //= AmosMoodle::_t('#subscription_already_request') ?></p>
    </div>
    <?php
} else {
?>
    <div class="text-center">
        <h2 class="text-success"><?= AmosIcons::show('check-circle', ['class' => 'am-4']) ?>
            <br /><?= AmosMoodle::_t('#congrats') ?>
        </h2>
        <?= AmosIcons::show('chevron-down', ['class' => 'am-4 text-success']) ?>
        <h3><strong><?= AmosMoodle::_t('#gentle_user') ?></strong>,
            <br /><?= AmosMoodle::_t('#user_already_subscribed') ?>
        </h3>
    </div>
<?php } ?>

</div>
