<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use app\assets\ResourcesAsset;
use app\modules\cms\helpers\CmsHelper;
use open20\amos\news\AmosNews;
use luya\helpers\Url;

$resourceAsset = ResourcesAsset::register($this);


$truncate = 250;
?>

<?php if ($detailPage) { ?>
<?php $route = Yii::$app->getModule('backendobjects')::getSeoUrl($model->getPrettyUrl(), $blockItemId); ?>
<a href="<?= $route ?>" title="Leggi tutto">
    <?php } ?>

    <div class="content-image">
        <?php foreach ($viewFields as $field): ?>
            <?php
            switch ($field->type) {
                case "IMAGE":
                    if (!is_null($model[$field->name])) {
                        if (!is_null($model[$field->name])) {
                            $url = $model[$field->name]->getWebUrl('square_medium', false, true);
                        }
                        ?>
                        <?php
                    } else {
                        $url = $resourceAsset->baseUrl . '/img/img_default.jpg';
                    }
                    $contentImage = CmsHelper::img($url, [
                        'class' => 'el-image',
                        'alt' => AmosNews::t('amosnews', 'Immagine della notizia')
                    ]);
//                    $contentImageLink = CmsHelper::a($contentImage,$route,['title' => 'Leggi tutto']);
                    ?>
                    <?= $contentImage; ?>
                    <?php
                    break;
                default:
                    ?>
                    <!--                --><?php //$model[$field->name];
                    ?>
                <?php } ?>
        <?php endforeach; //foreach on $viewFields ?>


    </div>
    <div class="el-content">

        <?php
        if (isset($model->newsCategorie) && !empty($model->newsCategorie)):

            $classCat = '';
            switch ($model->news_categorie_id) {
                case 4 :
                    $classCat = 'cat_agrifood';
                    break;
                case 5 :
                    $classCat = 'cat_aerospazio';
                    break;
                case 6 :
                    $classCat = 'cat_chimica';
                    break;
                case 9 :
                    $classCat = 'cat_mobilita';
                    break;
                case 8 :
                    $classCat = 'cat_fabbrica';
                    break;
                case 7 :
                    $classCat = 'cat_energia';
                    break;
                case 11 :
                    $classCat = 'cat_smart';
                    break;
                case 10 :
                    $classCat = 'cat_scienze';
                    break;
                case 12 :
                    $classCat = 'cat_ambienti';
                    break;
                case 13 :
                    $classCat = 'cat_storie_innovazione';
                    break;
                case 15 :
                    $classCat = 'cat_campus_party';
                    break;
                case 14 :
                    $classCat = 'cat_lab_lombardia';
                    break;
                case 16 :
                    $classCat = 'cat_stati_generali';
                    break;
                case 17 :
                    $classCat = 'cat_premio_int';
                    break;
                case 18 :
                    $classCat = 'cat_foro';
                    break;
                case 19 :
                    $classCat = 'cat_legge_reg';
                    break;
                case 20 :
                    $classCat = 'cat_redazione';
                    break;
                case 1 :
                    $classCat = 'cat_community';
                    break;
            }
            ?>
            <div class="<?=$classCat?>"><p class="category"><span><?=$model->newsCategorie->titolo?></span></p></div>
        <?php endif; ?>

        <?php if (CmsHelper::in_arrayCmsViewField('titolo', $viewFields) && isset($model->title) && !empty($model->title)): ?>
                <!-- <?php /*if ($detailPage) { */ ?>
            <a class="el-link" href="<? /*= $route; */ ?>" class="title" title="leggi tutto">
        --><?php /*} */ ?>
            <h2 class="el-title"><?= $model->getTitle(); ?></h2>
            <?php /*if ($detailPage) { */ ?><!--
            </a>
        --><?php /*} */ ?>
        <?php endif; ?>

        <?php if (CmsHelper::in_arrayCmsViewField('sottotitolo', $viewFields) && isset($model->sottotitolo) && !empty($model->sottotitolo)): ?>
            <h3 class="el-subtitle"><?= $model->sottotitolo ?></h3>
        <?php endif; ?>

        <?php if (CmsHelper::in_arrayCmsViewField('data_pubblicazione', $viewFields) && isset($model->data_pubblicazione) && !empty($model->data_pubblicazione)): ?>
            <div class="published-by"><?= Yii::$app->getFormatter()->asDate($model->data_pubblicazione) ?></div>
        <?php endif; ?>

        <!-- <div class="date-end">< ?//=$model->getPublicatedFrom(); ?></div>-->
        <!-- <div class="date">< ?//= Yii::$app->getFormatter()->asDate($model->getPublicatedAt()); ?></div>-->
    </div>

    <?php if ($detailPage) { ?>
</a>
<?php } ?>
