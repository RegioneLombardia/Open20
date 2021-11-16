<?php

use backend\modules\landings\models\PreferenceLandingRating;

/**
 * @var \yii\web\View $this
 */
?>
<?php if (!is_null($dataProvider)) {
    if ($dataProvider->getTotalCount() == 1) :
        $landing = isset($dataProvider->getModels()[0]) ? $dataProvider->getModels()[0] : null;

        if (!is_null($landing) && $landing->rating_flag):
            ?>
            <div class="<?= $cssClass ?>">
                <div class="clearfix"></div>

                <div class="news-home container">
                    <h3 class="mb-4 h1"><span class="mdi mdi-poll-box"></span> Rating</h3>

                    <?php
                    if (!empty($landing->rating_description)):
                        ?>
                        <div><?= $landing->rating_description ?></div>
                        <?php
                    endif;
                    ?>
                    <?php
                    // lettura del cookie per vedere se ha già compilato oppure no!
                    $cookies = \Yii::$app->request->cookies;

                    $cookieValue = null;
                    if ($cookies->has(PreferenceLandingRating::FRONTEND_RATING_COOKIE_NAME)) {
                        $arrayCookieContent = unserialize($cookies->getValue(PreferenceLandingRating::FRONTEND_RATING_COOKIE_NAME));
                        if (isset($arrayCookieContent[$landing->id]) && is_array($arrayCookieContent[$landing->id])) {
                            $cookieValue = $arrayCookieContent[$landing->id];
                        }
                    }
                    $ratingReadOnly = false;
                    $ratingValue = 0;
                    $thankyouMessage = null;

                    if (!is_null($cookieValue) && (isset($cookieValue['idRating'])) && !empty($cookieValue['idRating'])) {
                        $rating = PreferenceLandingRating::findOne(['id' => $cookieValue['idRating']]);
                        if (!empty($rating)) {
                            $ratingReadOnly = true;
                            $ratingValue = $rating->rating;
                            $thankyouMessage = 'La tua opinione risulta già espressa in precedenza';
                        }
                    }

                    echo $this->render('@frontend/modules/backendobjects/frontend/views/default/_ratingContent', [
                        'landing' => $landing,
                        'ratingReadOnly' => $ratingReadOnly,
                        'ratingValue' => $ratingValue,
                        'thankyouMessage' => $thankyouMessage,
                    ]);
                    ?>


                </div>

            </div>
        <?php
        else:

            $js = <<<JS
            $('.section-rating').attr('style',"display:none !important");
JS;
            $this->registerJs($js);

        endif;

    else:

        $js = <<<JS
    $('.section-rating').attr('style',"display:none !important");
JS;
        $this->registerJs($js);

    endif;
}
?>