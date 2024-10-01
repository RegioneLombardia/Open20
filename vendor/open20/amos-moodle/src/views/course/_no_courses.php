<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle
 * @category   views
 */

use open20\amos\moodle\AmosMoodle;

$this->title = AmosMoodle::_t('#courses');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var yii\web\View $this
 * @var ebike\assets\models\EbikeAssets $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="moodle-category-form col-xs-12 nop">
    <div class="col-xs-12">
        <h2><?= AmosMoodle::_t('#warning_no_courses_available_yet') ?></h2>
    </div>
    <hr />
    <div class="col-xs-12">
        <?= AmosMoodle::_t('#message_no_courses_available_yet') ?>
    </div>
    <div class="col-xs-12 nop">
        <a class="btn btn-secondary pull-right" href="/"><?= AmosMoodle::_t('#btn_cancel') ?></a>
    </div>
</div>
