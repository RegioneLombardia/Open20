<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\widgets
 * @category   CategoryName
 */

namespace open20\amos\discussioni\widgets;

use open20\amos\core\forms\AmosCarouselWidget;
use open20\amos\discussioni\models\DiscussioniTopic;
use yii\db\ActiveQuery;

/**
 * Class DiscussionsCarouselWidget
 * @package open20\amos\discussioni\widgets
 */
class DiscussionsCarouselWidget extends AmosCarouselWidget {

    /**
     * @inheritdoc
     */
    public function init() {
        $this->setItems($this->getDiscussionsItems());

        parent::init();
    }

    /**
     * @return array
     */
    protected function getDiscussionsItems() {
        $discussionsHighlights = [];
        $highlightsModule = \Yii::$app->getModule('highlights');

        if (!is_null($highlightsModule)) {
            /** @var \amos\highlights\Module $highlightsModule */
            $discussionsHighlightsIds = $highlightsModule->getHighlightedContents(DiscussioniTopic::className());
            /** @var ActiveQuery $query */
            $query = DiscussioniTopic::find()
                ->distinct()
                ->andWhere(['id' => $discussionsHighlightsIds])
                ->andWhere(['status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA]);

            $discussionsHighlights = $query->all();
        }

        return $discussionsHighlights;
    }

}
