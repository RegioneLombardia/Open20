<?php
/**
 * @var $this \luya\cms\base\PhpBlockView
 */
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

$googleMapsKey = "";
if (!is_null(Yii::$app->params['googleMapsApiKey'])) {
    $googleMapsKey = Yii::$app->params['googleMapsApiKey'];
} elseif (Yii::$app->params['google_places_api_key']) {
    $googleMapsKey = Yii::$app->params['google_places_api_key'];
} elseif (!is_null(Yii::$app->params['google-maps']) && !is_null(Yii::$app->params['google-maps']['key'])) {
    $googleMapsKey = Yii::$app->params['google-maps']['key'];
}
$idCanvas = empty($data['idcanvas']) ? "map-canvas" : $data['idcanvas'] ;

?>
<?php if ($canSeeBlock): ?>
    <style>
        .container {
            position: relative;
            width: 100%;
            margin: auto;
        }

        #map-container {
            padding: 56.25% 0 0 0;
            height: 0;
            position: relative;
        }

        #<?=$idCanvas?> {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
        }
    </style>

    <script>
        
        function initMap() {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'address': 'roma'
            }, function (results, status) {
                var map;
                if (status == google.maps.GeocoderStatus.OK) {
                    var myOptions = {
                        zoom: <?= $data['zoom'] ?>,
                        center: results[0].geometry.location,
                        mapTypeId: '<?= $data['maptype'] ?>'
                    }
                    map = new google.maps.Map(document.getElementById("<?=$idCanvas?>"), myOptions);
    
                    <?php
                    $i = 1;
                    foreach ($data['points'] as $point) {
                        ?>
                                geocoder.geocode({
                                    'address': '<?= $point['address'] ?>'
                                }, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {

                                        var marker<?= $i++ ?> = new google.maps.Marker({
                                            map: map,
                                            position: results[0].geometry.location,
                                            title: '<?= $point['description'] ?>'
                                        });
                                    }
                                });
                    <?php
                    }
                    ?>
                }
            });

        }

    </script>

    <script>

        function loadJS(file, callback) {
            // DOM: Create the script element
            var jsElm = document.createElement("script");
            // set the type attribute
            jsElm.type = "application/javascript";
            // make the script element load file
            jsElm.src = file;
            jsElm.onload = callback;
            // finally insert the element to the body element in order to load the script
            document.body.appendChild(jsElm);
        }

        if (typeof google !== 'object') {
            loadJS("https://maps.googleapis.com/maps/api/js?key=<?= $googleMapsKey ?>&callback=initMap", function () {
                initMap();
            });
        }else{
            initMap();
        }
    </script>

    <div id="map-container">
        <div id="<?=$idCanvas?>"></div>

    </div>
<?php endif; ?>

