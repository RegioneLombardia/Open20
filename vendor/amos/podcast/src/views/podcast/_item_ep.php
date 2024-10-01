<?php
/**
 * @var $model \amos\podcast\models\PodcastEpisode
 */

use yii\helpers\Html;
use amos\podcast\Module;
/* use amos\podcast\assets\ModulePodcastAsset;
ModulePodcastAsset::register($this); */

?>
<?php
$url = '/img/img_default.jpg';
if ($model->mainImage) {
    $url = $model->mainImage->getWebUrl();
}
?>

<div class="podcast-item-container my-3 row">
    <div class="col-sm-4 col-md-3 mb-3 mb-md-0 container-img">
        <a href="#" title="Vai alla puntata">
            <img class="img-responsive" alt="immagine podcast" src="<?= $url ?>" >
        </a>
    </div>
    <div class="col-sm-8 col-md-9 d-flex flex-column container-info"> 
        <a href="/podcast/podcast-episode/view?id=<?= $model->id?>" title="Vai alla puntata <?= $model->title?>" class="link-list-title mr-3 mb-0">
            <h4 class="h5 title-one-line"><?= $model->title?></h4>
        </a>
        <p class="h5 m-b-10">(<?= $model->duration?>’) - <?= \Yii::$app->formatter->asDate($model->published_at, 'long') ?></p>
        <p class="m-t-0 d-flex align-items-end podcast-description title-three-line"><?= $model->abstract ?></p>
    </div>
</div>