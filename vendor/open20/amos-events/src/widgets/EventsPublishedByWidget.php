<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets
 * @category   CategoryName
 */

namespace open20\amos\events\widgets;

use yii\base\Widget;

/**
 * Class EventsPublishedByWidget
 * Shows the entities name publishing the content and the selected publication rule
 * @package open20\amos\events\widgets
 */
class EventsPublishedByWidget extends Widget
{
    /**
     * @var string $layout The layout view
     */
    public $layout = '@vendor/open20/amos-events/src/views/event-wizard/layouts/event_published_by_widget.php';

    /**
     * @var array $entities The list of entities publishing a specific content (news, topic, etc)
     */
    public $entities;

    /**
     * @var integer $publicationRule The id of the publication rule for the specific content
     */
    public $publicationRule;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $i = 0;
        $publishingEntities = '-';
        $recipients = '-';
        if (!is_null($this->entities) && (count($this->entities) > 0)) {
            $publishingEntities = '';
            $moduleCwh = \Yii::$app->getModule('cwh');
            foreach ($this->entities as $publishingEntity) {
                if (isset($moduleCwh)) {
                    $entity = \open20\amos\cwh\models\CwhNodi::findOne($publishingEntity);
                }
                
                if ($i > 0) {
                    $publishingEntities .= ', ';
                }
                
                if (!empty($entity)) {
                    $publishingEntities .= $entity->text;
                }
                
                $i++;
            }
        }
        
        if (!is_null($this->publicationRule)) {
            $pubblicationRule = \open20\amos\cwh\models\base\CwhRegolePubblicazione::findOne($this->publicationRule);
            $recipients = (!is_null($pubblicationRule) ? $pubblicationRule->nome : '-');
        }
        
        return $this->renderFile(
            $this->layout,
            [
                'publishingEntities' => $publishingEntities,
                'recipients' => $recipients
            ]
        );
    }
}
