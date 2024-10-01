<?php

use app\modules\cms\utility\StorageUtility;
use app\modules\uikit\Uikit;
use open20\amos\documenti\models\Documenti;
use open20\design\assets\BootstrapItaliaDesignAsset;


/**
 * @var $this
 * @var $data
 */
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$id    = $data['id'];
$class = $data['class'];
$attrs = $data['attrs'];

// Accordion
//$attrs['uk-accordion'] = Uikit::json(Uikit::pickBool($data, ['multiple', 'collapsible']));
?>
<div class="attachments-detail">
    <ul<?= Uikit::attrs(compact('id', 'class'), $attrs) ?>>
    <?php foreach ($data['items'] as $item) : ?>
        <li class="attachments-detail__item">
            <?php
            $infos = [];
            if ($item['attachment']) {                
                $document = Documenti::findOne(['file_cms_id' => $item['attachment']]);
            }
            ?>
            <?php
                if(is_null($document))
                {
                    $fileSystem = new StorageUtility();
                    $infos = $fileSystem->getFileInfo($item['attachment']);
            ?>
                <a class="d-flex" href="<?= isset($infos['url']) ?  $infos['url'] : '' ?>" title="<?= $infos['name']?>">
                    <svg class="icon icon-primary">
                        <use xlink:href=" <?=$bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-clip"></use>
                    </svg>
                    <div class="news-allegato-name"><?= $item['title']?></div>
                </a>
            <?php
                } else {
                    $url = Yii::$app->getModule('backendobjects')::getBaseUrl($document->id, $blockItemId, $sitemap = false, $relatedDitailPage = false, $isrest = false); 
                    $mainDoc = $document->getDocumentMainFile();
                ?>
                <a class="d-flex" href="<?= isset($url) ?  $url : '' ?>" title="<?= $document->titolo ?>">
                    <svg class="icon icon-primary">
                        <use xlink:href=" <?=$bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-clip"></use>
                    </svg>
                    <div class="news-allegato-name"><?= $mainDoc->name ?>
                </div>
                    
                    
                    
                </a>
            <?php
                }
            ?>
		</li>
    <?php endforeach ?>
    </ul>
</div>
