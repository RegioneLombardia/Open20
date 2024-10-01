<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<style>
    .active {
        background-color: green !important;
    }
</style>

<div id="countdownEvento2020_1" class="player uk-margin-bottom" style="display:none"></div>
<div id="countdownEvento2020_2" class="player uk-margin-bottom" style="display:none"></div>
<div id="countdownEvento2020_3" class="player uk-margin-bottom" style="display:none"></div>
<div id="countdownEvento2020_4" class="player uk-margin-bottom" style="display:none"></div>
<div id="countdownEvento2020_5" class="player uk-margin-bottom" style="display:none"></div>

<div class="programma-streaming uk-margin-top uk-background-primary uk-padding-small">
    <div class="uk-margin-right" style="color:#FFF">Prossimi streaming: seleziona l'evento che vuoi seguire</div>
    <button id="btn-1" onclick="changeVideo('countdownEvento2020_1', 'btn-1')" class="uk-button uk-button-secondary"><strong>Intervento istituzionale
            DG</strong><br>Giovedì 18 - ore 16:00
    </button>
    <button id="btn-2" onclick="changeVideo('countdownEvento2020_2', 'btn-2')" class="uk-button uk-button-secondary"><strong>RE-Hub-ILITY</strong><br>Giovedì 18 -
        ore 17:00
    </button>
    <button id="btn-3" onclick="changeVideo('countdownEvento2020_3', 'btn-3')" class="uk-button uk-button-secondary"><strong>SAMBA</strong><br>Venerdì 19 - ore
        11:00-12:00
    </button>
    <button id="btn-4" onclick="changeVideo('countdownEvento2020_4', 'btn-4')" class="uk-button uk-button-secondary"><strong>LOMBHE@T</strong><br>Venerdì 19 - ore
        12:00-13:00
    </button>
    <button id="btn-5" onclick="changeVideo('countdownEvento2020_5', 'btn-5')" class="uk-button uk-button-secondary"><strong>BASE5G</strong><br>Venerdì 19 - ore
        17:00-18:30
    </button>
</div>

<script>

    var arrayPlayer = [
        {
            'id': "countdownEvento2020_1",
            'date': new Date("March 11, 2021 06:00:00"),
            'date_end': new Date("March 11, 2021 06:50:00"),
            'htmlvideo': '<iframe style="margin:auto;" width="1300" height="630" src="https://player.vimeo.com/video/483584257" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            'btnClass' : 'btn-1'
        },
        {
            'id': "countdownEvento2020_2",
            'date': new Date("March 11, 2021 10:00:00"),
            'date_end': new Date("March 11, 2021 10:43:00"),
            'htmlvideo': '<iframe style="margin:auto;" width="1300" height="630" src="https://player.vimeo.com/video/483584257" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            'btnClass' : 'btn-2'

        },
        {
            'id': "countdownEvento2020_3",
            'date': new Date("March 11, 2021 11:00:00"),
            'date_end': new Date("March 11, 2021 11:50:00"),
            'htmlvideo': '<iframe style="margin:auto;" width="1300" height="630" src="https://player.vimeo.com/video/483584257" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            'btnClass' : 'btn-3'

        },
        {
            'id': "countdownEvento2020_4",
            'date': new Date("March 11, 2021 20:00:00"),
            'date_end': new Date("March 11, 2021 20:43:00"),
            'htmlvideo': '<iframe style="margin:auto;" width="1300" height="630" src="https://player.vimeo.com/video/483584257" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            'btnClass' : 'btn-4'

        },
        {
            'id': "countdownEvento2020_5",
            'date': new Date("March 11, 2021 13:00:00"),
            'date_end': new Date("March 11, 2021 13:50:00"),
            'htmlvideo': '<iframe style="margin:auto;" width="1300" height="630" src="https://player.vimeo.com/video/483584257" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            'btnClass' : 'btn-5'

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
                '<div class="col-md-6 video-streaming"><div class="img-video-bg uk-margin"><img src="/it/attachments/file/view?hash=a4d85f5e786217b41daed8fe0388c7ca&amp;canCache=1" class="el-image" alt=""> </div></div>' +
                '<div class="col-md-6 video-commenti-streaming uk-margin-top">' +
                '<h2 class="uk-margin-large-top uk-margin-medium-bottom uk-text-muted">EVENTO LIVE IN:</h2>' +
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
            if(distance2 < 0){
                // console.log('next');
                // console.log(this.id);
                // console.log(nowDate + ' now ');
                // console.log(this.date_end + ' end ');
                setNext = true;
            }
            else{
                setNext = false;
            }
        } else {
            if(setNext){
                console.log('set');
                activePlayer = this;
                setNext = false;
            }
        }
    });
    $('#'+activePlayer.btnClass).addClass("active");
    $('#'+activePlayer.id).show();


    // funzione per cambiare video
    function changeVideo(id, btn_id){
        $('.programma-streaming button').removeClass('active');
        $('.player').hide();
        $('#'+id).show();
        $('#'+btn_id).addClass('active');

    }

</script>
