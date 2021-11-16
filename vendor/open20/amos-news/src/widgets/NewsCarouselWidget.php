<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets
 * @category   CategoryName
 */

namespace open20\amos\news\widgets;

use open20\amos\core\forms\AmosCarouselWidget;
use open20\amos\news\models\News;
use yii\db\ActiveQuery;

/**
 * Class NewsCarouselWidget
 * @package open20\amos\news\widgets
 */
class NewsCarouselWidget extends AmosCarouselWidget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setItems($this->getNewsItems());

        parent::init();
    }

    /**
     * @return array
     */
    protected function getNewsItems()
    {
        $newsHighlights = [];
        $highlightsModule = \Yii::$app->getModule('highlights');

        if (!is_null($highlightsModule)) {
            /** @var \amos\highlights\Module $highlightsModule */
            $newsHighlightsIds = $highlightsModule->getHighlightedContents(News::className());
            /** @var ActiveQuery $query */
            $query = News::find()
                ->distinct()
                ->andWhere(['id' => $newsHighlightsIds])
                ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])
                ->andWhere(['or',
                    ['data_rimozione' => null],
                    ['>=', 'data_rimozione', date('Y-m-d')]
                ]);
            
            $newsHighlights = $query->all();
        }

        return $newsHighlights;
    }
}