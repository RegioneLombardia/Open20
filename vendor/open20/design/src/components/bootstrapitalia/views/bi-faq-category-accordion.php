<?php

use open20\design\Module;
use open20\design\assets\BootstrapItaliaDesignAsset;


/**
 * Parameters:
 * @var int $htmlId - Base html id to give to accordion
 * @var int $sectionTitle - section title
 * @var string $buttonUrl - BUTTON url at the end of base accordion
 * @var string $buttonText - BUTTON text at the end of base accordion
 *
 *
 * @var array $list array of array of 'title' and 'content'
 * [
 *   [
 *     ['title' => 'title 1'],
 *     ['content' => 'lorem ipsum 1 ...']
 *   ],
 *   [
 *     ['title' => 'title 2'],
 *     ['content' => 'lorem ipsum 2...']
 *   ],
 *   [
 *     ['title' => 'title 3'],
 *     ['content' => 'lorem ipsum 3...']
 *   ],
 * ]
 */

$elementIdCollapse = $htmlId . '-collapse';
?>


<div id="collapseDiv2" class="collapse-div collapse-background-active" role="tablist">

    <div class="collapse-header" id="<?= $htmlId ?>">
        <button data-toggle="collapse" data-target="#<?= $elementIdCollapse ?>" aria-expanded="false"
                aria-controls="<?= $elementIdCollapse ?>">
            <?= $sectionTitle ?>
        </button>
    </div>
    <div id="<?= $elementIdCollapse ?>" class="collapse" role="tabpanel" aria-labelledby="<?= $htmlId ?>">
        <div class="collapse-body">
            <div id="collapseDiv3" class="collapse-div" role="tablist">

                <?php
                $i = 0;
                if (is_array($list)):
                    foreach ($list as $element):
                        $subHtmlId = $htmlId . '-' . $i;
                        $subCollapseHtmlId = $htmlId . '-collapse-' . $i;

                        $title = $element['title'] ?? '';
                        $content = $element['content'] ?? '';
                        ?>
                        <div class="collapse-header" id="<?= $subHtmlId ?>">
                            <button data-toggle="collapse" data-target="#<?= $subCollapseHtmlId ?>" aria-expanded="false" aria-controls="<?= $subCollapseHtmlId ?>">
                                <span class="mdi mdi-help-circle mr-1"></span><?= $title ?>
                            </button>
                        </div>
                        <div id="<?= $subCollapseHtmlId ?>" class="collapse" role="tabpanel" aria-labelledby="<?= $subHtmlId ?>">
                            <div class="collapse-body">
                                <?= $content ?>
                            </div>
                        </div>
                        <?php
                        $i++;
                    endforeach;
                endif;
                ?>
            </div>
            <?php
            if (!empty($buttonUrl)):
                $text = $buttonText ?? '';
                ?>
                <a href="<?= $buttonUrl ?>" class="btn btn-sm btn-secondary mt-3"><?= $text ?></a>
                <?php
            endif;
            ?>
        </div>

    </div>

</div>