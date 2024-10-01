<?php

use open20\design\Module;
use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$categorySize = (isset($categorySize)) ? $categorySize : 'h6';
$customCategoryClass = (isset($customCategoryClass)) ? $customCategoryClass : '';
$customTitleJobLabelClass = (isset($customTitleJobLabelClass)) ? $customTitleJobLabelClass : '';
$customTitleJobValueClass = (isset($customTitleJobValueClass)) ? $customTitleJobValueClass : '';

$titleSize = (isset($titleSize)) ? $titleSize : 'h5';
$descriptionSize = (isset($descriptionSize)) ? $descriptionSize : '';

$rating = (isset($rating)) ? $rating : 0; //firma alla fine della card


$buttons = (isset($buttons) ? $buttons : []);
$model = (isset($model) ? $model : null);
$actionModify = (isset($actionModify) ? $actionModify : null);
$actionDelete = (isset($actionDelete) ? $actionDelete : null);


$widthColumn = (isset($widthColumn)) ? $widthColumn : 'col-lg-4 col-md-6';

if (strlen($description) > 150) {
    $description = \open20\amos\core\utilities\StringUtils::truncateHTML(
        $description,
        147
    );
}
?>


<div class="card-container <?= $widthColumn ?>">
    <div class="card-wrapper card-space">
        <div class="card card-bg">
            <div class="card-body">
                <div class="other-info-item d-flex justify-content-end align-items-center">
                    <?php if (($newPublication)) : ?>
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                        );
                        ?>
                    <?php endif; ?>
                    <div class="ml-2">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                            [
                                'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete),
                                'model' => $model
                            ]
                        );
                        ?>
                    </div>

                </div>


                <div class="info-card">
                    <?php if (!$hideCategory) : ?>
                        <span class="category-card text-secondary text-uppercase badge font-weight-bold mb-0 <?= $categorySize ?> <?= $customCategoryClass ?>" <?php if ((!empty($colorBgCategory))) : ?> style="background-color: <?= $colorBgCategory ?> !important; padding: 4px; " <?php endif; ?>>
                            <strong <?php if ((!empty($colorTextCategory))) : ?> style="color: <?= $colorTextCategory ?> " <?php endif; ?>>
                                <?= $category ?>
                            </strong>
                        </span>
                    <?php endif; ?>
                    <?php if (!$hideTitleJob) : ?>
                        <p class="title-job mt-2">
                            <span class="<?= ($customTitleJobLabelClass) ? $customTitleJobLabelClass : '' ?>">
                                <?= $customTitleJobLabel ?>
                            </span>
                            <span class="<?= ($customTitleJobValueClass) ? $customTitleJobValueClass : '' ?>">
                                <?= $customTitleJobValue ?>
                            </span>
                        </p>
                    <?php endif; ?>


                </div>
                <div class="card-description pb-4">
                    <p class="title-card  font-weight-bold mb-2 <?= $titleSize ?>">
                        <?php if ($removeLink): ?>
                            <a href="<?= $url ?>" class="text-decoration-none" title="<?= Module::t('amosdesign', 'Vai al progetto' ). ' ' .$title ?>">
                                <?= $title ?>
                            </a>
                            
                        <?php else: ?>
                            <?= $title ?>
                        <?php endif; ?>
                    </p>


                    <?php if ((!empty($abstract))) : ?>
                        <p class="abstract-card mb-0 <?= $descriptionSize ?>">
                            <?= $abstract ?>
                        </p>
                    <?php else : ?>
                        <div class=" <?= $descriptionSize ?>">
                            <?= $description ?>
                        </div>
                    <?php endif; ?>


                </div>


                <?php if (!empty($starsNumber)) { ?>
                    <?= $this->render('bi-form-rating', [
                        'model' => $model,
                        'attribute' => $attribute,
                        'name' => $name,
                        'value' => $rating,
                        'inputId' => !empty($model->id) ? $model->id : $inputId,
                        'readonly' => $isReadOnly,
                        'starsNumber' => $starsNumber
                    ]);
                    ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

