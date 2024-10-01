<?php
/**
 * @var $episode_id
 * @var $enable_cover
 */

use yii\helpers\Html;

$this->registerJsFile('https://widget.spreaker.com/widgets.js', ['async' => true]);

$config =
[
    'class' => 'spreaker-player',
    'data-resource' => 'episode_id='.$episode_id,
    'data-width' =>  !empty($pluginOptions['data-width']) ? $pluginOptions['data-width'] : '500px',
    'data-height' => !empty($pluginOptions['data-height']) ? $pluginOptions['data-height'] : '250px',
    'data-theme' => 'dark',
    'data-playlist' => 'false',
    'data-playlist-continuous' => 'false',
    'data-autoplay' => 'false',
    'data-live-autoplay' => 'false',
//    'data-chapters-image' => $enable_cover,
    'data-episode-image-position' => 'right',
    'data-hide-logo' => 'false',
    'data-hide-likes' => 'true',
    'data-hide-comments' => 'true',
    'data-hide-sharing' => 'true',
    'data-hide-download' => 'true',
//    'data-cover' => $url,
];
//pr($url);
if(!empty($url)){
    $config['data-chapters-image'] = $enable_cover;
    $config['data-cover'] = $url;
}
else {
    $config['data-width'] = '100%';
    $config['data-height'] = '100%';
}

//pr($config);
if(!empty($show_id) && empty($episode_id)){
    $config['data-resource'] = 'show_id='.$show_id;
}
?>
<?php echo Html::a('Ascolta "Podcast test" su Spreaker.', $model->url_spreaker, $config ) ?>