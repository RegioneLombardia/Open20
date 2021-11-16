<?php

use yii\widgets\Pjax;

/**
 * @var boolean $ratingReadOnly
 * @var integer $ratingValue
 * @var \backend\modules\landings\models\PreferenceLanding $landing
 */

$myName = 'landing-rating-value';
Pjax::begin([
    'id' => 'id-rating-pjax',
    'timeout' => false,
    'formSelector' => '#landing-rating-form-id', // this form is submitted on change
    'submitEvent' => 'change',
    'enablePushState' => false,
    'clientOptions' => [
        'method' => 'POST'
    ]
]);

if (!empty($landing)) :
?>

    <form id="landing-rating-form-id" action="/site/rating" method="get">
        <input type="hidden" name="landing_id" value="<?= $landing->id ?>">
        <?php
        echo \open20\design\components\bootstrapitalia\Rating::widget([
            'name' => $myName,
            'readonly' => $ratingReadOnly,
            'value' => $ratingValue,
        ]);
        ?>
        <div class="clearfix"></div>
    </form>

    <?php
    if (!empty($thankyouMessage)) :
    ?>
        <div class="clearfix"></div>
            <div class="alert alert-success mt-2" role="alert"><?= $thankyouMessage ?></div>
        
    <?php
    endif;
    ?>

<?php
else :
    echo ''; // landing non definita ERRORE!
endif;
Pjax::end();

?>