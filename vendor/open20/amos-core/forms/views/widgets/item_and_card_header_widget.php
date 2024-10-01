<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\forms\views\widgets
 * @category   CategoryName
 */

//use open20\amos\core\forms\InteractionMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;

/**
 * @var string $contentCreatorAvatar Avatar of the content creator.
 * @var string $contentCreatorNameSurname Name and surname of the content creator.
 * @var bool $hideInteractionMenu If true set the class to hide the interaction menu.
 * @var array $interactionMenuButtons Sets the interaction menu buttons.
 * @var array $interactionMenuButtonsHide Sets the interaction menu buttons to hide.
 * @var string $publicatonDate Publication date of the content.
 * @var string $customContent Custom content.
 * @var \open20\amos\core\forms\ItemAndCardHeaderWidget $widget
 */

?>

<div class="post-header">
    <div class="flexbox">
        <div class="post-header-avatar">
            <?= $contentCreatorAvatar ?>
        </div>
        <div>
            <div class="flexbox">
                <p class="creator"><?= $widget->getCreator($contentCreatorNameSurname) ?></p>
                <?php if (!empty(\open20\amos\core\utilities\CwhUtility::getTargetsString($model)) &&
                isset($showPrevalentPartnershipAndTargets) && $showPrevalentPartnershipAndTargets
                
                ) : ?>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="<?= \open20\amos\core\utilities\CwhUtility::getTargetsString($model) ?>">
                        <span class="mdi mdi-account-supervisor-circle text-muted"></span>
                        <span class="sr-only"><?= \open20\amos\core\utilities\CwhUtility::getTargetsString($model) ?></span>
                    </a>
                <?php endif; ?>
            </div>
            <?php if (isset($contentPrevalentPartnership) && $contentPrevalentPartnership) : ?>
                <p class="card-prevalent-partnership"><small><?= $contentPrevalentPartnership ?></small></p>
            <?php endif; ?>
           
            <?php if (isset($customContent) && $customContent) : ?>
                <div class="custom-content"><?= $customContent; ?></div>
            <?php endif; ?>
            <?php if ($publicatonDate): ?>
                <p class="publication-date"><?= BaseAmosModule::t('amoscore', 'Pubblicato il') ?> <?= $publicatonDate ?></p>
            <?php endif; ?>
        </div>
        
        
    </div>
   

</div>
