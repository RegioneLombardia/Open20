<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\components
 * @category   CategoryName
 */

namespace open20\amos\events\components;

use open20\amos\attachments\models\File;
use open20\amos\core\components\PartQuestionarioAbstract;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use Yii;
use yii\web\UploadedFile;

/**
 * Class PartsWizardEventCreation
 * @package open20\amos\events\components
 */
class PartsWizardEventCreation extends PartQuestionarioAbstract
{
    const PART_INTRODUCTION = 'introduction';
    const PART_DESCRIPTION = 'description';
    const PART_ORGANIZATIONALDATA = 'organizational-data';
    const PART_PUBLICATION = 'publication';
    const PART_SUMMARY = 'summary';
    const PART_FINISH = 'finish';

    /**
     * @var Event $event The event model.
     */
    public $event;

    /**
     * @inheritdoc
     */
    public function getStatus($part)
    {
        if ($part == $this->current || $part == $this->currentChild) {
            if (Yii::$app->getRequest()->getIsPost() && !$this->isCompleted($part)) {
                self::$HAS_ERROR = true;
                return self::STATUS_ERROR;
            }
            return self::STATUS_CURRENT;
        } elseif ($this->partIsPostCurrent($part)) {
            return self::STATUS_UNDONE;
        } elseif ($this->isCompleted($part)) {
            return self::STATUS_COMPLETED;
        }
        return self::STATUS_UNDONE;
    }

    /**
     * @inheritdoc
     */
    public function initMap()
    {
        $i = 1;
        self::$map = [
            self::PART_INTRODUCTION => [
                'label' => AmosEvents::t('amosevents', 'Introduction'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_INTRODUCTION),
                'title' => $this->getTitle(self::PART_INTRODUCTION),
                'url' => $this->createUrl([self::PART_INTRODUCTION, 'id' => $this->event->id]),
            ],
            self::PART_DESCRIPTION => [
                'label' => AmosEvents::t('amosevents', 'Description'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_DESCRIPTION),
                'title' => $this->getTitle(self::PART_DESCRIPTION),
                'url' => $this->createUrl([self::PART_DESCRIPTION, 'id' => $this->event->id]),
            ],
            self::PART_ORGANIZATIONALDATA => [
                'label' => AmosEvents::t('amosevents', 'Organizational data'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_ORGANIZATIONALDATA),
                'title' => $this->getTitle(self::PART_ORGANIZATIONALDATA),
                'url' => $this->createUrl([self::PART_ORGANIZATIONALDATA, 'id' => $this->event->id]),
            ],
            self::PART_PUBLICATION => [
                'label' => AmosEvents::t('amosevents', 'Publication'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_PUBLICATION),
                'title' => $this->getTitle(self::PART_PUBLICATION),
                'url' => $this->createUrl([self::PART_PUBLICATION, 'id' => $this->event->id]),
            ],
            self::PART_SUMMARY => [
                'label' => AmosEvents::t('amosevents', 'Summary'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_SUMMARY),
                'title' => $this->getTitle(self::PART_SUMMARY),
                'url' => $this->createUrl([self::PART_SUMMARY, 'id' => $this->event->id]),
            ],
            self::PART_FINISH => [
                'label' => AmosEvents::t('amosevents', 'Finish'),
                'index' => $i++,
                'description' => '',
                'status' => $this->getStatus(self::PART_FINISH),
                'title' => $this->getTitle(self::PART_FINISH),
                'url' => $this->createUrl([self::PART_FINISH, 'id' => $this->event->id]),
            ]
        ];
    }

    /**
     * @param string $part
     * @return bool
     */
    public function isCompleted($part)
    {
        switch ($part) {
            case self::PART_INTRODUCTION:
                $oldScenario = $this->event->getScenario();
                $cwhBehavior = $this->event->getBehavior('cwhBehavior');
                if (!empty($cwhBehavior)) {
                    $this->event->detachBehavior('cwhBehavior');
                }
                $this->event->setScenario(Event::SCENARIO_INTRODUCTION);
                if ($this->event->validate()) {
                    $this->event->setScenario($oldScenario);
                    if (!empty($cwhBehavior)) {
                        $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                    }
                    return true;
                }
                $this->event->setScenario($oldScenario);
                if (!empty($cwhBehavior)) {
                    $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                }
                break;
            case self::PART_DESCRIPTION:
                $oldScenario = $this->event->getScenario();
                $cwhBehavior = $this->event->getBehavior('cwhBehavior');
                if (!empty($cwhBehavior)) {
                    $this->event->detachBehavior('cwhBehavior');
                }
                $this->event->setScenario(Event::SCENARIO_DESCRIPTION);
                $logoOk = true;
                if (!is_null($this->event->eventType) && $this->event->eventType->logoRequested) {
                    $logoOk = (($this->event->eventLogo instanceof UploadedFile) || ($this->event->eventLogo instanceof File));
                }
                if ($this->event->validate() && $logoOk) {
                    $this->event->setScenario($oldScenario);
                    if (!empty($cwhBehavior)) {
                        $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                    }
                    return true;
                }
                $this->event->setScenario($oldScenario);
                if (!empty($cwhBehavior)) {
                    $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                }
                break;
            case self::PART_ORGANIZATIONALDATA:
                $oldScenario = $this->event->getScenario();
                $cwhBehavior = $this->event->getBehavior('cwhBehavior');
                if (!empty($cwhBehavior)) {
                    $this->event->detachBehavior('cwhBehavior');
                }
                $this->event->setScenario(Event::SCENARIO_ORGANIZATIONALDATA);
                if ($this->event->validate()) {
                    $this->event->setScenario($oldScenario);
                    if (!empty($cwhBehavior)) {
                        $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                    }
                    return true;
                }
                $this->event->setScenario($oldScenario);
                if (!empty($cwhBehavior)) {
                    $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                }
                break;
            case self::PART_PUBLICATION:
                $oldScenario = $this->event->getScenario();
                $this->event->setScenario(Event::SCENARIO_PUBLICATION);
                if ($this->event->validate()) {
                    $this->event->setScenario($oldScenario);
                    return true;
                }
                $this->event->setScenario($oldScenario);
                break;
            case self::PART_SUMMARY:
                $cwhBehavior = $this->event->getBehavior('cwhBehavior');
                if (!empty($cwhBehavior)) {
                    $this->event->detachBehavior('cwhBehavior');
                }
                $oldScenario = $this->event->getScenario();
                $ok = (
                    $this->isCompleted(self::PART_INTRODUCTION) &&
                    $this->isCompleted(self::PART_DESCRIPTION) &&
                    $this->isCompleted(self::PART_ORGANIZATIONALDATA) &&
                    $this->isCompleted(self::PART_PUBLICATION) &&
                    $this->event->validate()
                );
                $this->event->setScenario($oldScenario);
                if (!empty($cwhBehavior)) {
                    $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                }
                return $ok;
                break;
            case self::PART_FINISH:
                $cwhBehavior = $this->event->getBehavior('cwhBehavior');
                if (!empty($cwhBehavior)) {
                    $this->event->detachBehavior('cwhBehavior');
                }
                $oldScenario = $this->event->getScenario();
                $ok = $this->isCompleted(self::PART_SUMMARY);
                $this->event->setScenario($oldScenario);
                if (!empty($cwhBehavior)) {
                    $this->event->attachBehavior('cwhBehavior', $cwhBehavior);
                }
                return $ok;
                break;
        }

        if (!Yii::$app->getRequest()->getIsPost()) {
            $this->event->clearErrors();
        }

        return false;
    }
}
