<?php

use app\modules\uikit\Uikit;

/**
 * @var $this
 * @var $data
 */

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
                $fileSystem = new app\modules\cms\utility\StorageUtility();
                $infos = $fileSystem->getFileInfo($item['attachment']);
            }
            ?>
            <a href="<?= isset($infos['url']) ?  $infos['url'] : '' ?>" title="<?= $infos['name']?>">
                <div class="news-allegato-icon">
                    <i class="glyphicon glyphicon-file"></i>
                </div>
                <div class="news-allegato-name"><?= $item['title']?>.<?= $infos['type']?></div>
                <div class="news-allegato-kb"><?= isset($infos['size']) ? $infos['size'] :  '' ?></div>
            </a>
            
		</li>
    <?php endforeach ?>
    </ul>
</div>
