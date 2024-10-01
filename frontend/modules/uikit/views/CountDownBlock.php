<?php

use yii\web\JqueryAsset;
use yii\web\View;
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

$this->registerAssetBundle(JqueryAsset::className(), View::POS_HEAD);
$count_id = empty($data['countdown_id']) ? "count_1" : $data['countdown_id'];
$width = empty($data['video_width']) ? "1300" : $data['video_width'];
$heigth = empty($data['video_height']) ? "630" : $data['video_height'];

$ics_str =  empty($data['url_ics'])  ? '' : '<a class="el-content uk-button uk-button-default uk-margin-large-top btn-save-date" href="'.$data['url_ics'] . '"><span class="mdi mdi-calendar"></span> <span>Salva l\'evento nel tuo calendario</span></a>';

?>

<?php if ($canSeeBlock): ?>

    <div id="<?=$count_id?>" class="player uk-margin-bottom" style="display:none"></div>
    <div > <?=$ics_str?></div>

    <script>

        var arrayPlayer = [
            {
                'id': "<?=$count_id?>",
                'date': new Date(<?=$data['date']?>*1000),
                'date_end': new Date(<?=$data['date_end']?>*1000),
                'htmlvideo': '<iframe src="<?=$data['url_video']?>" width="<?=$width?>" height="<?=$heigth?>" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
                'btnClass': 'btn-1'
            }

        ];

        $.each(arrayPlayer, function (e) {
            // Set the date we're counting down to
            var countDownDate = this.date.getTime();
            var playerID = this.id;
            var htmlVideo = this.htmlvideo;


            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);


                // Display the result in the element with id="demo"

                document.getElementById(playerID).innerHTML =
                        '<div class="row content-streaming uk-text-center">' +
                        '<div class="col-md-12 video-commenti-streaming uk-margin-top">' +
                        '<h2 class="uk-margin-medium-bottom uk-text-muted"><?=$data['title']?></h2>' +
                        '<div class="uk-grid-small uk-text-center uk-flex-center uk-child-width-auto" uk-grid>' +
                        '<div><div class="uk-countdown-number uk-countdown-days">' + days + '</div><div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Giorni</div></div>' +
                        '<div class="uk-countdown-separator">:</div>' +
                        '<div><div class="uk-countdown-number uk-countdown-hours">' + hours + '</div><div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Ore</div></div>' +
                        '<div class="uk-countdown-separator">:</div>' +
                        '<div><div class="uk-countdown-number uk-countdown-minutes">' + minutes + '</div><div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Minuti</div></div>' +
                        '<div class="uk-countdown-separator">:</div>' +
                        '<div><div class="uk-countdown-number uk-countdown-seconds">' + seconds + '</div><div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Secondi</div></div>' +
                        '</div>';
                '</div>';
                '</div>';
                // If the count down is finished, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById(playerID).innerHTML = htmlVideo;
                }
            }, 1000);
        });

        // setto al load della pagina il player attivo a seconda di data inzio/fine
        var setNext = false;
        var nowDate = new Date();
        var now = nowDate.getTime();
        var activePlayer = arrayPlayer[0];


        $.each(arrayPlayer, function (e) {
            // Find the distance between now and the count down date
            var dateEnd = this.date_end.getTime();
            var date = this.date.getTime();
            var distance = date - now;
            var distance2 = dateEnd - now;

            if (distance < 0) {
                // console.log(this.id + ' current');
                activePlayer = this;
                if (distance2 < 0) {
                    // console.log('next');
                    // console.log(this.id);
                    // console.log(nowDate + ' now ');
                    // console.log(this.date_end + ' end ');
                    setNext = true;
                } else {
                    setNext = false;
                }
            } else {
                if (setNext) {
                    console.log('set');
                    activePlayer = this;
                    setNext = false;
                }
            }
        });
        $('#' + activePlayer.btnClass).addClass("active");
        $('#' + activePlayer.id).show();


        // funzione per cambiare video
        function changeVideo(id, btn_id) {
            $('.programma-streaming button').removeClass('active');
            $('.player').hide();
            $('#' + id).show();
            $('#' + btn_id).addClass('active');

        }

    </script>
<?php endif; ?>