<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\amos\cwh\assets\MaterialDesignAsset;
use yii\web\View;
use open20\design\Module;

$materialDesignAsset = MaterialDesignAsset::register($this);

$this->registerJs(<<<JS

    $("input[id^='card-input-checkbox-']").click(function() {
        $("#card-preference-checkbox-id-" + $(this).val()).toggleClass("active");
   });

JS
, View::POS_READY);

?>

<div class="row">
    <?php
    foreach ($choices as $topic) :
        $topicId = $topic->getId();
        $checked = (in_array($topicId, $selected[$rootId])) ? 'checked="checked"' : '';
    ?>

        <div class="<?= $classCardContainer ?>">
            <div class="card-wrapper card-preference card-preference-checkbox pb-3 <?= (!empty($checked))? 'active': '' ?>" id='card-preference-checkbox-id-<?= $topicId ?>'>
                <div class="card rounded bg-card-preference-bg">
                    
                    <div class="card-body flexbox">
                            <div class="icon-name flexbox">
                                <?php
                                    if(!empty($topic->getIcon())){
                                ?>
                                    <div class="categoryicon-top">
                                        <svg class="icon icon-sm icon-primary" role="img" aria-label="Icona per attivare una preferenza">
                                            <use xlink:href="<?= $materialDesignAsset->baseUrl . $baseIconsUrl . $topic->getIcon() ?>"></use>
                                        </svg>
                                    </div>
                                <?php
                                    }
                                ?>
                                <span class="h6 m-b-0 m-l-5"><?= $topic->getLabel() ?></span>
                            </div>
                            <div class=" p-icon p-toggle p-plain" id="card-input-toggle-<?= $topicId ?>">
                                <input type="checkbox" id="card-input-checkbox-<?= $topicId ?>" name="<?= $inputName ?>[simple-choice][<?=$rootId?>][]" value="<?= $topicId ?>" <?= $checked ?> />     
                            </div>
                        </div>
                </div>
            </div>
        </div>
    <?php
    endforeach;
    ?>
</div>
