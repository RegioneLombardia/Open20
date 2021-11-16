<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\views\comment\email
 * @category   CategoryName
 */

use open20\amos\comments\AmosComments;

/**
 * @var \open20\amos\core\interfaces\ModelLabelsInterface $model
 * @var \open20\amos\comments\models\Comment $modelComment
 */

?>

<div style="box-sizing:border-box;color:#000000;">
    <div style="padding:5px 10px;background-color: #F2F2F2;text-align:center;">
        <h1 style="color:#297A38;font-size:1.5em;margin:0;">
            <?= AmosComments::t('amoscomments', '#user_published_comment', [$modelComment->createdUserProfile->getSurnameName()]) ?>
        </h1>
        <p style="font-size:1em;margin:0;margin-top:5px;">
            <?= AmosComments::t('amoscomments', '#there_have_content_interest', [$model->getGrammar()->getIndefiniteArticle(), $model->getGrammar()->getModelSingularLabel()]) ?>
        </p>
    </div>
</div>
