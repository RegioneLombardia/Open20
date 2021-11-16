<?php

use backend\modules\landings\models\PreferenceLandingRating;

/**
 * @var \yii\web\View $this
 */
?>
<?php if (!is_null($dataProvider)) {
    if ($dataProvider->getTotalCount() == 1) :
        $landing = isset($dataProvider->getModels()[0]) ? $dataProvider->getModels()[0] : null;

        if (!is_null($landing) && !empty($landing->playlist_youtube_url)):

            $url_components = parse_url($landing->playlist_youtube_url);
            parse_str($url_components['query'], $params);
            $tokenYoutube = $params['list'];


            ?>
            <div class="<?= $cssClass ?>">
                <div class="clearfix"></div>

                <div class="news-home container">
                    <h3 class="mb-3 h1"><span class="mdi mdi-youtube"></span> Playlist YouTube</h3>

                    <?php
                    if (!empty($landing->playlist_youtube_description)):
                        ?>
                        <div class="mb-3 lead"><p><?= $landing->playlist_youtube_description ?></p></div>
                    <?php
                    endif;

                    // ---------- fine desc inizio iframe ---------

                    if (!empty($tokenYoutube)):
                        // IFRAME YOUTUBE
                        echo '<iframe width="100%" height="500" 
                            src="https://www.youtube.com/embed/videoseries?list='.$tokenYoutube.'" 
                            title="YouTube video player" frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>';
                    else:
                        echo 'Link malformato, impossibile visualizzare la playlist';
                    endif;
                    ?>
                </div>

            </div>
        <?php
        else:

            $js = <<<JS
            $('.section-playlist-youtube').attr('style',"display:none !important");
JS;
            $this->registerJs($js);

        endif;

    else:

        $js = <<<JS
    $('.section-playlist-youtube').attr('style',"display:none !important");
JS;
        $this->registerJs($js);

    endif;
}
?>