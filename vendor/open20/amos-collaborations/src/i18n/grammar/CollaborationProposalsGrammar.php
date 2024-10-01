<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\collaborations\i18n\grammar;

use open20\amos\collaborations\utility\CollaborationProposalsEmailUtility;
use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\collaborations\Module;
use Yii;
use yii\helpers\Console;


class CollaborationProposalsGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return \Yii::t('amoscollaborations', '#collaboration_proposals_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return \Yii::t('amoscollaborations', '#collaboration_proposals_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return \Yii::t('amoscollaborations', '#collaboration_proposals_article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return \Yii::t('amoscollaborations', '#collaboration_proposals_article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return \Yii::t('amoscollaborations', '#collaboration_proposals_indefinite_article');
    }
}
