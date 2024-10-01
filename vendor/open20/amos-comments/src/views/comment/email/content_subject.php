<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\views\comment\email
 * @category   CategoryName
 */

use open20\amos\comments\AmosComments;
use open20\amos\core\interfaces\BaseContentModelInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;

/**
 * @var \open20\amos\core\record\Record $contextModel
 */

$title = $contextModel->__toString();
if (($contextModel instanceof BaseContentModelInterface) || $contextModel->hasMethod('getTitle')) {
    $title = $contextModel->getTitle();
}
if (($contextModel instanceof BaseContentModelInterface) || $contextModel->hasMethod('getSiglaPiattaforma') || $contextModel->hasMethod('getTitle')) {
    $sigla_platform = $contextModel->getSiglaPiattaforma()." | ".$contextModel->getTitle();
}

$label = '-';

if(!empty($sigla_platform)){
    $title = $sigla_platform;
}
if (($contextModel instanceof ModelLabelsInterface) || $contextModel->hasMethod('getGrammar')) {
    $label = $contextModel->getGrammar()->getModelSingularLabel();
}

?>

<?= AmosComments::t('amoscomments', '#notification_email_subject', [$label, $title]); ?>
